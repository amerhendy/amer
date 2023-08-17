<!-- inc.header_a -->
<nav class="navbar navbar-dark navbar-expand-lg navbar-dark bg-dark scrolling-navbar">
    <div class="container-fluid ">
        <div class="row">
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#logonav" aria-controls="logonav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="col-sm">
                    <img src="{{asset(config('amer.co_logo'))}}" style="width: 17px;">
                </span>
            </button>
            <div class="col-sm-2">
                <div class="collapse navbar-collapse" id="logonav">
                        <div class="col-sm navbar-nav" style=" background-image:url({{asset(config('amer.min_logo'))}});background-repeat: no-repeat;background-size: contain; height: 50px">
                        </div>
                        <div class="col-sm navbar-nav" style="background-image:url({{asset(config('amer.hc_logo'))}});background-repeat: no-repeat;background-size: contain; height: 50px"></div>
                        <div class="col-sm navbar-nav" style="background-image:url({{asset(config('amer.co_logo'))}});background-repeat: no-repeat; background-size: contain; height: 50px"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="text-center font-weight-bold text-white">
                    <hgroup>
                    <a href="{{url('')}}" class="">
                    <h5 class="text-white">
                    {{config('amer.hc_name')}}
                    </h5>
                    <h6 class="text-white">
                    {{config('amer.co_name')}}
                    </h6>
                    </a>
                    </hgroup>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#mapmarker" aria-controls="mapmarker" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-map-marker "></span>
            </button>
            <div class="col-sm-4">
                <div class="collapse navbar-collapse" id="mapmarker">
                    <div  class="row">
                        <div class="col-sm-9">
                            <div class="text-center font-weight-bold text-white">
                                <h5 class="text-white text-justify">
                                <address>
                                    <span class="fa fa-map-marker "></span>
                                    <a href="{{config('amer.co_map')}}" target="_blank">
                                        {{config('amer.co_address')}}
                                    </a>
                                </address>
                                </h5>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-center font-weight-bold text-white">
                                <h5 class="text-white ">
                                    <span class="fa fa-fax"></span>
                                    <a href="tel:{{config('amer.socialmedia.fax.link')}}">
                                    {{config('amer.socialmedia.fax.link')}}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>
<!-- inc.header_a -->