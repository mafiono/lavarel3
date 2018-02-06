<template>

    <transition name="vue-fade-in">

        <div class="bs-wp golodeouro" v-if="visible">
            <div class="row golodeouro-header-padding">
                <div class="col-md-12 golodeouro-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header-left" >
                                <div class="title orange big-xs big-md topo title-header">GOLO D'OURO</div>
                                <div class="title white medium-xs medium-md title-subtitle">ESTA SEMANA GANHE</div>
                                <div class="title white big-xs big-md title-bold" v-for="golo in golos" >ATÉ {{formatPrice(golo.odd * golo.maxValue)}}€</div>
                                <div class="title  white small-xs small-md hidden-xs">Quem marca, quando marca, e o resultado final.</div>
                            </div>
                            <div class="header-right">
                                <div class="image">
                                    <img src="assets/portal/img/golodeouro.png"  class="image-xs image-md" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="title  white small-xs small-md visible-xs">Quem marca, quando marca, e o resultado final.</div>
                        </div>
                    </div>
                </div>
            </div>


            <input id="id" style="display:none" v-for="golo in golos" :value=golo.id>
            <div class="row golodeouro-header-padding">
                <div class="col-md-12 golodeouro-container">
                    <div class="row">
                        <div class="col-md-2">

                        </div>
                        <div class=" col-md-8 col-offset-md-2">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <div class="golodeouro-fixture-title" v-for="golo in golos">
                                        {{golo.name}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="golodeouro-fixture-time" v-for="golo in golos">
                                        {{golo.startTime}} | Futebol
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row golodeouro-fixture-markets">
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            1
                        </div>
                        <div class="col-md-4  small-xs-9 select-golodeouro">
                            <select id="marcador" class="form-control" v-model="marcador">
                                <option value="" disabled selected>1ºMarcador</option>
                                <option :value="firstscorer.id" v-for="firstscorer in firstscorers">{{firstscorer.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            2
                        </div>
                        <div class="col-md-4 select-golodeouro  small-xs-9 ">
                            <select id="minuto" class="form-control" v-model="minuto">
                                <option value="" disabled selected>Minuto</option>
                                <option :value="gametime.id" v-for="gametime in gametimes">{{gametime.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            3
                        </div>
                        <div class="col-md-4 select-golodeouro  small-xs-9">
                            <select id="resultado" class="form-control" v-model="resultado">
                                <option value="" disabled selected>Resultado</option>
                                <option :value="result.id" v-for="result in results">{{result.name}}</option>
                            </select>
                        </div>
                        <div class="col-xs-3 visible-xs titulo white big-xs-2">
                            4
                        </div>
                        <div class="col-md-4  small-xs-9 ">

                            <select id="valor"  class="form-control"  v-model="valor">
                                <option value="" disabled selected>Montante</option>
                                <option :value="value.amount" v-for="value in values">{{value.amount}}€</option>
                            </select>




                        </div>
                        <div class="col-md-8  small-xs-12 ">



                            <div class="row golodeouro-bet">

                                <div class="col-md-6" style="margin-top:10px;">
                                    <div class="flavor" v-if="valor === ''">
                                        Faça a sua seleção e ganhe <div class="value" v-for="golo in golos">{{formatPrice(golo.odd * valor)}}€</div>
                                    </div>
                                    <div v-for="golo in golos" class="flavor" v-else>
                                        Cotas : {{golo.odd}} &nbsp Possível retorno: <div class="value" v-for="golo in golos">{{formatPrice(golo.odd * valor)}}€</div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:10px;">
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
                <div v-model="inactives" v-if="inactives.length">
                    <p>{{inactives[0].fixtureName}}</p>
                    {{inactives[0].startTime}}
                </div>
                <div class="last-golodeouro-header">
                </div>
                <div class="last-golodeouro-left">
                    <p v-for="inactive in inactives">{{inactive.marketName}}:</p>
                </div>
                <div class="last-golodeouro-right">
                    <p v-for="inactive in inactives">{{inactive.selectionName}}</p>
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
                $.post( "/golodeouro/aposta", {marcador:this.marcador,minuto:this.minuto,resultado:this.resultado,valor:this.valor,id:$('#id').val()})
                    .done(function(data){
                        var submitBtn = $("#btn-apostar");
                        submitBtn.prop("disabled", false);
                        $("#item-apostar").show();
                        $("#item-aguarde").hide();
                        $("#blocker-container").removeClass("blocker");

                        if(data.msg)
                        {
                            $.fn.popup({
                                type: 'error',
                                title: 'Erro',
                                text: data.msg,
                            });
                        }else{
                            $.fn.popup({
                                type: 'success',
                                title: 'Sucesso',
                                text: data,
                            });
                        }});

            },


            fetchfirstscorers(){
                $.getJSON("https://api-issue-2-dev.casinoportugal.pt/api/v1/goldengoal/"+ this.golos[0].id +"/markets/marcador/selections")
                    .done(data => {
                        data.data.forEach(goalscorer => this.firstscorers.push(goalscorer));
                    });
            },
            fetchresults(){
                $.getJSON("https://api-issue-2-dev.casinoportugal.pt/api/v1/goldengoal/"+ this.golos[0].id +"/markets/resultado final/selections")
                    .done(data => {
                        data.data.forEach(result => this.results.push(result));
                    });
            },
            fetchtimes(){
                $.getJSON("https://api-issue-2-dev.casinoportugal.pt/api/v1/goldengoal/"+ this.golos[0].id +"/markets/Minuto Primeiro Golo/selections")
                    .done(data => {
                        data.data.forEach(gametime => this.gametimes.push(gametime));
                    });
            },
            fetchgolo(){
                $.getJSON("https://api-issue-2-dev.casinoportugal.pt/api/v1/goldengoal/active")
                    .done(data => {
                        this.golos.push(data.data);
                    });
            },
            fetchvalues(){
                $.getJSON("https://api-issue-2-dev.casinoportugal.pt/api/v1/goldengoal/"+ this.golos[0].id +"/values")
                    .done(data => {
                        data.data.forEach(value => this.values.push(value));
                    });
            },
            fetchinactives(){
                $.getJSON("https://api-issue-2-dev.casinoportugal.pt/api/v1/goldengoal/lastactive")
                    .done(data => {
                        data.data.forEach(inactive => this.inactives.push(inactive));
                    });
            },

            setFrame(){
                $.getJSON("https://api-issue-2-dev.casinoportugal.pt/api/v1/goldengoal/active")
                    .done(data => {
                        $('#statsgolo').attr('src','https://www.score24.com/statistics3/index.jsp?partner=casinoportugal&gameId=' + data.data.externalId);
                    });

            }

        },
        computed: {
            loaded() {
                return Store.golodeouro.loaded;
            },
            visible() {
                return Store.golodeouro.visible;
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
