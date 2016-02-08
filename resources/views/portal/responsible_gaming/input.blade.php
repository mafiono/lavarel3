<div class="col-xs-9 micro-mtop registo-form">
    <label>{{ $label }}
        <div class="limit-check">
            <input id="no-limit-{{ $typeId }}" name="no-limit-{{ $typeId }}" type="checkbox" class="settings-switch"
                   value="no-limit-{{ $typeId }}" {{!$value ? 'checked="checked"' : ''}}>
            <label for="no-limit-{{ $typeId }}" title="Sem Limite"></label>
        </div>
    </label>
    <input class="col-xs-10" type="text" name="limit_{{ $typeId }}" id="limit_{{ $typeId }}"
           value="{{$value or 'Sem Limites'}}"
    /><b> €</b>

    <span class="has-error error" style="display:none;"> </span>
</div>
<script>
    $(function(){
        var cb = $('#no-limit-{{ $typeId }}');
        var tb = $('#limit_{{ $typeId }}');
        var tbb = tb.next('b');
        var prevValue = cb.is(':checked') ? '0.00' : tb.val();

        cb.on('change', function changeCheckBox(){
           var noLimit = cb.is(':checked');
            if (noLimit) {
                prevValue = tb.val();
                tb.val('Sem limite definido.').attr('disabled', 'disabled');
                tbb.hide();
            } else {
                tb.val(prevValue).removeAttr('disabled');
                tbb.show();
            }
        });
    });
</script>