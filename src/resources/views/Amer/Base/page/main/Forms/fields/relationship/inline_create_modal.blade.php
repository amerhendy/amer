<!--inline_create_modal.blade -->
@php
    $loadedFields = json_decode($parentLoadedFields);
    foreach($loadedFields as $loadedField) {
        $Amer->markFieldTypeAsLoaded($loadedField);
    }
@endphp
<div class="container-fluid bg-secondary-subtle border border-secondary rounded" id="inline-create-dialog">
            <form method="post"
            id="{{$entity}}-inline-create-form"
            action="#"
            onsubmit="return false"
          @if ($Amer->hasUploadFields('create'))
          enctype="multipart/form-data"
          @endif
            >
        {!! csrf_field() !!}
            @include(fieldview('relationship.form_content'), [ 'fields' => $fields, 'action' => $action])
    </form>
        <button type="button" class="btn btn-secondary" id="cancelButton">{{trans('AMER::actions.cancel')}}</button>
          <button type="button" class="btn btn-primary" id="saveButton">{{trans('AMER::actions.save')}}</button>
</div>
<!--inline_create_modal.blade -->
