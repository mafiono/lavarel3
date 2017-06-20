<?php
    $curr = \App\UserLimit::GetCurrLimitFor($key);
    $last = \App\UserLimit::GetLastLimitFor($key);
    $currVal = $curr != null ? $curr->limit_value : null;
    $value = $last != null ? $last->limit_value : $currVal;
    $showAlert = $last != null && $last->implement_at != null && $last->implement_at->isFuture();
    $currFormated = number_format($currVal, 0, ',', ' ');
    if ($showAlert) {
        $limite = $value? number_format($value, 0, ',', ' '):'sem limite';
        $tempo = min($last->implement_at->diffInHours() + 1, 24);
        $msg = "* O limit atual é de $currFormated, mudará para $limite em $tempo h.";
    } else {
        $msg = ($currVal ? "* O limite atual é de $currFormated" : '* Não tem limite atual') . '.';
    }
?>
<div class="row grupo error-placer {{$value ? 'active' : ''}}" id="grp-{{$typeId}}">
    <div class="col-xs-5 col-sm-6">
        <div id="label_{{ $typeId }}" class="grupo-title {{ !$value ? 'disabled' : ''}}">
            <span>{{$label}}</span>
            <input id="limit-{{ $typeId }}" name="limit-{{ $typeId }}" type="checkbox" class="settings-switch"
                   value="limit-{{ $typeId }}" {{$value ? 'checked="checked"' : ''}}>
            <label for="limit-{{ $typeId }}" title="Sem Limite"></label>
        </div>
    </div>
    <div class="col-xs-4 col-sm-6">
        <input type="text" name="limit_{{ $typeId }}" id="limit_{{ $typeId }}"
               value="{{($value ? number_format($value, 0, ',', ' ') : 'Ilimitado')}}"
               {{ !$value ? 'disabled=disabled' : ''}} class="number {{ !$value ? 'disabled' : ''}}"/>
    </div>
    @if (isset($final))
        <div class="col-xs-3 col-sm-12">
            <input type="submit" value="{{$final}}">
        </div>
    @endif
    <div class="col-xs-12">
        <span class="has-error error place" style="display:none;"> </span>
    </div>
    <div class="col-xs-12">
        <p class="warning-msg">{{$msg}}</p>
    </div>
</div>
<script>
    $(function(){
        var cb = $('#limit-{{ $typeId }}'),
            lb = $('#label_{{ $typeId }}').click(function (ev) {
                if (ev.target.tagName !== 'LABEL') {
                    cb.trigger('click');
                }
            }),
            tb = $('#limit_{{ $typeId }}'),
            grp = $('#grp-{{$typeId}}'),
            prevValue = !cb.is(':checked') ? '0' : tb.val();

        cb.on('change', function changeCheckBox(){
            var noLimit = !cb.is(':checked');
            grp.toggleClass('active', !noLimit);
            if (noLimit) {
                lb.addClass('disabled');
                prevValue = tb.val();
                tb.toggleClass('disabled');
                tb.attr('disabled', 'disabled').removeAttr('min').val('Ilimitado');
                tb.rules('remove', 'number required min');
                tb.siblings('.success-color').remove();
                tb.siblings('.warning-color').remove();
                tb.valid();
            } else {
                lb.removeClass('disabled');
                tb.removeAttr('disabled').attr('min', '0').val(prevValue);
                tb.removeClass('disabled');
                tb.rules('add', {
                    number: true,
                    required: true,
                    min: 0
                });
                tb.valid();
            }
        });
        setTimeout(function () {
            $('.warning-msg').slideUp('slow');
        }, 5000);
    });
</script>