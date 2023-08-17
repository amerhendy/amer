<?php
if($field['type'] == 'newuser' || $field['type'] == 'newrole'){
	return;
}
$blockprefix=['month','date_picker','base64_image','checklist','table','upload','upload_multiple','uploadOrLink','video','newuser'];
$data_field_name=['table','image','base64_image','upload','upload_multiple','uploadOrLink'];
$initfunctions=[
	'video'=>'bpFieldInitVideoElement',
	'uploadOrLink'=>'bpFieldInitUploadMultipleElement',
	'upload'=>'bpFieldInitUploadElement',
	'upload_multiple'=>'bpFieldInitUploadMultipleElement',
	'checklist_dependency'=>'bpFieldInitChecklistDependencyElement',
	'image'=>'bpFieldInitCropperImageElement',
	'checklist'=>'bpFieldInitChecklist',
	'base64_image'=>'bpFieldInitBase64CropperImageElement',
	'browse_multiple'=>'bpFieldInitBrowseMultipleElement',
];
$field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
$field['wrapper']['class'] = $field['wrapper']['class'] ?? 'form-group col-sm-12';

if(in_array($field['type'],$data_field_name)){
	$field['wrapper']['data-field-name'] = $field['wrapper']['data-field-name'] ?? $field['name'];
}
if(array_key_exists($field['type'],$initfunctions)){
	$field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? $initfunctions[$field['type']];
}
if($field['type']=='checklist_dependency'){
	$field['wrapper']['class'] = $field['wrapper']['class'].' checklist_dependency';
	$field['wrapper']['data-entity'] = $field['wrapper']['data-entity'] ?? $field['field_unique_name'];
}
if($field['type']=='table'){
	$field['wrapper']['data-field-type'] = 'table';
}
if($field['type']=='video'){
	$field['wrapper']['data-youtube-api-key'] = $field['youtube_api_key'] ?? config('amer.base.youtube_key')??'AIzaSyBLRoVYovRmbIf_BH3X12IcTCudAEDRlCE';
	$field['wrapper']['data-video'] = '';
}
foreach($field['wrapper'] as $attributeKey => $value) {
	$field['wrapper'][$attributeKey] = !is_string($value) && is_callable($value) ? $value($Amer, $field, $entry ?? null) : $value ?? '';
}
$required='';
if(isset($action) && $Amer->isRequired($field['name'], $action)){
	$required=' required';
}elseif(isset($field['showAsterisk']))
{
	$required = isset($field['showAsterisk']) ? ($field['showAsterisk'] ? ' required' : '') : $required;
}

	if($field['type'] == 'image'){
		$field['wrapper']['class'] = $field['wrapper']['class'].' cropperImage';
		$field['wrapper']['data-aspectRatio'] = $field['aspect_ratio'] ?? 0;
		$field['wrapper']['data-crop'] = $field['crop'] ?? false;
		
	}
	if($field['type'] == 'date_picker'){
		$field['attributes']['style'] = $field['attributes']['style'] ?? 'background-color: white!important;';
    	$field['attributes']['readonly'] = $field['attributes']['readonly'] ?? 'readonly';
	}
	
	if($field['type'] == 'base64_image'){
		$field['wrapper']['class'] = $field['wrapper']['class'].' cropperImage';
		$field['wrapper']['data-aspectRatio'] = $field['aspect_ratio'] ?? 0;
		$field['wrapper']['data-crop'] = $field['crop'] ?? false;
		
	}
	if($field['type'] == 'browse_multiple'){
		$multiple = Arr::get($field, 'multiple', true);
		$sortable = Arr::get($field, 'sortable', false);
		$value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';

if (!$multiple && is_array($value)) {
    $value = Arr::first($value);
}


$field['wrapper']['data-elfinder-trigger-url'] = $field['wrapper']['data-elfinder-trigger-url'] ?? url(config('elfinder.route.prefix').'/popup/'.$field['name'].'?multiple=1');
$wantedtrigers=['mime_types','rememberLastDir','useBrowserHistory','onlyMimes','clientFormatDate','UTCDate','disk','path'];
$tr='';
forEach($field as $a=>$b){    
    if(in_array($a,$wantedtrigers)){
        if(is_bool($b)){
            if($b == true || $b == "true" || $b == 1){$b="true";}
            if($b == false || $b == "false" || $b == 0){$b="false";}
            $tr.="&".$a.'='.$b;
        }else{
            $tr.="&".$a.'='.urlencode(serialize($b));
        }
        
    }   
}
$field['wrapper']['data-elfinder-trigger-url'].=$tr;
if($sortable){
    $field['wrapper']['sortable'] = "true";
}
if ($multiple) {
    if($multiple == true || $multiple == "true" || $multiple == 1){$field['wrapper']['data-multiple'] = "true";}else{$field['wrapper']['data-multiple'] = "false";}
} else {
    $field['wrapper']['data-multiple'] = "false";
}
	}
	if(!is_array($field['name'])){
		$field['wrapper']['id'] = $field['wrapper']['id'] ?? $field['name']."_mainDiv";
	}else{
		$field['wrapper']['id'] = $field['wrapper']['id'] ?? implode('_',$field['name'])."_mainDiv";
	}
	
	$field['wrapper']['for'] =$field['name'];
	$field['wrapper']['class'] = $field['wrapper']['class'].$required;
	$field['wrapper']['element'] = $field['wrapper']['element'] ?? 'div';
	$translatable = false;
	if($Amer->model->translationEnabled()) {
		foreach((array) $field['name'] as $field_name){
			if($Amer->model->isTranslatableAttribute($field_name)) {$translatable = true;}
		}
	}
	if(isset($field['store_in']) && $Amer->model->isTranslatableAttribute($field['store_in'])) {$translatable = true;}
	echo '<'.$field['wrapper']['element'];
	foreach ($field['wrapper'] as $attribute => $value) {
		echo ' '.$attribute.'=';
		if(is_array($value)){
			echo json_encode($value);
		}else{
			echo '"'.$value.'" ';
		}
	}
	echo '>';
	?>
@if(!in_array($field['type'],['boolean','checkbox']))
	@if($field['type'] == 'video')
	<label for="{{ $field['name'] }}_link" class="form-label">{!! $field['label'] !!}</label>
	@else
		@if(!is_array($field['name']))
		<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
		@else
		<label for="{{ implode('_',$field['name']) }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
		@endif
	
	@endif
	
	@if ($translatable && config('Amer.Base.show_translatable_field_icon'))
		<i class="fa fa-language pull-{{ config('Amer.Base.translatable_field_icon_position') ?? 'left' }}" style="margin-top: 3px;" title="This field is translatable."></i>
	@endif
@endif
<?php
if(!in_array($field['type'],$blockprefix)){
	if(isset($field['prefix']) || isset($field['suffix'])){echo'<div class="input-group mb-3">';}
	if(isset($field['prefix'])){ echo'<span class="input-group-text" id="basic-addon2">'.$field['prefix'].'</span>';}
}
?>