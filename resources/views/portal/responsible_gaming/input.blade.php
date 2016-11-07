<?php
    $curr = \App\UserLimit::GetCurrLimitFor($key);
    $last = \App\UserLimit::GetLastLimitFor($key);
    $currVal = $curr != null ? $curr->limit_value : null;
    $value = $last != null ? $last->limit_value : $currVal;
    $showAlert = $last != null && $last->implement_at != null && $last->implement_at->isFuture();
?>
    <div class="grupo">
        <div>
        <div  id="label_{{ $typeId }}" class="grupo-title {{ !$value ? 'disabled' : ''}}" style="float: left; width: 40%">{{$label}}</div>
        <div style="float: left; width: 20%; margin-bottom:5px;">
            <input id="limit-{{ $typeId }}" name="limit-{{ $typeId }}" type="checkbox" class="settings-switch"
            value="limit-{{ $typeId }}" {{$value ? 'checked="checked"' : ''}}>
            <label  for="limit-{{ $typeId }}" title="Sem Limite"></label>
        </div>
        <div style="float: left; width: 40%;line-height:0px;">
            <input type="text" style="width:90%"  name="limit_{{ $typeId }}" id="limit_{{ $typeId }}"
            value="{{$value or 'Ilimitado'}}" {{ !$value ? 'disabled=disabled' : ''}} class="{{ !$value ? 'disabled' : ''}}"/>
            <span class="has-error error" style="display:none;"> </span>
        </div>

        </div>

    </div>

<script>
    $(function(){
        var lb = $('#label_{{ $typeId }}');
        var cb = $('#limit-{{ $typeId }}');
        var tb = $('#limit_{{ $typeId }}');
        var tbb = tb.next('.input-group-addon');
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
                tbb.hide();
            } else {
                lb.removeClass('disabled');
                tb.val(prevValue).removeAttr('disabled');
                tb.removeClass('disabled');
                tb.rules('add', {
                    number: true,
                    required: true,
                    min: 0
                });
                tbb.removeClass('hidden').show();
            }
        });
    });
</script>