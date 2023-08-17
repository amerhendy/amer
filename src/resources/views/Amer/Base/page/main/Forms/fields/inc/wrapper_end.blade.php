<?php
$blockprefix=['month','date_picker','base64_image','table','upload','upload_multiple','uploadOrLink','video'];
if(!in_array($field['type'],$blockprefix)){
    if(isset($field['suffix'])){ echo'<span class="input-group-text" id="basic-addon2">'.$field['suffix'].'</span>';}
    if(isset($field['prefix']) || isset($field['suffix'])){echo'</div>';}
}
if (isset($field['hint'])){echo'<p class="form-text">'.$field['hint'].'</p>';}
?>
</{{ $field['wrapper']['element'] ?? 'div' }}>