</div>
    <div class="card-footer">
        <div id="saveActions" class="form-group">
            <input type="hidden" name="_save_action" value="{{ $saveAction['active']['value'] }}">
            @if(!empty($saveAction['options']))
            <div class="btn-group" role="group">
            @endif
            <button type="submit" class="btn btn-success">
                <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
                <span data-value="{{ $saveAction['active']['value'] }}">{{ $saveAction['active']['label'] }}</span>
            </button>
            @if(!empty($saveAction['options']))
            <div class="btn-group" role="group">
                <button id="bpSaveButtonsGroup"  type="button" class="btn btn-success dropdown-toggle border-end" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="sr-only"><span class="fa fa-caret-down"></span></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="bpSaveButtonsGroup">
                @foreach( $saveAction['options'] as $value => $label)
                    <button type="button" class="dropdown-item btn btn-success " data-value="{{ $value }}">{{$label}}</button>
                @endforeach
                </div>
            </div>
            @endif
            @if(!$Amer->hasOperationSetting('showCancelButton') || $Amer->getOperationSetting('showCancelButton') == true)
            <a href="{{ $Amer->hasAccess('list') ? url($Amer->route) : url()->previous() }}" class="btn btn-danger"><span class="fa fa-ban"></span> &nbsp;{{ trans('AMER::actions.cancel') }}</a>
            @endif
            @if ($Amer->get('update.showDeleteButton') && $Amer->get('delete.configuration') && $Amer->hasAccess('delete'))
            <button onclick="confirmAndDeleteEntry()" type="button" class="btn btn-danger float-right"><i class="fa fa-trash-alt"></i> {{ trans('AMER::actions.delete') }}</button>
            @endif
            @if(!empty($saveAction['options']))
            </div>
            @endif
        </div>
    </div>
</form>
</div>
@push('after_scripts')
@if ($Amer->get('update.showDeleteButton') && $Amer->get('delete.configuration') && $Amer->hasAccess('delete'))
@loadScriptOnce('js/Amer/forms/confirmAndDeleteEntry.js')
<script>
    var confirmAndDeleteEntryRoute= '{{ url($Amer->route.'/'.$entry->getKey()) }}';
    var confirmAndDeleteEntryRedirect='{{ is_bool($Amer->get('update.showDeleteButton')) ? url($Amer->route) : (string) $Amer->get('update.showDeleteButton') }}';
</script>
@endif

@endpush
