<div class="col-xs-9 micro-mtop registo-form">
    <label>{{ $label }}
        <div class="limit-check">
            <input id="limit-{{ $typeId }}" name="limit-{{ $typeId }}" type="checkbox" class="settings-switch"
                   value="limit-{{ $typeId }}" {{$value ? 'checked="checked"' : ''}}>
            <label for="limit-{{ $typeId }}" title="Sem Limite"></label>
        </div>
    </label>
    <input class="col-xs-10 input-group-left" type="text" name="limit_{{ $typeId }}" id="limit_{{ $typeId }}"
           value="{{$value or 'Sem limite definido.'}}" {{ !$value ? 'disabled=disabled' : ''}}
    /><div  class="input-group-label-right {{ !$value ? 'hidden' : '' }}"> €</div>

    <span class="has-error error" style="display:none;"> </span>
</div>
<script>
    $(function(){
        var cb = $('#limit-{{ $typeId }}');
        var tb = $('#limit_{{ $typeId }}');
        var tbb = tb.next('div');
        var prevValue = !cb.is(':checked') ? '0.00' : tb.val();

        cb.on('change', function changeCheckBox(){
           var noLimit = !cb.is(':checked');
            if (noLimit) {
                prevValue = tb.val();
                tb.val('Sem limite definido.').attr('disabled', 'disabled');
                tb.rules('remove', 'number required');
                tb.siblings('.success-color').remove();
                tb.siblings('.warning-color').remove();
                tb.valid();
                tbb.hide();
            } else {
                tb.val(prevValue).removeAttr('disabled');
                tb.rules('add', {
                    number: true,
                    required: true
                });
                tbb.removeClass('hidden').show();
            }
        });
    });
</script>