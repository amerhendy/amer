<!--inc.header_menu-->
<?php
    $current_url=base64_encode(Request::fullUrl());
?>
<div id="menu_area" class="menu-area">
    <div class="container-fluid">
        <div class="row">
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menue" aria-controls="menue" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="menue">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @include(mainview('Header.menu_model'))
                    @include(mainview('Header.usersBlock'))
                    @include(mainview('Header.mainmenu'))
                    @include('vendor.Amer.Base.inc.menu.mainmenu')
                    <?php
    $guards=config('auth.guards');
    ?>
@if(array_key_exists('Employers',$guards))
    @if (auth::guard('Employers')->check())
      @include('Employers::topmenu')
    @endif
@endif
                    
      </ul>
    </div>
  </div>
</nav>
</div>
    </div>
</div>