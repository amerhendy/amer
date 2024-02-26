<!--buttons.create.blade-->
<?php
if($Amer->hasAccess('create')){
	$createroute='<a href="';
	$createroute.= url($Amer->route.'/create');
	$createroute.= '" class="btn btn-primary" data-style="zoom-in">';
	$createroute.= '<span class="ladda-label">';
	$createroute.= '<i class="fa fa-plus"></i> ';
	$createroute.= trans('AMER::actions.add').' '.$Amer->entity_name;
	$createroute.= '</span>';
	$createroute.= '</a>';
			echo $createroute;
}
?>