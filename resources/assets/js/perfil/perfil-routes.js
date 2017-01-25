module.exports = {
    perfil: {
        name: 'PERFIL',
        link: '',
        size: 'small',
        sub: {
            info: {key: 'info', name: 'Info. pessoal', link: '', events: require('./sub/personal_info')},
            autenticacao: {key: 'autenticacao', name: 'Autenticação', link: '/autenticacao', events: require('./sub/autenticacao')},
            codigos: {key: 'codigos', name: 'Códigos Acesso', link: '/codigos', events: require('./sub/codigos_acesso')}
        }
    },
    banco: {
        name: 'BANCO',
        link: '/banco/saldo',
        size: 'small',
        sub: {
            saldo: {key: 'saldo', name: 'Saldo', link: '/banco/saldo'},
            depositar: {key: 'depositar', name: 'Depositar', link: '/banco/depositar', events: require('./sub/deposit')},
            'conta-pagamentos': {key: 'conta-pagamentos', name: 'Conta Pagamentos', link: '/banco/conta-pagamentos', events: require('./helpers/input-file')},
            levantar: {key: 'levantar', name: 'Levantar', link: '/banco/levantar'},
        }
    },
    bonus: {
        name: 'BÓNUS',
        link: '/bonus/porusar',
        size: 'small',
        sub: {
            porusar: {key: 'porusar', name: 'Por Utilizar', link: '/bonus/porusar'},
            activos: {key: 'activos', name: 'Em Utilização', link: '/bonus/activos'},
            utilizados: {key: 'utilizados', name: 'Utilizados', link: '/bonus/utilizados'},
            amigos: {key: 'amigos', name: 'Convidar Amigos', link: '/bonus/amigos'},
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
            mensagens: {key: 'mensagens', name: 'Mensagens', link: '/comunicacao/mensagens', events: require('./sub/messages')},
            definicoes: {key: 'definicoes', name: 'Definições', link: '/comunicacao/definicoes'},
            reclamacoes: {key: 'reclamacoes', name: 'Reclamações', link: '/comunicacao/reclamacoes'},
        }
    },
    'jogo-responsavel': {
        name: 'JOGO RESPONSÁVEL',
        link: '/jogo-responsavel/limites',
        size: 'big',
        sub: {
            limites: {key: 'limites', name: 'Limites', link: '/jogo-responsavel/limites'},
            autoexclusao: {key: 'autoexclusao', name: 'Auto-exclusão', link: '/jogo-responsavel/autoexclusao'},
            last_logins: {key: 'last_logins', name: 'Últimos Acessos', link: '/jogo-responsavel/last_logins'},
        }
    },
};