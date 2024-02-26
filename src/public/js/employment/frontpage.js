(function(){
    id=$('#fullhome_0');
    link=websitelink+'/api/frontpage';
    jQuery.ajax({
   	url:link,
   	dataType: 'json',
   	type: 'post',
   	success: function(data) {
        set_home_page(id,data);
   	},
   	error: function (e,xhr,opt) {
        if(e.status == 419){
            showerror(jstrans['error'],"Error 419 requesting <br>" + opt + "<br>" + e.responseJSON['message'] +"<br> Please Refresh Page");
        }
        else if(e.status == 500){
            showerror(jstrans['error'],"Error 500 requesting <br>" + opt + "<br>" + e.responseJSON['message'] );
        }else{
            showerror(jstrans['error'],jstrans['error']+" "+e.status+" <br>" + opt + "<br>" + e.responseJSON['message'] );
        }
   	}
   });
   function set_home_page(id,data){
    element=id
    res=data;
    for(var k in res){
        vold=res[k];
        var container=$('<div></div>');
        $(container).attr('class','container px-4 py-5')
        $(container).attr('id','fullhome_col_'+k)
        var htitle=$('<h5 class="pb-1 border-bottom"></h5>');
        $(htitle).append(jstrans['homepage_annonce_number']+" ("+vold['number']+") ")
        $(htitle).append(jstrans['homepage_annonce_foryear']+" "+vold['year'])
        $(htitle).append("(<span class=''>")
        var lenplace  = Object.keys(vold['place']).length;
        if(vold['place'].length !== 0){
            $(htitle).append(vold['place'].join(' - '))
        }
        if(vold['employment_annonce']){
            $(htitle).append($('<div class="col col-sm-12 text-white font-weight-bold justify-content-center text-center">'));
            var lenemployment_annonce  = Object.keys(vold['employment_annonce']).length;
                for (var i = 0, l = lenemployment_annonce-1; i <= l; i++) {
                    if(vold['employment_annonce'][i]['statue'] === 'published'){
                        $(htitle).append($(vold['employment_annonce'][i]['text']));
                    }
                }
        }
        $(htitle).append("</span>)")
        var row=$('<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 py-5"></div>')
        len_jobs=Object.keys(vold['Employment_Jobs']).length;
        for (var i = 0, l = len_jobs-1; i <= l; i++) {
            var jobdiv=$('<div class="col d-flex align-items-start"></div>')
            $(jobdiv).append($(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg>`))
            var insidediv=$('<div></div>')
            
                job=vold['Employment_Jobs'][i];
                var jobapplyurldata=[websitelink,'employment_operation','annoncejobinfo',vold['Slug'],job['Slug']];
            $(insidediv).append($('<h5 class="fw-bold mb-0 fs-4 text-body-emphasis" data=\''+JSON.stringify(jobapplyurldata)+'\'>'+vold['Employment_Jobs'][i]['name']+'</h5>'))
            var p=$('<p></p>')
            contenttext=vold['Employment_Jobs'][i]['code']+'<br>'+vold['Employment_Jobs'][i]['job_name']+"<br>";
            contenttext+=`<form action="`+jobapplyurldata.join('/')+`" method='POST'><input type="hidden" name="_token" value="`+$('meta[name="csrf-token"]').attr('content')+`">
                        <Button data-bs-target="viewjob"  class="btn btn-primary btn-sm" role="button"><i class="fa fa-eye" aria-hidden="true"></i>عرض اشتراطات الوظيفة</Button></form>`;
            $(p).append(contenttext);
            $(insidediv).append($(p))
            $(jobdiv).append($(insidediv))
            $(row).append($(jobdiv));
        }
        $(container).append($(htitle));$(container).append($(row));
        $(element).append($(container));
        //$(element).append('Amer');
    }
}
})(jQuery)

//href="`+websitelink+`/`