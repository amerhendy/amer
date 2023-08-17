<?php
?>
<!--usersBlock.blade -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="usernavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>العاملين بالشركة
        </a>
        @if(\AmerHelper::modelexists('\Amerhendy\Employers\App\Models\Base\Employers') || \AmerHelper::modelexists('\Amerhendy\Security\App\Models\User'))
        <ul class="dropdown-menu ola" aria-labelledby="usernavbarDropdown fa fa-ul">
            @include(('SEC::loginstick'))
        </ul>
        @endif
    </li>

@if (auth::guard('Amer')->check())
<!-- admin.blade -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="usernavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-user"></i>{{trans("AMER::auth.dashboard")}}
    </a>
    <ul class="dropdown-menu adminPanel" aria-labelledby="adminPanelnavbarDropdown fa fa-ul">
        @include(BaseView('inc.menu.admin'))
    </ul>
</li>
<!-- admin.blade -->
@endif

<!--END usersBlock.blade -->