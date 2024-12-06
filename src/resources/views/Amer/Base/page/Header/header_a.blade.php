<!-- inc.header_a -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#logonav" aria-controls="logonav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="col-sm">
                    <img src="{{asset(config('Amer.Amer.co_logo'))}}" style="width: 17px;">
                </span>
            </button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mapmarker" aria-controls="mapmarker" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-map-marker "></span>
            </button>
            <div class="collapse navbar-collapse col-sm-2" id="logonav">
                <div class="col-sm navbar-nav" style=" background-image:url({{asset(config('Amer.Amer.min_logo'))}});background-repeat: no-repeat;background-size: contain; height: 50px">
                </div>
                <div class="col-sm navbar-nav" style="background-image:url({{asset(config('Amer.Amer.hc_logo'))}});background-repeat: no-repeat;background-size: contain; height: 50px"></div>
                <div class="col-sm navbar-nav" style="background-image:url({{asset(config('Amer.Amer.co_logo'))}});background-repeat: no-repeat; background-size: contain; height: 50px"></div>
            </div>
            <div class="text-center font-weight-bold text-white col-sm-3">
                <hgroup>
                <a href="{{url('')}}" class="">
                <h5 class="text-white">
                {{config('Amer.Amer.hc_name')}}
                </h5>
                <h6 class="text-white">
                {{config('Amer.Amer.co_name')}}
                </h6>
                </a>
                </hgroup>
            </div>
            <div class="collapse navbar-collapse col-sm-4" id="mapmarker">
                    <div  class="row">
                        <div class="col-sm-9">
                            <div class="text-center font-weight-bold text-white">
                                <h5 class="text-white text-justify">
                                <address>
                                    <span class="fa fa-map-marker "></span>
                                    <a href="{{config('amer.co_map')}}" target="_blank">
                                        {{implode("<br>",config('Amer.Amer.co_address'))}}
                                    </a>
                                </address>
                                </h5>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-center font-weight-bold text-white">
                                    @foreach(config('Amer.Amer.socialmedia.fax') as $a=>$b)
                                    <p class="fs-6 text-white">
                                        <span class="{{$b['icon'] ?? 'fa fa-fax'}}"></span>
                                        <a href="tel:{{$b['link']}}">
                                        {{$b['link']}}
                                        </a>
                                    </p>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</nav>

<!-- inc.header_a -->
