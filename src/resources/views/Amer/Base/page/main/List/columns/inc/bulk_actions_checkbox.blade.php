@if (!isset($entry))
    <span class="Amer_bulk_actions_checkbox">
        <input type="checkbox" class="Amer_bulk_actions_general_checkbox control-input">
    </span>
@else
    <span class="Amer_bulk_actions_checkbox">
        <input type="checkbox" class="Amer_bulk_actions_line_checkbox  control-input" data-primary-key-value="{{ $entry->getKey() }}">
    </span>
@endif
