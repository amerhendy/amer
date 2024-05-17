<!-- footer.blade.php -->
<div class="text-center py-3">
        <div class="container p-4">
    <!--Grid row-->
    <div class="row">
      <!--Grid column-->
      <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
        <h5 class="text-uppercase">{{config('amer.co_name') ?? 'HCWW'}}</h5>
      </div>
      <!--Grid column-->

      <!--Grid column-->
      <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
        <ul class="list-unstyled mb-0">
          <li>
          <?php
                                $current_url=base64_encode(Request::fullUrl());
                            ?>
            <a onclick="popitup('{{url('api')}}/QRCODE/{{$current_url}}','qrcode');" class="white-text">QRCODE</a>
          </li>
          <li>
            <a href="{{config('amer.socialmedia.facebook.link') ?? url('')}}" class="white-text">
                                    FaceBook
                                </a>
          </li>
          <li>
            <a href="mailto:{{config('amer.socialmedia.email.link') ?? url('')}}" class="">E-mail</a>
          </li>
          <li>
            <a href="tel:{{config('amer.socialmedia.fax.link') ?? url('')}}" class="">Fax</a>
          </li>
        </ul>
      </div>
      <!--Grid column-->

      <!--Grid column-->
      <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
        <ul class="list-unstyled">
          <li>
            <a href="https://play.google.com/store/apps/details?id=hcww.orchtech.com.ebrd_live" class="">تطبيق 125 - اندرويد</a>
          </li>
          <li>
            <a href="https://apps.apple.com/eg/app/125/id1431089961" class="">تطبيق 125 - ايفون</a>
          </li>
          <li>
            <a href="https://play.google.com/store/apps/details?id=com.hcww.it.myreading&hl=ar&gl=US" class="">تطبيق قراءتى</a>
          </li>
        </ul>
      </div>
      <!--Grid column-->

      <!--Grid column-->
      <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
        <ul class="list-unstyled">
          <li>
            <a href="https://cms.hcww.com.eg/WebsiteComplaints/Web_CustomerComplaint?CID=23" class="">شكاوى المواطنين</a>
          </li>
          <li>
            <a href="https://www.hcww.com.eg/bill-calc/" class="">احسب فاتورتك</a>
          </li>
          <li>
            <a href="{{config('amer.socialmedia.fawrylink.link') ?? url('')}}" class="">دفع الفواتير - فورى</a>
          </li>
        </ul>
      </div>
      <!--Grid column-->
    </div>
    <!--Grid row-->
  </div>
  <!-- Grid container -->

        <div class="row">
            <div class="col">
                <div class='text-center p-3'>
                    ©2009- {{date('Y')}} Copyright:
                    <a href="{{url('')}}">{{config('amer.co_name') ?? 'NSSCWW'}}</a>
                </div>
            </div>
        </div>
    </div>
<!-- footer.blade.php -->