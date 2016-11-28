<?php
    $curr = \App\UserLimit::GetCurrLimitFor($key);
    $last = \App\UserLimit::GetLastLimitFor($key);
    $currVal = $curr != null ? $curr->limit_value : null;
    $value = $last != null ? $last->limit_value : $currVal;
    $showAlert = $last != null && $last->implement_at != null && $last->implement_at->isFuture();
    if ($showAlert) {
        $limite = $value?:'sem limite';
        $tempo = min($last->implement_at->diffInHours() + 1, 24);
        $app->make('warningText')->$typeId = "* O atual " . mb_strtolower($label) . " é de $currVal, mudará para $limite em $tempo h.";
    }
?>
<div class="row grupo">
    <div class="col-xs-5">
        <div id="label_{{ $typeId }}" class="grupo-title {{ !$value ? 'disabled' : ''}}">{{$label}}
            <input id="limit-{{ $typeId }}" name="limit-{{ $typeId }}" type="checkbox" class="settings-switch"
                   value="limit-{{ $typeId }}" {{$value ? 'checked="checked"' : ''}}>
            <label  for="limit-{{ $typeId }}" title="Sem Limite"></label>
        </div>
    </div>
    <div class="col-xs-4">
        <input type="text" name="limit_{{ $typeId }}" id="limit_{{ $typeId }}"
               value="{{$value or 'Ilimitado'}}" {{ !$value ? 'disabled=disabled' : ''}} class="{{ !$value ? 'disabled' : ''}}"/>
        <span class="has-error error" style="display:none;"> </span>
    </div>
    @if (isset($final))
        <div class="col-xs-3">
            <input type="submit" value="{{$final}}">
        </div>
    @endif
</div>
<script>
    $(function(){
        var lb = $('#label_{{ $typeId }}');
        var cb = $('#limit-{{ $typeId }}');
        var tb = $('#limit_{{ $typeId }}');
        var prevValue = !cb.is(':checked') ? '0.00' : tb.val();

        cb.on('change', function changeCheckBox(){
           var noLimit = !cb.is(':checked');
            if (noLimit) {
                lb.addClass('disabled');
                prevValue = tb.val();
                tb.toggleClass('disabled');
                tb.val('Ilimitado').attr('disabled', 'disabled');
                tb.rules('remove', 'number required min');
                tb.siblings('.success-color').remove();
                tb.siblings('.warning-color').remove();
                tb.valid();
            } else {
                lb.removeClass('disabled');
                tb.val(prevValue).removeAttr('disabled');
                tb.removeClass('disabled');
                tb.rules('add', {
                    number: true,
                    required: true,
                    min: 0
                });
            }
        });
    });
</script>