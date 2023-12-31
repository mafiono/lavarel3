module.exports = {
    perfil: {
        name: 'PERFIL',
        link: '',
        size: 'small',
        sub: {
            info: {key: 'info', name: 'Info. pessoal', link: '', events: require('./sub/personal_info')},
            autenticacao: {key: 'autenticacao', name: 'Documentos', link: '/autenticacao', events: require('./sub/autenticacao')},
            codigos: {key: 'codigos', name: 'Códigos Acesso', link: '/codigos', events: require('./sub/codigos_acesso')}
        }
    },
    banco: {
        name: 'BANCO',
        link: '/banco/saldo',
        size: 'small',
        sub: {
            saldo: {key: 'saldo', name: 'Saldo', link: '/banco/saldo', events:  require('./sub/bank/balance')},
            depositar: {key: 'depositar', name: 'Depositar', link: '/banco/depositar', events: require('./sub/bank/deposit')},
            'conta-pagamentos': {key: 'conta-pagamentos', name: 'Conta Pagamentos', link: '/banco/conta-pagamentos', events: require('./sub/bank/accounts')},
            levantar: {key: 'levantar', name: 'Levantar', link: '/banco/levantar', events: require('./sub/bank/withdraw')},
        }
    },
    historico: {
        name: 'HISTÓRICO',
        link: '/historico',
        size: 'small'
    },
    comunicacao: {
        name: 'COMUNICAÇÃO',
        link: '/comunicacao/mensagens',
        size: 'big',
        sub: {
            mensagens: {key: 'mensagens', name: 'Mensagens', link: '/comunicacao/mensagens', events: require('./sub/comunications/messages')},
            definicoes: {key: 'definicoes', name: 'Definições', link: '/comunicacao/definicoes', events: require('./sub/comunications/definicaos')},
            reclamacoes: {key: 'reclamacoes', name: 'Reclamações', link: '/comunicacao/reclamacoes', events: require('./sub/comunications/reclamacao')},
        }
    },
    'jogo-responsavel': {
        name: 'JOGO RESPONSÁVEL',
        link: '/jogo-responsavel/limites',
        size: 'big',
        sub: {
            limites: {key: 'limites', name: 'Limites', link: '/jogo-responsavel/limites', events: require('./sub/resp-gaming/limits')},
            autoexclusao: {key: 'autoexclusao', name: 'Autoexclusão', link: '/jogo-responsavel/autoexclusao', events: require('./sub/resp-gaming/self-exclusion')},
            last_logins: {key: 'last_logins', name: 'Últimos Acessos', link: '/jogo-responsavel/last_logins', events: require('./sub/resp-gaming/last_logins')},
        }
    },
};