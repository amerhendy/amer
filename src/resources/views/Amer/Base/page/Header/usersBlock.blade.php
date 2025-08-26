<?php
$guards=config('auth.guards');
?>
<!--usersBlock.blade -->
@if(\AmerHelper::modelexists('\Amerhendy\Employers\App\Models\Base\Employers') || \AmerHelper::modelexists('\Amerhendy\Security\App\Models\User'))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="usernavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>العاملين بالشركة
        </a>
        <ul class="dropdown-menu ola" aria-labelledby="usernavbarDropdown">
            @if(!(Auth::guard('Amer')->check() || Auth::guard('Employers')->check()))
                <li><a class="dropdown-item" id="loginformshow"><i class="fa fa-users"></i>تسجيل الدخول</a> </li>
            @endif
            @if(\AmerHelper::modelexists('\Amerhendy\Security\App\Models\User'))
                @include('SEC::loginstick')
            @endif
            @if(\AmerHelper::modelexists('\Amerhendy\Employers\App\Models\Base\Employers'))
                @include('Employers::loginstick')
            @endif
        </ul>
    </li>
@endif

@if(Auth::guard('Amer')->check())
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="adminnavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>{{ trans("AMER::auth.dashboard") }}
        </a>
        <ul class="dropdown-menu adminPanel" aria-labelledby="adminPanelnavbarDropdown">
            @include(BaseView('inc.menu.admin'))
        </ul>
    </li>
@endif

@push('after_scripts')
    @loadScriptOnce('js/Amer/login.js')
    <script>
        const LOGINTITLE = "{{ trans('SECLANG::auth.login') }}";
    </script>
@endpush

<style>
    .is-invalid {
        border: 1px solid red;
    }
</style>
