@extends(Baseview('app'))
@push('styles')
    <style>
        .requireinput{
            border-color: #0B90C4;
        }
        .select2-results__group{
        background-color:gray;
        }
        .has-error{
            border-color: rgb(185, 74, 72) !important;
        }
    </style>
@endpush
@push('scripts')
@endpush
@section('content')
    @parent
    @php    $currenttab='main';   @endphp
    @include(Baseview('layout.searchome'))
        <div class="row">
            <div class="linkresult">
            </div>
        </div>
    </div>
    <div class="row">
        <?php
        $settings=$Amer->settings();
        $permessions=[];
        if(isset($settings['clone.access']) && $settings['clone.access'] == 'true'){$permessions['clone']=1;}
        if(isset($settings['create.access']) && $settings['create.access'] == 'true'){$permessions['create']=1;}
        if(isset($settings['delete.access']) && $settings['delete.access'] == 'true'){$permessions['delete']=1;}
        if(isset($settings['list.access']) && $settings['list.access'] == 'true'){$permessions['list']=1;}
        if(isset($settings['show.access']) && $settings['show.access'] == 'true'){$permessions['show']=1;}
        if(isset($settings['update.access']) && $settings['update.access'] == 'true'){$permessions['update']=1;}
        ?>
        @if($Amer->getCurrentOperation() =='update')
            @include(Baseview('layout.create'))
        @else
            @include(Baseview('layout.'.$Amer->getCurrentOperation()))
        @endif
        
    
    </div>
@endsection