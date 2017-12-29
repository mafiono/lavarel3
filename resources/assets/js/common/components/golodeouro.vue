<template>
    <transition name="vue-fade-in">
    <div class="golodeouro" v-if="visible">
        <div class="golodeouro-header">
        <div class="header-left">
            <div class="title1">GOLO D'OURO</div>
            <div class="title2">Esta semana ganhe</div>
            <div class="title3" v-for="golo in golos" >Até {{formatPrice(golo.odd * golo.max)}}€</div>
            <div class="title4">Quem marca, quando marca, e o resultado final.</div>
        </div>
        <div class="header-right">
            <div class="image">
            <img src="assets/portal/img/golodeouro.jpg" width="168px">
            </div>
        </div>
    </div>
        <input id="id" style="display:none" v-for="golo in golos" :value=golo.id>

    <div class="golodeouro-container">
        <div class="golodeouro-fixture-title" v-for="golo in golos">
            {{golo.name}}
        </div>
        <div class="golodeouro-fixture-time" v-for="golo in golos">
            {{golo.start}} | {{golo.sport}}
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
                <option :value="value.amount" v-for="value in values">{{value.amount}}</option>
            </select>

            <div class="flavor">
               Faça a sua seleção e ganhe até <div class="value" v-for="golo in golos">{{formatPrice(golo.odd * golo.max)}}€</div>
            </div>

            <div class="bet" @click.prevent="performAction()" ><button>Apostar</button></div>
        </div>
    </div>
        <div class="golodeouro-history">
            Ultimo Resultado:

            <div class="whitebar"></div>
            <p>{{inactives[0].game}}</p>
            <div class="last-golodeouro-header">
            </div>
            <div class="last-golodeouro-left">
                <p v-for="inactive in inactives">{{inactive.market}}:</p>
            </div>
            <div class="last-golodeouro-right">
                <p v-for="inactive in inactives">{{inactive.name}}</p>
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

                }else{
                    $.post( "/golodeouro/aposta", {marcador:this.marcador,minuto:this.minuto,resultado:this.resultado,valor:this.valor,id:$('#id').val()});
                }
            },

            fetchfirstscorers(){
                $.getJSON("/golodeouro/marcador")
                    .done(data => {
                        data.forEach(goalscorer => this.firstscorers.push(goalscorer));
                    });
            },
            fetchresults(){
                $.getJSON("/golodeouro/resultado")
                    .done(data => {
                        data.forEach(result => this.results.push(result));
                    });
            },
            fetchtimes(){
                $.getJSON("/golodeouro/tempo")
                    .done(data => {
                        data.forEach(gametime => this.gametimes.push(gametime));
                    });
            },
            fetchgolo(){
                $.getJSON("/golodeouro/golo")
                    .done(data => {
                        data.forEach(golo => this.golos.push(golo));
                    });
            },
            fetchvalues(){
                $.getJSON("/golodeouro/values")
                    .done(data => {
                        data.forEach(value => this.values.push(value));
                    });
            },
            fetchinactives(){
                $.getJSON("/golodeouro/inactives")
                    .done(data => {
                        data.forEach(inactive => this.inactives.push(inactive));
                    });
            }

        },
        computed: {
            loaded() {
                return Store.state.golodeouro.loaded;
            },
            visible() {
                return Store.state.golodeouro.visible;
            },
        },

        components: {
            'golodeouro': require('./golodeouro.vue')
        },
        mounted() {
            this.visible = !!this.collapsed;
            this.fetchfirstscorers();
            this.fetchresults();
            this.fetchtimes();
            this.fetchgolo();
            this.fetchvalues();
            this.fetchinactives();
        }
    }
</script>
