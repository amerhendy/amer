@php
    $horizontalTabs = $Amer->getTabsType()=='horizontal' ? true : false;

    if ($errors->any() && array_key_exists(array_keys($errors->messages())[0], $Amer->getCurrentFields()) &&
        array_key_exists('tab', $Amer->getCurrentFields()[array_keys($errors->messages())[0]])) {
        $tabWithError = ($Amer->getCurrentFields()[array_keys($errors->messages())[0]]['tab']);
    }
@endphp

@push('Amer_fields_styles')
    <style>
        .nav-tabs-custom {
            box-shadow: none;
        }
        .nav-tabs-custom > .nav-tabs.nav-stacked > li {
            margin-right: 0;
        }

        .tab-pane .form-group h1:first-child,
        .tab-pane .form-group h2:first-child,
        .tab-pane .form-group h3:first-child {
            margin-top: 0;
        }
    </style>
@endpush
@if ($Amer->getFieldsWithoutATab()->filter(function ($value, $key) { return $value['type'] != 'hidden'; })->count())
<div class="card">
    <div class="card-body row">
    @include(fieldview('inc.show_fields'), ['fields' => $Amer->getFieldsWithoutATab()])
    </div>
</div>
@else
    @include(fieldview('inc.show_fields'), ['fields' => $Amer->getFieldsWithoutATab()])
@endif

<div class="tab-container {{ $horizontalTabs ? '' : 'container'}} mb-2">
    <div class="nav-tabs-custom {{ $horizontalTabs ? '' : 'row'}}" id="form_tabs">
        <ul class="nav {{ $horizontalTabs ? 'nav-tabs' : 'flex-column nav-pills'}} {{ $horizontalTabs ? '' : 'col-md-3' }} mb-3" id="ex1" role="tablist">
            @foreach ($Amer->getTabs() as $k => $tab)
            <li class="nav-item" role="presentation">
                <a
                class="nav-link {{ isset($tabWithError) ? ($tab == $tabWithError ? 'active' : '') : ($k == 0 ? 'active' : '') }}"
                id="ex1-tab-1"
                data-bs-toggle="tab"
                href="#tab_{{ Str::slug($tab) }}" 
                role="tab"
                tab_name="{{ Str::slug($tab) }}" 
                aria-controls="tab_{{ Str::slug($tab) }}" 
                aria-selected="true"
                >{{ $tab }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="tab-content  {{$horizontalTabs ? '' : 'col-md-9'}}" id="ex1-content">
        @foreach ($Amer->getTabs() as $k => $tab)
        <div
            class="tab-pane fade show {{ isset($tabWithError) ? ($tab == $tabWithError ? ' active' : '') : ($k == 0 ? ' active' : '') }} border"
            id="tab_{{ Str::slug($tab) }}"
            role="tabpanel"
            aria-labelledby="tab_{{ Str::slug($tab) }}">
            <div class="row">
                @include(fieldview('inc.show_fields'), ['fields' => $Amer->getTabFields($tab)])
            </div>
        </div>
        @endforeach
    </div>
</div>