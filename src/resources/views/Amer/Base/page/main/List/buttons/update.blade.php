<!--buttons.update.blade-->
<?php
if(($Amer->hasAccess('update'))){
	$updatebtn='';
	if (!$Amer->model->translationEnabled()){
		$updatebtn.='<a href="';
		$updatebtn.=Route($Amer->routelist['edit']['as'],$entry->getKey());
		$updatebtn.='" class="btn btn-sm btn-primary" data-toggle="tooltip" title="'.trans('AMER::actions.edit').'"><i class="fa fa-edit"></i>';
		$updatebtn.='</a>';
	}else{
		$updatebtn.='<div class="btn-group">';
			$updatebtn.='<a href="';
			$updatebtn.=Route($Amer->routelist['edit']['as'],$entry->getKey());
			$updatebtn.='" class="btn btn-sm btn-link pr-0"><i class="fa fa-edit"></i> ';
				$updatebtn.=trans('AMER::actions.edit');
			$updatebtn.='</a>';
			$updatebtn.='<ul class="dropdown-menu dropdown-menu-right">';
				$updatebtn.='<li class="dropdown-header">';
					$updatebtn.=trans('AMER::actions.edit_translations');
				$updatebtn.=':</li>';
				foreach ($Amer->model->getAvailableLocales() as $key => $locale)
					{
						$updatebtn.='<a class="dropdown-item" href="'.Route($Amer->routelist['edit']['as'],$entry->getKey()).'?_locale='.$key.'">'.$locale.'</a>';
					}
			
			$updatebtn.='</ul>';
		$updatebtn.='</div>';
		
	}
	echo $updatebtn;
}
?>
