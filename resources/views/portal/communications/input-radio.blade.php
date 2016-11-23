<div class="col-xs-{{isset($cols)?$cols:'6'}}">
    <div class="grupo">
        <div class="grupo-title">
            {{$fieldName}}
        </div>
        <div style="float: right;">
            <?php !empty($value) && $value == 1 ? $checked = 'checked' : $checked = '';?>
            <input id="{{$field}}" class="settings-switch" name="{{$field}}" type="checkbox" {{$checked}}>
            <label for="{{$field}}"></label>
            <span class="has-error error" style="display:none;"> </span>
        </div>
        <div class="clear"></div>
    </div>
</div>