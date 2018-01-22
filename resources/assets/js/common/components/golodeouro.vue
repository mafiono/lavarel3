<template>
    <transition name="vue-fade-in">
        <div class="golodeouro" v-if="visible">
            <div class="golodeouro-header">
                <div class="header-left">
                    <div class="title1">GOLO D'OURO</div>
                    <div class="title2">Esta semana ganhe</div>
                    <div class="title3" v-for="golo in golos" >Até {{formatPrice(golo.odd * golo.maxValue)}}€</div>
                    <div class="title4">Quem marca, quando marca, e o resultado final.</div>
                </div>
                <div class="header-right">
                    <div class="image">
                        <img src="assets/portal/img/golodeouro.png" width="168px">
                    </div>
                </div>
            </div>
            <input id="id" style="display:none" v-for="golo in golos" :value=golo.id>

            <div class="golodeouro-container">
                <div class="golodeouro-fixture-title" v-for="golo in golos">
                    {{golo.name}}
                </div>
                <div class="golodeouro-fixture-time" v-for="golo in golos">
                    {{golo.startTime}} | Futebol
                </div>
                <div class="golodeouro-fixture-markets">
                    <select id="marcador" v-model="marcador">
                        <option value="" disabled selected>1ºMarcador</option>
                        <option :value="firstscorer.id" v-for="firstscorer in firstscorers">{{firstscorer.name}}</option>
                    </select>
                    <select id="minuto" v-model="minuto">
                        <option value="" disabled selected>Minuto</option>
                        <option :value="gametime.id" v-for="gametime in gametimes">{{gametime.name}}</option>
                    </select>
                    <select id="resultado" v-model="resultado">
                        <option value="" disabled selected>Resultado</option>
                        <option :value="result.id" v-for="result in results">{{result.name}}</option>
                    </select>
                </div>

                <div class="golodeouro-bet">
                    <select id="valor" v-model="valor">
                        <option value="" disabled selected>Montante</option>
                        <option :value="value.amount" v-for="value in values">{{value.amount}}€</option>
                    </select>

                    <div class="flavor" v-if="valor === ''">
                        Faça a sua seleção e ganhe <div class="value" v-for="golo in golos">{{formatPrice(golo.odd * valor)}}€</div>
                    </div>
                    <div v-for="golo in golos" class="flavor" v-else>
                        Cotas : {{golo.odd}} &nbsp Possível retorno: <div class="value" v-for="golo in golos">{{formatPrice(golo.odd * valor)}}€</div>
                    </div>

                    <div id="btn-apostar" class="bet" @click.prevent="performAction()" ><button id="item-apostar">Apostar</button ><span id="item-aguarde" style="display: none;">Aguarde...</span></div>
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
<style>

</style>
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
                    .success(this.submitDone())
                    .error(this.submitFail());

            },
            submitDone()
            {
                var submitBtn = $("#btn-apostar");
                submitBtn.prop("disabled", false);
                $("#item-apostar").show();
                $("#item-aguarde").hide();
                $("#blocker-container").removeClass("blocker");
                $.fn.popup({
                    type: 'success',
                    title: 'Erro',
                    text: 'Erro, se',
            });
            },
            submitFail()
            {
                var submitBtn = $("#btn-apostar");
                submitBtn.prop("disabled", false);
                $("#item-apostar").show();
                $("#item-aguarde").hide();
                $("#blocker-container").removeClass("blocker");
                $.fn.popup({
                    type: 'error',
                    title: 'Erro',
                    text: 'Erro, serviço de apostas indisponível ou limites ultrapassados',
                });


            },

            fetchfirstscorers(){
                $.getJSON("http://localhost:64193/api/v1/goldengoal/"+ this.golos[0].id +"/markets/marcador/selections")
                    .done(data => {
                        data.data.forEach(goalscorer => this.firstscorers.push(goalscorer));
                    });
            },
            fetchresults(){
                $.getJSON("http://localhost:64193/api/v1/goldengoal/"+ this.golos[0].id +"/markets/resultado final/selections")
                    .done(data => {
                        data.data.forEach(result => this.results.push(result));
                    });
            },
            fetchtimes(){
                $.getJSON("http://localhost:64193/api/v1/goldengoal/"+ this.golos[0].id +"/markets/Minuto Primeiro Golo/selections")
                    .done(data => {
                        data.data.forEach(gametime => this.gametimes.push(gametime));
                    });
            },
            fetchgolo(){
                $.getJSON("http://localhost:64193/api/v1/goldengoal/active")
                    .done(data => {
                         this.golos.push(data.data);
                    });
            },
            fetchvalues(){
                $.getJSON("http://localhost:64193/api/v1/goldengoal/"+ this.golos[0].id +"/values")
                    .done(data => {
                        data.data.forEach(value => this.values.push(value));
                    });
            },
            fetchinactives(){
                $.getJSON("http://localhost:64193/api/v1/goldengoal/lastactive")
                    .done(data => {
                        data.data.forEach(inactive => this.inactives.push(inactive));
                    });
            },

            setFrame(){
                $.getJSON("http://localhost:64193/api/v1/goldengoal/active")
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
