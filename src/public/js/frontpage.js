(function(){
    id=$('#fullhome_0');
    link=websitelink+'/api/frontpage';
    jQuery.ajax({
   	url:link,
   	dataType: 'json',
   	type: 'get',
   	success: function(data) {
        set_home_page(id,data);
   	},
   	error: function (e,xhr,opt) {
        if(e.status == 500){
            showerror("Error","Error 500 requesting <br>" + opt + "<br>" + e.responseJSON['message'] );
        }else{
            showerror("Error","Error "+e.status+" requesting <br>" + opt + "<br>" + e.responseJSON['message'] );
        }
   	}
   });
})(jQuery)
function set_home_page(id,data){
    htmld='';
    res=data;
    for(var k in res){
        vold=res[k];
        htmld+="<!-- frontpage.js --><div class='col col-sm-6' id='fullhome_col_"+k+"'  data-aos='zoom-in'>";
            htmld+='<div class="row rounded-lg light-green lighten-1">';
                htmld+='<div class="col col-sm-12 white-text font-weight-bold justify-content-center text-center">';
                    htmld+='<h3>';
                        htmld+=jstrans['homepage_annonce_number']+" ("+vold['number']+") ";
                        htmld+=jstrans['homepage_annonce_foryear']+" "+vold['year'];
                        htmld+="(<span class='display-5'>";
                        var lenplace  = Object.keys(vold['place']).length;
                        for (var i = 0, l = lenplace-1; i <= l; i++) {
                            if(i !== lenplace-1){
                                htmld+=vold['place'][i]['name']+'-';
                            }else{
                                htmld+=vold['place'][i]['name'];
                            }
                        }
                        htmld+="</span>)";
                    htmld+='</h3>';
                htmld+='</div>';
                if(vold['employment_annonce']){
                    htmld+='<div class="col col-sm-12 white-text font-weight-bold justify-content-center text-center">';
                    var lenemployment_annonce  = Object.keys(vold['employment_annonce']).length;
                        for (var i = 0, l = lenemployment_annonce-1; i <= l; i++) {
                            if(vold['employment_annonce'][i]['statue'] === 'published'){
                                htmld+=vold['employment_annonce'][i]['text'];
                            }
                        }
                    htmld+='</div>';
                }
            htmld+='</div>';
            ///
            len_jobs=Object.keys(vold['employment_job']).length;
            if(len_jobs !== 0){
                htmld+='<div class="container">';
                    htmld+='<div class="row">';
                    for (var i = 0, l = len_jobs-1; i <= l; i++) {
                        
                            job=vold['employment_job'][i];
                            annslug=vold['slug'];
                            jobslug=job['slug'];
                            htmld+='<a href="'+websitelink+'/employment_operation/annoncejobinfo/'+annslug+'/'+jobslug+'">';
                                htmld+='<div class="col-md-12 btn btn-primary">';
                                    htmld+='<h5 class="mb-0 white-text">';
                                        htmld+=vold['employment_job'][i]['code']+' : '+vold['employment_job'][i]['name']+' ( '+vold['employment_job'][i]['job_name']+' )';
                                    htmld+='</h5>';
                                htmld+='</div>';
                            htmld+='</a>';
                        
                    }
                    htmld+='</div>';
                htmld+='</div>';
            }
                    htmld+='</div>';
        htmld+='</div><!-- frontpage.js -->';
    }
    id.html(htmld);
}
