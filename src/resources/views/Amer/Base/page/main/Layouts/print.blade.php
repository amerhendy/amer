<html dir="rtl" lang="ar-eg">
    <head>
        <title>Print Page</title>
        <meta charset="utf-8">
    <!---<link rel="stylesheet" type="text/css" href="{{asset('css/printpage.css')}}" media="print">-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{asset('css/printpagescreen.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{asset('css/awesom/all.min.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{asset('css/printpage.css')}}" media="print">
    <style>
        #polarimg {
                object-fit: contain;
                max-width:50%;
                max-height:50%;;
                vertical-align: middle;
                border-style: none;
                }
        </style>
</head>

<template>
                <page size="A4" class="con" id="page-{PageId}">
                    <header  id="header-{PageId}">
                        <div class='row border-bottom' id='newline'>
                            <div class='col-sm-4 text-center'>
                                شركة مياه الشرب والصرف الصحى بشمال وجنوب سيناء<br>قطاع تنمية الموارد البشرية<br>قاعدة البيانات
                            </div>
                            <div class='col-sm-4 text-center'>
                                <img src="{{asset(config('amer.co_logo'))}}" id='polarimg'>
                            </div>
                            <div class='col-sm-4 text-center'>
                                    North And SOuth SINAI<BR>For Water And Waste-Water<br>{{Carbon\Carbon::now()->toDateTimeString()}}
                            </div>
                        </div>
                        <div class='row text-right' id='newline'>
                            <div class='col-sm-12 text-center' id="pageheader">
                                <span>بطاقة الوصف الوظيفى</span>
                            </div>
                        </div>
                            <div class='row  text-right' id='Mosama_JobName_TEXT_{PageId}' element_id="{PageId}">
                                <div class='col-sm-12 title text-center'  id='jobname' >
                                    {JobNameText}
                                </div>
                            </div>
                            </header>
                    <main id="main-{PageId}">
                        <section>
                            <div class='row text-justify' id='newline'>
                                <div class='col-sm-2' id='Mosama_JobTitles_TITLE_{PageId}'>المسمى الوظيفى :   </div>
                                <div class='col-sm border-bottom' id='Mosama_JobTitles_TEXT_{PageId}' >
                                    {Mosama_JobTitles}
                                </div>
                                <div class='col-sm-2' id='Mosama_Groups_TITLE_{PageId}'>المجموعة: </div>
                                <div class='col-sm border-bottom' id='Mosama_Groups_TEXT_{PageId}' >
                                    {Mosama_Groups}
                                </div>
                            </div>
                            <div class='row text-justify' id='newline'>
                                <div class='col-sm-2' id='Mosama_Degrees_TITLE_{PageId}'>الدرجة: </div>
                                <div class='col-sm border-bottom' id="Mosama_Degrees_TEXT_{PageId}" >
                                    {Mosama_Degrees}
                                </div>    
                                <div class='col-sm-2' id='Mosama_Managers_TITLE_{PageId}'>المسئول المباشر :   </div>
                                <div class='col-sm border-bottom' id="Mosama_Managers_TEXT_{PageId}" >
                                    {Mosama_Managers}
                                </div>
                            </div>
                            <div class='row text-justify' id='newline'>
                                <div class='col-sm-2' id='Mosama_OrgStru_1_TITLE_{PageId}'> القطاع: </div>
                                <div class='col-sm-10 border-bottom' id="Mosama_OrgStru_1_TEXT_{PageId}" >
                                            {Mosama_OrgStru_1}
                                </div>
                            </div>
                            <div class='row text-right' id='newline'>
                                <div class='col-sm-2' id='Mosama_OrgStru_4_TITLE_{PageId}'>الادارة: </div>
                                <div class='col-sm-10 border-bottom' ID='Mosama_OrgStru_4_TEXT_{PageId}' >
                                    {Mosama_OrgStru_4}
                                </div>
                            </div>
                            <div class='row text-right' id='newline'>
                                <div class='col-sm-2' id='Mosama_OrgStru_2_TITLE_{PageId}'> الادارة العامة: </div>
                                <div class='col-sm-10 border-bottom' id='Mosama_OrgStru_2_TEXT_{PageId}' >
                                {Mosama_OrgStru_2}
                                </div></div>
                        </section>
                        <section>
                            <div class='row text-right bg-light bg-gradient' id='Mosama_Goals_outDiv_{PageId}' >
                                <div class='col-sm-12 text-center'>
                                    <b>الملخص الوظيفى</b>
                                </div>
                            </div>
                            <div class='row text-right' id='Mosama_Goals_inDiv_{PageId}'>
                                <div class='col-sm-2' id='Mosama_Goals_TITLE_{PageId}'>هدف الوظيفة: </div>
                                        <div class='col-sm-10 text-justify border-bottom' id="Mosama_Goals_TEXT_{PageId}" >
                                        {Mosama_Goals}
                                        </div>
                                </div>
                        </section>
                        <section>
                            <div class='row text-right bg-light bg-gradient' id='newline'>
                                <div class='col-sm-12 text-center'>
                                    <b>علاقات الاتصال</b>
                                </div>
                            </div>
                            <div class='row text-right' id='newline'>
                                <div class='col-sm-3' id='Mosama_Connections_in_TITLE_{PageId}'>الاتصالات الداخلية: </div>
                                <div class='col-sm-9 border-bottom text-justify text-right' id="Mosama_Connections_in_TEXT_{PageId}" >
                                {Mosama_Connections_in}
                                </div>
                                <div class='col-sm-3' id='Mosama_Connections_out_TITLE_{PageId}'>الاتصالات الخارجية: </div>
                                <div class='col-sm-9 border-bottom' id="Mosama_Connections_out_TEXT_{PageId}" >
                                {Mosama_Connections_out}
                                </div>
                            </div>
                            <div class='row text-right' id='newline'>
                                <div class='col-sm-3' id='Mosama_Tasks_fatherof_TITLE_{PageId}'>العلاقات الاشرافية</div>
                                <div class='col-sm-9 border-bottom' id="Mosama_Tasks_fatherof_TEXT_{PageId}" >
                                {Mosama_Tasks_fatherof}
                                </div>
                            </div>
                        </section>
                        <section>
                            <div class='row text-right bg-light bg-gradient' id='newline'>
                                <div class='col-sm-12 text-center'>
                                    <b>المهام والمسئوليات</b>
                                </div>
                            </div>
                            <div class='row text-justify' id='newline'>
                            <div class='col-sm-2' id='Mosama_Tasks_eshraf_TITLE_{PageId}'>المهام والمسئوليات الاشرافية: </div>
                            <div class='col-sm-10 border-bottom text-justify' id="Mosama_Tasks_eshraf_TEXT_{PageId}" >
                                    {Mosama_Tasks_eshraf}
                            </div>
                            <div class='col-sm-2' id='Mosama_Tasks_wazifia_TITLE_{PageId}'>المهام والمسئوليات الوظيفية: </div>
                            <div class='col-sm-10 border-bottom text-justify' id="Mosama_Tasks_wazifia_TEXT_{PageId}" >
                                {Mosama_Tasks_wazifia}
                            </div>
                            <div class='col-sm-2' id='Mosama_Tasks_tanfiz_TITLE_{PageId}'>المهام والمسئوليات التنفيذية: </div>
                            <div class='col-sm-10 border-bottom' id="Mosama_Tasks_tanfiz_TEXT_{PageId}" >
                                {Mosama_Tasks_tanfiz}
                            </div>
                            </div>
                        </section>
                        <section>
                            <div class='row text-right bg-light bg-gradient' id='newline'>
                                <div class='col-sm-12 text-center'>
                                    <b>متطلبات الوظيفة</b>
                                </div>
                            </div>
                            <div class='row text-right' id='newline'>
                                <div class='col-sm-2' id='Mosama_Competencies_TITLE_{PageId}'>الكفاءات: </div>
                                <div class='col-sm-9 border-bottom' id="Mosama_Competencies_TEXT_{PageId}" >
                                {Mosama_Competencies}
                                </div>
                            </div>
                        </section>
                        <section>
                            <div class='row text-right bg-light bg-gradient' id='newline'>
                                <div class='col-sm-12 text-center'>
                                    <b>شروط شغل الوظيفة</b>
                                </div>
                            </div>  
                            <div class='row text-right' id='newline'>
                                <div class='col-sm-3' id='Mosama_Educations_TITLE_{PageId}'>المؤهل الدراسى: </div>
                                <div class='col-sm-9 border-bottom' id="Mosama_Educations_TEXT_{PageId}" >
                                    {Mosama_Educations}
                                </div>
                                <div class='col-sm-3' id='Mosama_Experiences_TITLE_{PageId}'>الخبرة: </div>
                                <div class='col-sm-9 border-bottom' id="Mosama_Experiences_TEXT_{PageId}" >
                                {Mosama_Experiences}
                                </div>
                                <div class='col-sm-3' id='Mosama_Skills_TITLE_{PageId}'>المهارات: </div>
                                <div class='col-sm-9 border-bottom' id="Mosama_Skills_TEXT_{PageId}" >
                                {Mosama_Skills}
                                </div>
                            </div>
                        </section>
                    </main>
                    <footer id="footer-{PageId}">
                        <div class="row">
                            <div class="col-sm-5 border-right text-center"><small>محطة مياه شمال سيناء المرشحة - بجوار قسم شرطة القنطرة شرق - طريق العريش -محافظة الاسماعيلية</small></div>
                            <div class="col-sm-4 border-right text-center"><small>website:www.sinaiwater.com<br>email:sinaiwater@outlook.com</small></div>
                            <div class="col-sm-2 border-right text-center"><small>tel:043751317<br>Fax:0643751319<br>Hotline:125</small></div>
                            <div class="col-sm-1"><figcaption></figcaption></div>
                        </div></footer>
                    <div id='lastelement'></div>
                </page>
</template>
<div class="container">
<div id='GFG'></div>
</div>
<body>
<script title="" type="application/javascript" src="{{asset('js/bootstrap.js')}}"></script>
<script title="" type="application/javascript" src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script title="" type="application/javascript" src="{{asset('packages/sweetalert/sweetalert2.all.js')}}"></script>
<script>
    function view_noty(type, val) {
    new Swal({
        type: type,
        text: val
    });
}
    link="{{route('showprintjobname')}}";
    CSRF="{{ csrf_token() }}";

            var ids=@json($data);
            var pers=@json($permessions);
            var logo="{{asset(config('amer.co_logo'))}}";
            var timenow="{{Carbon\Carbon::now()->toDateTimeString()}}";
            var homelink="{{url('/')}}";
        </script>
        
        <script title="" type="application/javascript" src="{{asset('js/printpop.js')}}" defer></script>
</body>
</html>