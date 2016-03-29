<?php
    $curr = \App\UserLimit::GetCurrLimitFor($key);
    $last = \App\UserLimit::GetLastLimitFor($key);
    $currVal = $curr != null ? $curr->limit_value : null;
    $value = $last != null ? $last->limit_value : $currVal;
    $showAlert = $last != null && $last->implement_at != null && $last->implement_at->isFuture();
?>
<div class="col-xs-7 registo-form">
    <label>{{ $label }}
        <div class="limit-check">
            <input id="limit-{{ $typeId }}" name="limit-{{ $typeId }}" type="checkbox" class="settings-switch"
                   value="limit-{{ $typeId }}" {{$value ? 'checked="checked"' : ''}}>
            <label for="limit-{{ $typeId }}" title="Sem Limite"></label>
        </div>
    </label>
    <div class="input-group">
        <input type="text" class="form-control" name="limit_{{ $typeId }}" id="limit_{{ $typeId }}"
               value="{{$value or 'Sem limite definido.'}}" {{ !$value ? 'disabled=disabled' : ''}}/>
        <span class="input-group-addon {{ !$value ? 'hidden' : '' }}"> €</span>
    </div>
    <span class="has-error error" style="display:none;"> </span>
</div>
@if($showAlert)
<p class="alert-info">Nota: O {{ $label }} actual é de {{$currVal}} e passará para {{$value?:'sem limite'}} daqui a {{$last->implement_at->diffInHours() + 1}} hora(s).</p>
@endif
<script>
    $(function(){
        var cb = $('#limit-{{ $typeId }}');
        var tb = $('#limit_{{ $typeId }}');
        var tbb = tb.next('.input-group-addon');
        var prevValue = !cb.is(':checked') ? '0.00' : tb.val();

        cb.on('change', function changeCheckBox(){
           var noLimit = !cb.is(':checked');
            if (noLimit) {
                prevValue = tb.val();
                tb.val('Sem limite definido.').attr('disabled', 'disabled');
                tb.rules('remove', 'number required min');
                tb.siblings('.success-color').remove();
                tb.siblings('.warning-color').remove();
                tb.valid();
                tbb.hide();
            } else {
                tb.val(prevValue).removeAttr('disabled');
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