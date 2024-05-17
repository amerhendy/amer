(function(){
    const Templates=['AnnonceTitleTemplate','JobTemplate'];
    $.each(Templates,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
    id=$('#fullhome_0');
    link=api+'frontpage';
    jQuery.ajax({
   	url:link,
   	dataType: 'json',
   	type: 'post',
   	success: function(data) {
        $.each(data,function(k,v){
            if(v.Employment_Jobs.length !== 0){
                setAnnonceDiv(v);
                setJobs(v);
            }
            
        });
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
   setAnnonceDiv=function(data){
        var template=$(AnnonceTitleTemplate).html();
        var templateA=$(template).clone();
        $(templateA).attr('id','fullhome_col_'+data.Slug);
        var annonceTitle=$(templateA).find('#annonceTitle');
        annonceTitle.text(replacestr(annonceTitle.text(),[data.number,data.year]));
        if(data.place.length !== 0){
            $(annonceTitle).append(" ("+data['place'].join(' - ')+ ")")
        }
        if(data.description !== '' || data.description !== null){
            var desctip =$(templateA).find('span');
            desctip.text(data.description);
        }
        var mainJobsDiv=$('<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 py-5" id="mainDivJobs"></div>')
        $(templateA).append(mainJobsDiv);
        $(id).append(templateA);
   }
   setJobs=function(data){
    var annonceDiv=$('#fullhome_col_'+data.Slug);
    $.each(data.Employment_Jobs,function(k,v){
        setJobTemplate(v,annonceDiv,data.Slug);
    });
   }
   setJobTemplate=function(data,annonceDiv,AnnonceSlug){
    var template=$(JobTemplate).html();
    var templateA=$(template).clone();
    var name=$(templateA).find('#name');
    name.text(data.name);
    var jobapplyurldata=[websitelink,'employment_operation','annoncejobinfo',AnnonceSlug,data.Slug];
    name.attr('data',JSON.stringify(jobapplyurldata));
    var JobCode=$(templateA).find('#jobCode');
    JobCode.text(data.code);
    var JobName=$(templateA).find('#job_name');
    JobName.text(data.job_name);
    var form=$(templateA).find('a');
    form.attr('href',`${jobapplyurldata[0]}${jobapplyurldata[1]}/${jobapplyurldata[2]}/${jobapplyurldata[3]}/${jobapplyurldata[4]}`);
    var mainDivJobs=$(annonceDiv).find('#mainDivJobs');
    $(mainDivJobs).append(templateA);

   }
})(jQuery)

//href="`+websitelink+`/`