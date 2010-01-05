<label for="extra">{$label|htmlentities}:</label><br/>
<select name="{$nomeCampo}[]" id="extra" multiple="multiple" size="5" class="campo">
    {html_options options=$select_extra selected=''}
</select>
