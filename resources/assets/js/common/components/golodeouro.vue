<template>
    <transition name="vue-fade-in">
        <div class="bs-wp golodeouro" id="golodeouro">
            <div class="row golodeouro-header-padding">
                <div class="col-md-12 golodeouro-header">
                    <div class="row" v-if="visible() && goloValid">
                        <div class="col-md-12">
                            <div class="header-wrapper">
                                <div class="header-left">
                                    <div class="title1 orange big-xs big-md top title-header">{{golo.details.title}}</div>
                                    <div class="title2 white big-xs big-md title-bold title-subtitle">{{golo.details.subtitle}}</div>
                                    <div class="title3 white title-text">{{golo.details.text || ''}}</div>
                                </div>
                                <div class="header-right" v-if="golo.fixtureId !== 0">
                                    <div class="image">
                                        <img src="assets/portal/img/golodeouro.png"  class="image-xs image-md" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="title3-mobile">{{golo.details.text}}</div>
                    </div>
                    <div class="infogolodeouro" v-if="golo !== null && golo.id === 0">
                        De momento não há Golo d'Ouro.<br>
                        Aproveite uma das nossas outras excelentes <a href="/promocoes">Promoções</a>
                    </div>
                    <div class="loading" v-if="golo === null">
                        <i class="cp-spin cp-spinner2"></i>
                    </div>
                </div>
            </div>
            <div class="row golodeouro-header-padding" v-if="golo !== null && golo.id !== 0">
                <div class="col-md-12 golodeouro-container">
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class=" col-md-8 col-offset-md-2">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <div class="golodeouro-fixture-title">
                                        {{golo.name}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="golodeouro-fixture-time">
                                        {{timeOfGame}} | Futebol
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row golodeouro-fixture-markets">
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            1
                        </div>
                        <div class="col-sm-4  small-xs-9 select-golodeouro">
                            <select id="marcador" class="form-control" v-model="marcador">
                                <option value="" disabled selected>1ºMarcador</option>
                                <option :value="firstscorer.id" v-for="firstscorer in golo.competitors">{{firstscorer.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            2
                        </div>
                        <div class="col-sm-4 select-golodeouro  small-xs-9 ">
                            <select id="minuto" class="form-control" v-model="minuto">
                                <option value="" disabled selected>Minuto</option>
                                <option :value="gametime.id" v-for="gametime in golo.times">{{gametime.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            3
                        </div>
                        <div class="col-sm-4 select-golodeouro  small-xs-9">
                            <select id="resultado" class="form-control" v-model="resultado">
                                <option value="" disabled selected>Resultado</option>
                                <option :value="result.id" v-for="result in golo.results">{{result.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            4
                        </div>
                        <div class="col-sm-4  small-xs-9 ">
                            <select id="valor"  class="form-control" v-model="valor">
                                <option value="" disabled selected>Montante</option>
                                <option :value="value.amount" v-for="value in golo.amounts">{{value.amount}}€</option>
                            </select>
                        </div>
                        <div class="col-sm-8 small-xs-12">
                            <div class="row golodeouro-bet">
                                <div class="col-sm-6" style="padding: 0 15px 15px;">
                                    <div class="flavor flavor-xs" v-if="valor === ''">
                                        Faça a sua seleção e ganhe <div class="value">{{formatPrice(golo.odd * valor)}}€</div>
                                    </div>
                                    <div class="flavor flavor-xs" v-else>
                                        Cotas : {{golo.odd}} <br> <div class="value-text">Possível retorno:</div> <div class="value">{{formatPrice(golo.odd * valor)}}€</div>
                                    </div>
                                </div>
                                <div class="col-sm-6" style="padding-left: 15px;padding-right: 15px" >
                                    <div id="btn-apostar" class="bet" @click.prevent="performAction()" ><button id="item-apostar">Apostar</button ><span id="item-aguarde" style="display: none;">Aguarde...</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="golodeouro-history" v-if="golo !== null">
                Ultimo Resultado:
                <div class="whitebar"> </div>
                <p></p>
                <div v-if="golo.inactives && golo.inactives.length">
                    <div>{{golo.inactives[0].fixtureName}}</div>
                    {{formatTimeOfGame(golo.inactives[0].startTime)}} | Futebol
                </div>
                <div class="last-golodeouro-header">&nbsp;</div>

                <div class="last-golodeouro-row" v-for="inactive in golo.inactives">
                    <div class="last-golodeouro-left">
                        {{inactive.marketName}}:
                    </div>
                    <div class="last-golodeouro-right">
                        {{inactive.selectionName}}
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                golo: null,
                marcador:"",
                minuto:"",
                valor:"",
                id:"",
                resultado:"",
                app: Store.app,
            }
        },
        methods: {
            exit() {
                page.back();
            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            performAction() {
                if(!userAuthenticated){
                    page('/registar');
                } else {
                    this.disableSubmit();
                    this.submit();
                }
            },
            disableSubmit() {
                var submitBtn = $("#btn-apostar");
                $("#item-apostar").hide();
                $("#item-aguarde").show();
                submitBtn.prop("disabled", true);
                $("#blocker-container").addClass("blocker");
            },
            submit() {
                $.post( "/golodeouro/aposta", {
                    marcador: this.marcador,
                    minuto: this.minuto,
                    resultado: this.resultado,
                    valor: this.valor,
                    id: this.golo.id
                })
                    .done(function(data){
                        var submitBtn = $("#btn-apostar");
                        submitBtn.prop("disabled", false);
                        $("#item-apostar").show();
                        $("#item-aguarde").hide();
                        $("#blocker-container").removeClass("blocker");
                        $.fn.popup({
                            type: 'success',
                            title: 'Sucesso',
                            text: 'Aposta efetuada com sucesso!',
                        });
                    })
                    .error(function(data){
                        var submitBtn = $("#btn-apostar");
                        submitBtn.prop("disabled", false);
                        $("#item-apostar").show();
                        $("#item-aguarde").hide();
                        $("#blocker-container").removeClass("blocker");
                        $.fn.popup({
                            type: 'error',
                            title: 'Erro',
                            text: JSON.parse(data.responseText).msg,
                        });
                    });
            },
            formatTimeOfGame(time) {
                return moment.utc(time).local().format('DD MMM - HH:mm').toUpperCase();
            },
            visible() {
                return this.golo !== null && Store.golodeouro.visible;
            },
        },
        computed: {
            timeOfGame() {
                return this.formatTimeOfGame(this.golo.startTime);
            },
            loaded() {
                return Store.golodeouro.loaded;
            },
            goloValid() {
                return this.golo !== null && this.golo.details !== null
                    && this.golo.details.title
                    && this.golo.details.subtitle
                    && this.golo.details.text
                ;
            }
        },
        watch: {
            'app.currentRoute': function (x) {
                Store.golodeouro.$show.next(x === '/golodeouro');
                $("#golodeouro").toggle(x === '/golodeouro');
            }
        },
        mounted() {
            Store.golodeouro.getFeed()
                .subscribe(x => { this.golo = x; }, err => {this.golo = {id:0,details:null}});
        }
    }

</script>
