<div class="col-xs-{{isset($cols)?$cols:'6'}}">
    <div class="grupo {{!empty($value) && $value == 1 ? 'active' : ''}}" id="grp-{{$field}}">
        <div class="grupo-title">
            {{$fieldName}}
        </div>
        <div style="float: right;">
            <?php !empty($value) && $value == 1 ? $checked = 'checked' : $checked = '';?>
            <input id="{{$field}}" class="settings-switch" name="{{$field}}" type="checkbox" {{$checked}}>
            <label for="{{$field}}" onclick="$('#grp-{{$field}}').toggleClass('active', !$('#{{$field}}').is(':checked'));"></label>
            <span class="has-error error" style="display:none;"> </span>
        </div>
        <div class="clear"></div>
    </div>
</div>