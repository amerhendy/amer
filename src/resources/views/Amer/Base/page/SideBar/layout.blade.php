<nav id="main-navbar" class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <button class="navbar-toggler btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation" >
        <i class="fas fa-bars"></i>
      </button>
    </div>
    <!-- Container wrapper -->
  </nav>
<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse">
    <div class="position-sticky">
      <div class="list-group list-group-flush mx-3 mt-4">
        <a href="#" class="list-group-item list-group-item-action py-2 ripple" aria-current="true">
          <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>{{trans('AMER::crud.admin')}}</span>
        </a>
        <a href="{{Amerurl('Governorates')}}" class="list-group-item list-group-item-action py-2 ripple" aria-current="true">
          <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>{{trans('AMER::Governorates.Governorates')}}</span>
        </a>
        <a href="{{Amerurl('Cities')}}" class="list-group-item list-group-item-action py-2 ripple" aria-current="true">
          <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>{{trans('AMER::Cities.Cities')}}</span>
        </a>
        @include('Amer::Base.inc.menu.admin')
        <?php $guards=config('auth.guards');?>
        @if(array_key_exists('Amer',$guards))
        @if(\File::exists(base_path('vendor/AmerHendy/Security/composer.json')))
              @include('SEC::adminsidemenu')
            @endif
          @if (auth::guard('Amer')->check())
            @if(\File::exists(base_path('vendor/AmerHendy/Employers/composer.json')))
              @include('Employers::Sidemenu-employer')
            @endif
            @if(\File::exists(base_path('vendor/AmerHendy/Employment/composer.json')))
              @include('Employment::Admin-SideBar')
            @endif
            
          @endif
        @endif
      </div>
    </div>
  </nav>
  <!-- Sidebar -->

  <!-- Navbar -->
  @push('after_scripts')
  <script>
    var CurrentPageLink=$(location).attr('href');
    sideMenuActive();
    function sideMenuActive(){
      var arr=new Array();
      $.each($('#sidebarMenu').find('[href]'),function(k,v){
        if($(v).hasClass('active')){$(v).removeClass('active')}
      })
      $.each($('#sidebarMenu').find('[href]'),function(k,v){
        if($(v).attr('href') == CurrentPageLink){
          arr.push($(v));
        }else if(CurrentPageLink.indexOf($(v).attr('href')) !== -1){
          arr.push($(v));
        }
      })
      if(arr == undefined){return;}
      talest=Math.max.apply(Math, $.map(arr, function (el) { return el.attr('href').length }));
      var elem;
      $.each(arr,function(k,v){
        if($(v).attr('href').length == talest){
          elem=$(v);
        }
      });
      $(elem).addClass('active');
      parent=$(elem).parents()
      var parentnumber;
      $.each(parent,function(k,v){
        if($(v).hasClass('mt-4')){parentnumber=k;}
      })
      for(i=0;i<parent.length;i++){
        if($(parent[i]).hasClass('mt-4')){
          //$(parent[i]).toggle()
        }
        if(i<parentnumber){
          $(parent[i]).toggle()
        }
      }
    }
    
  </script>
  @endpush