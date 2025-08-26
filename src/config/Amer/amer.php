<?php
return [
    'API_CLIENT_ID'=>env('API_CLIENT_ID'),
    'API_CLIENT_SECRET'=>env('API_CLIENT_SECRET'),
    'browser_cache'=>[
        'cache-control'=>'no-cache, no-store, must-revalidate', //no-cache, no-store, must-revalidate, false
        'expires'=>0, //0 OR Wed, 26 Feb 1997 08:21:57 GMT
        'pragma'=>'no-cache' // no-cache OR false
    ],
    'default_date_format'=> 'D MMM YYYY',
    'default_datetime_format' => 'D MMM YYYY, HH:mm',
    'timeZone'=>'Africa/Cairo',
    'Carbon_dateTimeFormat'=>'Y:MMMM:D - h:m',
    'Currency'=>'EGP',
    'html_direction' => 'rtl',
    'styles' => [],
    'js'=>[],
    'route_prefix' => 'amer',
    'routeName_prefix'=>'amer',
    'api_version'=>'v1',
    'web_middleware' => 'web',
    'view_namespace' => 'Amer::',
    'root_disk_name' => 'amer',
    'package_path'=>base_path().'\\vendor\amerhendy\amer\src\\',
    'nameSpace'=>'Amerhendy\Amer',
    'Controllers'=>'Amerhendy\Amer\App\Http\Controllers',
    //'cachebusting_string' => \PackageVersions\Versions::getVersion('amerhendy/amer'),
    'SecretKey'=>'Amer',
    'public_path'=>base_path().'/../',
    'language'=>'arabic',
    'lang'=>'ar-eg',
    'dir'=>'rtl',
    'ENCODE'=>'UTF-8',
    'co_shor_name'=>'مياه سيناء',
    'co_name'=>'شركة مياه الشرب والصرف الصحى بشمال وجنوب سيناء',
    'co_name_english'=>'North And South  SINAI For Water And WasteWater',
    'hc_name'=>'الشركة القابضة لمياه الشرب والصرف الصحى',
    'hc_name_english'=>'Holding Company for Drinking Water and Wastewater',
    'min_name'=>'وزارة الاسكان والمرافق والمجتمعات العمرانية',
    'min_name_english'=>'Ministry of Housing, Utilities and Urban Communities',
    'co_logo'=>'images/logo.png',
    'co_logoGif'=>'images/nsscww.gif',
    'hc_logo'=>'images/hcww.gif',
    'min_logo'=>'images/eagle.png',
    'co_address'=>[
        'محطة مياه شمال سيناء المرشحة - بجوار قسم شرطة القنطرة شرق - طريق العريش - مدينة القنطرة شرق - محافظة الاسماعيلية'
    ],
    'short_address'=>
        [
            'القنطرة شرق -طريق العريش',
            'محطة مياه شمال سيناء'
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
        'js/packages/jquery-ui-1.14.0.custom/jquery-ui.min.js',
        'js/bootstrap/bootstrap.bundle.min.js',
        //'js/bootstrap/bootstrap.min.js',
        'js/Amer/color-modes.js',
        'js/packages/aos/aos.js',
        'js/packages/sweetalert/sweetalert2.all.min.js',
        'js/packages/noty/noty.min.js',
        'js/Amer/website.js',
        'js/Amer/forms/forms.js',
        'js/Amer/forms/AmerField.js',
        'js/Amer/apiRequest.js'
    ],
    'mainstyle'=>[

        ['url'=>'js/packages/aos/aos.css','media'=>'all'],
        ['url'=>'js/packages/sweetalert/sweetalert2.min.css','media'=>'all'],
        ['url'=>'js/packages/noty/noty.css','media'=>'all'],
        ['url'=>'css/Amer/printpage.css','media'=>'print'],
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
