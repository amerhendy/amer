<?php
$sources=[
    'images'=>'images',
];

return [
    'default_date_format'=> 'D MMM YYYY',
    'default_datetime_format' => 'D MMM YYYY, HH:mm',
    'timeZone'=>'Africa/Cairo',
    'Carbon_dateTimeFormat'=>'j:m:Y - H:i',
    'html_direction' => 'rtl',
    'styles' => [],
    'js'=>[],    
    'route_prefix' => 'amer',
    'routeName_prefix'=>'amer',
    'api_version'=>'v1',
    'web_middleware' => 'web',
    'view_namespace' => 'Amer::',
    'root_disk_name' => 'amer',
    'package_path'=>base_path().'\\vendor\Amerhendy\Amer\src\\',
    'nameSpace'=>'Amerhendy\Amer',
    'Controllers'=>'Amerhendy\Amer\App\Http\Controllers',
    //'cachebusting_string' => \PackageVersions\Versions::getVersion('amerhendy/amer'),
    'SecretKey'=>'Amer',
    'public_path'=>base_path().'/../jobs/',
    'language'=>'arabic',
    'lang'=>'ar-eg',
    'dir'=>'rtl',
    'ENCODE'=>'UTF-8',
    'co_shor_name'=>'مياه سيناء',
    'co_name'=>'شركة مياه الشرب والصرف الصحى بشمال وجنوب سيناء',
    'co_name_english'=>'North And South  SINAI For Water And WasteWater',
    'hc_name'=>'الشركة القابضة لمياه الشرب والصرف الصحى',
    'min_name'=>'وزارة الاسكان والمرافق والمجتمعات العمرانية',
    'co_logo'=>$sources['images'].'/'.'logo.png',
    'co_logoGif'=>$sources['images'].'/'.'nsscww.gif',
    'hc_logo'=>$sources['images'].'/'.'hcww.gif',
    'min_logo'=>$sources['images'].'/'.'eagle.png',
    'co_address'=>[
        'محطة مياه شمال سيناء المرشحة - بجوار قسم شرطة القنطرة شرق - طريق العريش - مدينة القنطرة شرق - محافظة الاسماعيلية'
    ],  
    'co_map'=>'https://goo.gl/maps/Z87nVcxcpjjN9YvZ8',
    'html'=>[
        'theme-color'=>'white',
        'description'=>'شركة مياه الشرب والصرف الصحى بشمال وجنوب سيناء توظيف وظائف مهمات مهندسين حكومة حكومات',
        'keywords'=>'',
    ],
    'socialmedia'=>[
        'website'=>[
            [
                'name'=>'شركة مياه الشرب والصرف الصحى بشمال وجنوب سيناء',
                'icon'=>'fa fa-home',
                'link'=>'www.sinaiwater.com',    
            ]
            ],
        'facebook'=>[
            'name'=>'شركة مياه الشرب والصرف الصحى بشمال وجنوب سيناء',
            'icon'=>'',
            'link'=>'https://www.facebook.com/sinaiwaterpr2020',
        ],
        'email'=>[
            [
                'name'=>'البريد الاليكترونى',
                'icon'=>'fa fa-email',
                'link'=>'sinaiwater@outlook.com',
            ]
        ],
        'fax'=>[
            [
                'name'=>'الفاكس',
                'icon'=>'fa fa-fax',
                'link'=>'0643751319'
            ]
        ],
        'phone'=>[
            [
                'name'=>'التليفون',
                'icon'=>'fa fa-phone',
                'link'=>'0643751318'
            ],
            [
                'name'=>'التليفون',
                'icon'=>'fa fa-phone',
                'link'=>'0643751354'
            ],
        ],
        'fawrylink'=>[
            'name'=>'الفواتير',
            'icon'=>'',
            'link'=>'link',
        ],
    ],
    'main_dir'=>'Amer',
    'public_dir'=>'jobs',
    'mainscripts'=>[
        'js/jquery/jquery-3.6.0.min.js',
        'js/bootstrap/bootstrap.bundle.min.js',
        //'js/bootstrap/bootstrap.min.js',
        'js/color-modes.js',
        'js/packages/aos/aos.js',
        'js/packages/sweetalert/sweetalert2.all.min.js',
        'js/packages/noty/noty.min.js',
        'js/website.js',
        'js/forms.js',
    ],
    'mainstyle'=>[
        ['url'=>'css/bootstrap/bootstrap.min.css','media'=>'all'],
        ['url'=>'css/bootstrap/bootstrap.rtl.min.css','media'=>'all'],
        ['url'=>'css/bootstrap/bootstrap-grid.rtl.min.css','media'=>'all'],
        ['url'=>'css/bootstrap/bootstrap-reboot.rtl.min.css','media'=>'all'],
        ['url'=>'css/bootstrap/bootstrap-utilities.rtl.min.css','media'=>'all'],
        ['url'=>'css/awesom/all.css','media'=>'all'],
        ['url'=>'css/awesom/brands.css','media'=>'all'],
        ['url'=>'css/awesom/fontawesome.css','media'=>'all'],
        ['url'=>'css/awesom/regular.css','media'=>'all'],
        ['url'=>'css/awesom/solid.css','media'=>'all'],
        ['url'=>'css/awesom/svg-with-js.css','media'=>'all'],
        ['url'=>'css/awesom/v4-shims.css','media'=>'all'],
        ['url'=>'js/packages/aos/aos.css','media'=>'all'],
        ['url'=>'js/packages/sweetalert/sweetalert2.min.css','media'=>'all'],
        ['url'=>'js/packages/noty/noty.css','media'=>'all'],
        ['url'=>'css/printpage.css','media'=>'print'],
    ],
    //flash message 
    'Alert'=>[
        'levels' => [
            'info',
            'warning',
            'error',
            'success',
            'alert',
        ],
        'session_key' => 'alert_messages',
    ],
    'ping'=>[
        'Maps'=>[
            'Key'=>'AsN9E3qyWBFYouftQ2wqelB4ALt2_gvdlsqZApK2cy0kdNOrZeyi6oVkaHtoa3hU'
        ]
    ]
];