<label for="categoria_extra">{$label|htmlentities}:</label><br/>
<select name="{$nomeCampo}[]" id="categoria_extra" multiple="multiple" size="5" class="campo">
    {html_options options=$select_extra selected=''}
</select>
