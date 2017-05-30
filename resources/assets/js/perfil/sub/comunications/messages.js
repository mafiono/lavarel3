import {Observable} from 'rxjs/Observable';
import {Subject} from 'rxjs/Subject';
import 'rxjs/add/observable/timer';
import 'rxjs/add/operator/switchMap';
import 'rxjs/add/operator/takeUntil';
import 'rxjs/observable/fromPromise';

var auth = require('../../helpers/input-file');

let active$ = new Subject();
module.exports.load = function(){
    active$.next(true);
    auth.load();
    $('#messages-container').slimScroll({
        //width: '600px',
        height: '330px',
        start: 'bottom',
        allowPageScroll: false
    });

    var scrollOnNext = true;
    var items = 0;

    var $newmessage = $("#newmessage");
    $newmessage.validate({
        beforeSubmit: false,
        customProcessStatus: function (status, response) {
            scrollOnNext = true;
            $newmessage.find('#messagebody').val('').focus();
            // use the default logic
            return false;
        }
    });

    var $newupload = $("#newupload");
    $newupload.validate({
        beforeSubmit: function beforeSubmit(form) {
            $.fn.popup({
                title: 'Aguarde por favor!',
                type: 'warning',
                text: '<div class="bs-wp"><div class="progress">\
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">\
                        <span class="sr-only">0% Completo</span>\
                    </div>\
                </div></div>',
                html: true,
                showCancelButton: false,
                showConfirmButton: false
            });
        },
        customProcessStatus: function (status, response) {
            scrollOnNext = true;
            $newupload.find('#image').val('');
            $newupload.find('.box label')
                .html('<strong>Clique para seleccionar arquivo</strong><span class="box__dragndrop"><br>ou arraste e solte neste espaço</span>');

            $newupload.find('#messagebody').focus();
            // use the default logic
            return false;
        }
    });

    Observable.timer(0, 5000)
        .takeUntil(active$)
        .switchMap(() => {
            // console.log('Requesting /ajax-perfil/perfil/mensagens/chat');
            return Observable.fromPromise($.get('/ajax-perfil/perfil/mensagens/chat').promise());
        })
        .subscribe(data => {
            if (typeof data === 'string') {
                data = JSON.parse(data);
            }
            if (items !== data.length){
                items = data.length;
                scrollOnNext = true;
                $('.messages-total').text(items);
            }
            var html = Template.apply('messages_details', data);

            if (scrollOnNext) {
                scrollOnNext = false;
                $("#messages-container").html(html);
                $('#messages-container').slimScroll({ scrollTo: '9999' });
            }
        })
    ;
    Observable
        .timer(3000)
        .takeUntil(active$)
        .switchMap(() => {
            // console.log('Requesting /perfil/mensagens/read');
            return Observable.fromPromise($.post('/perfil/mensagens/read').promise());
        })
        .subscribe(x => {
            console.log('Set all messages readed', x);
            $('.messages-count').text('');
        })
    ;
};
module.exports.unload = function () {
    active$.next(false);
    auth.unload();
};