<!-- form_content.blade -->
<input type="hidden" name="http_referrer" value={{ old('http_referrer') ?? \URL::previous() ?? url($Amer->route) }}>
{{-- See if we're using tabs --}}
@if ($Amer->tabsEnabled() && count($Amer->getTabs()))
    @include(fieldview('relationship.show_tabbed_fields'))
    <input type="hidden" name="current_tab" value="{{ Str::slug($Amer->getTabs()[0], "") }}" />
@else
      @include(fieldview('relationship.show_fields'), ['fields' => $Amer->fields()])
@endif
<!-- form_content.blade -->
