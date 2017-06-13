<div class="box error-placer no-error input-file" id="box-{{$field}}" data-autosubmit="{{isset($autoSubmit) && $autoSubmit ? 'true':'false'}}">
    <div class="box__input">
        <input class="box__file" type="file" name="{{$field}}" id="{{$field}}" accept=".gif, .jpg, .jpeg, .png, .pdf, .doc, .docx"/>
        <label for="{{$field}}">
            <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
            <span class="box-label"><strong>Clique para {!! $name !!}</strong><span class="box__dragndrop"><br>ou arraste e solte neste espaço</span></span>
        </label>
        <button class="box__button" type="submit">Enviar</button>
    </div>
    <div class="box__uploading">Uploading&hellip;</div>
    <div class="box__success">Concluído!</div>
    <div class="box__error place"><span class="error">Erro!.</span></div>
</div>