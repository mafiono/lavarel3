<template>
    <div class="info" style="margin-top: 0; width: 640px">
        <div class="header">SUPORTE
            <i @click="close()" class="cp-cross"></i>
            <i @click="print()" id="info-print" class="cp-calendar-o"></i>
        </div>
        <div class="links">
            <div class="links-content">
                <a id="info-sobre_nos" href="#" class="link" :class="selectedClass('sobre_nos')" @click.prevent="select('sobre_nos')">Sobre Nós <i :class="iconClass('sobre_nos')"></i></a>
                <a id="info-termos_e_condicoes" href="#" class="link" :class="selectedClass('termos_e_condicoes')" @click.prevent="select('termos_e_condicoes')">Termos e Condições <i :class="iconClass('termos_e_condicoes')"></i></a>
                <a id="info-politica_privacidade" href="#" class="link" :class="selectedClass('politica_privacidade')" @click.prevent="select('politica_privacidade')">Politica de Privacidade <i :class="iconClass('politica_privacidade')"></i></a>
                <a id="info-faq" href="#" class="link last" :class="selectedClass('faq')" @click.prevent="select('faq')">FAQ <i :class="iconClass('faq')"></i></a>
                <a id="info-bonus_e_promocoes" href="#" class="link" :class="selectedClass('bonus_e_promocoes')" @click.prevent="select('bonus_e_promocoes')">Bónus e Promoções <i :class="iconClass('bonus_e_promocoes')"></i></a>
                <a id="info-pagamentos" href="#" class="link" :class="selectedClass('pagamentos')" @click.prevent="select('pagamentos')">Pagamentos <i :class="iconClass('pagamentos')"></i></a>
                <a id="info-jogo_responsavel" href="#" class="link" :class="selectedClass('jogo_responsavel')" @click.prevent="select('jogo_responsavel')">Jogo Responsável <i :class="iconClass('jogo_responsavel')"></i></a>
                <a id="info-contactos" href="#" class="link last" :class="selectedClass('contactos')" @click.prevent="select('contactos')">Contactos <i :class="iconClass('contactos')"></i></a>
            </div>
        </div>
        <div id="info-content" class="content" v-html="content"></div>
    </div>
</template>
<script>
    export default {
        data: function() {
            return this.$root.$data.info;
        },
        methods: {
            select: function(term) {
                this.selected = term;

                this.$router.push({name: 'info', params: {term: term}});

                $.getJSON(this.routes[term])
                    .done(function(content) {
                        this.content = content.legalDoc;
                    }.bind(this));
            },
            selectedClass: function(term) {
                return this.selected === term ? "selected" : "";
            },
            iconClass: function(term) {
                return this.selected === term ? "cp-caret-down" : "cp-plus";
            },
            close: function() {
                this.$router.push('/');
            },
            print: function() {
                $("#info-content").print({
                    addGlobalStyles : false,
                    stylesheet : null,
                    rejectWindow : true,
                    noPrintSelector : ".no-print",
                    iframe : true,
                    append : null,
                    prepend : null
                });
            }
        },
        mounted: function() {
            this.selected = this.$route.params.term || this.selected;

            this.select(this.selected);
        }
    }
</script>
