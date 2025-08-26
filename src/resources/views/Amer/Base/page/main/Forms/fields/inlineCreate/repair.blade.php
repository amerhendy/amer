<?php
    if($activeInlineCreate) {
        if(!isset($inlineCreate)) {
            $inlineCreateObj=new \stdClass;
            $inlineCreateObj->force_select=$field['inline_create']['force_select'] ?? true;
            $inlineCreateObj->modal_class=$field['inline_create']['modal_class'] ?? 'modal-dialog';
            $entityWithoutAttribute = $Amer->getOnlyRelationEntity($field);
            $routeEntity =$entityWithoutAttribute;
            $inlineCreateObj->entity=$field['inline_create']['entity'] ?? $routeEntity;
            $inlineCreateObj->create_route=$field['inline_create']['create_route'] ?? route($inlineCreateObj->entity."-inline-create-save");
            $inlineCreateObj->modal_route=$field['inline_create']['modal_route'] ?? route($inlineCreateObj->entity."-inline-create");
            $inlineCreateObj->include_main_form_fields=$field['inline_create']['include_main_form_fields'] ?? false;
            if(!is_bool($inlineCreateObj->include_main_form_fields)) {
                if(is_array($inlineCreateObj->include_main_form_fields)) {
                    $inlineCreateObj->include_main_form_fields = json_encode($inlineCreateObj->include_main_form_fields);
                }else{
                    $arrayed_field = array($inlineCreateObj->include_main_form_fields);
                    $inlineCreateObj->include_main_form_fields = json_encode($arrayed_field);
                }
            }
        }
    }
?>
