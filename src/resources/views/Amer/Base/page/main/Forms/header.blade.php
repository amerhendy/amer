    <div class="row">
    <!-- formFailedScript -->
        <?php
        $enc='';
        if($Amer->getcurrentOperation() == 'create'){
            $targetroute=url($Amer->route);
            if ($Amer->hasUploadFields('create')){$enc='enctype="multipart/form-data"';}
        }elseif($Amer->getcurrentOperation() == 'update'){
            $targetroute=url($Amer->route.'/'.$entry->getKey());
            if($Amer->hasUploadFields('update', $entry->getKey())){$enc='enctype="multipart/form-data"';}
        }
        ?>
        <form class="form" name="form" action="{{ $targetroute }}" method="post" {{$enc}}>
                {!! csrf_field() !!}
                @if($Amer->getcurrentOperation() == 'update')
                {!! method_field('PUT') !!}
                @endif
                <input type="hidden" name="_http_referrer" value="{{session('referrer_url_override') ?? old('_http_referrer') ?? \URL::previous() ?? Route($Amer->route.'.index')}}">

        <div class="col-lg-8">
        <div class="card padding-10">
            <div class="card-header">
                <h2>
                    <small>{!! $Amer->getSubheading() ?? trans('AMER::actions.'.$Amer->getcurrentOperation()).' '.$Amer->entity_name !!}.</small>
                </h2>
            </div>
                    <div class="card-body bold-labels">
                        @if (Alert::count())
                        @foreach (Alert::getMessages() as $type => $messages)
                        <?php
                        if(is_array($messages)){
                            $messages=implode('<br>',$messages);
                        }
                        \Amerhendy\Amer\App\Helpers\Widget::add([
                            'type'        => 'alert',
                            'class'			=>$type, //success, info, warning, danger, primary, secondary, light, dark
                            'close_button'=>true,
                            'content'     => $messages,
                        ]);
            ?>
                        @endforeach
                        @endif
@push('after_scripts')

@endpush
