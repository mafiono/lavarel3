<template>
    <transition name="vue-fade-in">
        <div class="bs-wp golodeouro" v-if="visible">
            <div class="row golodeouro-header-padding" v-if="golos.length > 0">
                <div class="col-md-12 golodeouro-header">
                <div class="infogolodeouro" v-if="golo.fixtureId === 0">Não existe golo de ouro ativo</div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header-wrapper">
                                <div class="header-left" >
                                    <div class="title1 orange big-xs big-md top title-header">{{details.title}}</div>
                                    <div class="title2 white big-xs big-md title-bold title-subtitle">{{details.subtitle}}</div>
                                    <div class="title3 white title-text">{{details.text}}</div>
                                </div>
                                <div class="header-right" v-if="golo.fixtureId !== 0">
                                    <div class="image">
                                        <img src="assets/portal/img/golodeouro.png"  class="image-xs image-md" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="title3-mobile">{{details.text}}</div>
                    </div>
                </div>
            </div>
            <input id="id" style="display:none" :value=golo.id>
            <div class="row golodeouro-header-padding" v-if="golo.fixtureId !== 0">
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
                                <option :value="firstscorer.id" v-for="firstscorer in firstscorers">{{firstscorer.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            2
                        </div>
                        <div class="col-sm-4 select-golodeouro  small-xs-9 ">
                            <select id="minuto" class="form-control" v-model="minuto">
                                <option value="" disabled selected>Minuto</option>
                                <option :value="gametime.id" v-for="gametime in gametimes">{{gametime.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            3
                        </div>
                        <div class="col-sm-4 select-golodeouro  small-xs-9">
                            <select id="resultado" class="form-control" v-model="resultado">
                                <option value="" disabled selected>Resultado</option>
                                <option :value="result.id" v-for="result in results">{{result.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            4
                        </div>
                        <div class="col-sm-4  small-xs-9 ">
                            <select id="valor"  class="form-control" v-model="valor">
                                <option value="" disabled selected>Montante</option>
                                <option :value="value.amount" v-for="value in values">{{value.amount}}€</option>
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
            <div class="golodeouro-history">
                Ultimo Resultado:
                <div class="whitebar"> </div>
                <p></p>
                <div v-if="inactives.length">
                    <div>{{inactives[0].fixtureName}}</div>
                    {{formatTimeOfGame(inactives[0].startTime)}} | Futebol
                </div>
                <div class="last-golodeouro-header">&nbsp;</div>

                <div class="last-golodeouro-row" v-for="inactive in inactives">
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
        data(){
            return {
                firstscorers:[],
                results:[],
                gametimes:[],
                golos:[],
                values:[],
                inactives:[],
                marcador:"",
                minuto:"",
                valor:"",
                id:"",
                resultado:"",
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
            performAction(){
                if(!userAuthenticated){
                    page('/registar');
                } else {
                    this.disableSubmit();
                    this.submit();
                    //$.post( "/golodeouro/aposta", {marcador:this.marcador,minuto:this.minuto,resultado:this.resultado,valor:this.valor,id:$('#id').val()});
                }
            },
            disableSubmit()
            {
                var submitBtn = $("#btn-apostar");
                $("#item-apostar").hide();
                $("#item-aguarde").show();
                submitBtn.prop("disabled", true);
                $("#blocker-container").addClass("blocker");
            },
            submit()
            {
                $.post( "/golodeouro/aposta", {
                    marcador: this.marcador,
                    minuto: this.minuto,
                    resultado: this.resultado,
                    valor: this.valor,
                    id: $('#id').val()
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

            fetchfirstscorers(){
                $.getJSON('/api/selections/'+this.golos[0].id+'/Marcador')
                    .done(data => {
                        data.data.forEach(goalscorer => this.firstscorers.push(goalscorer));
                    });
            },
            fetchresults(){
                $.getJSON('/api/selections/'+this.golos[0].id+'/Resultado final')
                    .done(data => {
                        data.data.forEach(result => this.results.push(result));
                    });
            },
            fetchtimes(){
                $.getJSON('/api/selections/'+this.golos[0].id+'/Minuto Primeiro Golo')
                    .done(data => {
                        data.data.forEach(gametime => this.gametimes.push(gametime));
                    });
            },

            fetchgolo(){
                $.getJSON('/api/active')
                    .done(data => {
                        this.golos.push(data.data);
                    });
            },
            fetchvalues(){
                $.getJSON('/api/'+this.golos[0].id+'/values')
                    .done(data => {
                        data.data.forEach(value => this.values.push(value));
                    });
            },
            fetchinactives(){
                $.getJSON('/api/lastactive')
                    .done(data => {
                        data.data.forEach(inactive => this.inactives.push(inactive));
                    });
            },
            setFrame(){
                $.getJSON('/api/active');
            },
            formatTimeOfGame(time) {
                return moment(time).format('DD MMM - HH:mm').toUpperCase();
            }
        },
        computed: {
            golo() {
                if (this.golos !== null && this.golos.length)
                    return this.golos[0];
                return null;
            },
            details() {
                if (this.golo !== null && this.golo.details)
                    return JSON.parse(this.golo.details);
                return {
                    title: '',
                    subtitle: '',
                    text: '',
                };
            },
            timeOfGame() {
                return this.formatTimeOfGame(this.golo.startTime);
            },
            loaded() {
                return Store.golodeouro.loaded;
            },
            visible() {
                return this.golo !== null && Store.golodeouro.visible;
            },
        },
        watch: {
            'golos': function(){
                if(this.golos.length > 0){
                    this.fetchvalues();
                    this.fetchtimes();
                    this.fetchfirstscorers();
                    this.fetchresults();
                }
            },
        },
        components: {
            'golodeouro': require('./golodeouro.vue')
        },
        mounted() {
            this.fetchgolo();
            this.fetchinactives();
            this.setFrame();
        }
    }
</script>
