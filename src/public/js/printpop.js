    $('html').ready(function() {
        getdata();
    });
    function getdata(){
        var requests = [];
        $.each(ids,function(index,value){
            requests.push($.ajax({
                url:link,
                data:{jobnameselect:value},
                dataType: 'json',
                headers:{'X-CSRF-TOKEN':CSRF},
                type: 'post',
                timeout:600000
            }));
        });
        var pola=new Array();
        $.when.apply($, requests).done(function() {
            $.each(requests,function(index,value){
                var lol=JSON.parse(value.responseText);
                settemplates(lol);
                $.each(JSON.parse(value.responseText),function(a,b){
                    //maindata.push(b);
                });
            });
        });
    }
    function settemplates(maindata){
        template=$('template').html();
        let content = '';
       for (let i = 0; i < maindata.length; i++) {
        let entry = template.replace(/POS/g, (i + 1))
          .replace(/{PageId}/g, maindata[i].id)
          //.replace(/{JobNameText}/g, maindata[i].text)
          .replace(/{JobNameText}/g, getelementtext(maindata[i],'Mosama_JobName','-'))
          .replace(/{Mosama_JobTitles}/g, getelementtext(maindata[i],'Mosama_JobTitles','-'))
          .replace(/{Mosama_Groups}/g, getelementtext(maindata[i],'Mosama_Groups','-'))
          .replace(/{Mosama_Degrees}/g, getelementtext(maindata[i],'Mosama_Degrees','-'))
          .replace(/{Mosama_Managers}/g, getelementtext(maindata[i],'Mosama_Managers','-'))
          .replace(/{Mosama_OrgStru_1}/g, getelementtext(maindata[i],'Mosama_OrgStru_1','-'))
          .replace(/{Mosama_OrgStru_4}/g, getelementtext(maindata[i],'Mosama_OrgStru_4','-'))
          .replace(/{Mosama_OrgStru_2}/g, getelementtext(maindata[i],'Mosama_OrgStru_2','-'))
          .replace(/{Mosama_Goals}/g, getelementtext(maindata[i],'Mosama_Goals','<br>'))
          .replace(/{Mosama_Connections_in}/g, getelementtext(maindata[i],'Mosama_Connections_in',' - '))
          .replace(/{Mosama_Connections_out}/g, getelementtext(maindata[i],'Mosama_Connections_out',' - '))
          .replace(/{Mosama_Tasks_fatherof}/g, getelementtext(maindata[i],'Mosama_Tasks_fatherof',' - '))
          .replace(/{Mosama_Tasks_eshraf}/g, getelementtext(maindata[i],'Mosama_Tasks_eshraf','<ol>'))
          .replace(/{Mosama_Tasks_wazifia}/g, getelementtext(maindata[i],'Mosama_Tasks_wazifia','<ol>'))
          .replace(/{Mosama_Tasks_tanfiz}/g, getelementtext(maindata[i],'Mosama_Tasks_tanfiz','<ol>'))
          .replace(/{Mosama_Competencies}/g, getelementtext(maindata[i],'Mosama_Competencies','<ul>'))
          .replace(/{Mosama_Educations}/g, getelementtext(maindata[i],'Mosama_Educations',' أو'))
          .replace(/{Mosama_Experiences}/g, getelementtext(maindata[i],'Mosama_Experiences',' أو'))
          .replace(/{Mosama_Skills}/g, getelementtext(maindata[i],'Mosama_Skills',' - '))
          //.replace(/NAME/g, maindata[i].name)
          .replace(/GITHUB/g, maindata[i].github);
        entry = entry.replace('<a href=\'http:///\'></a>', '-');
        content += entry;
      }
      document.getElementById('GFG').innerHTML += content;
      cleancode();
    }
    function getelementtext(arr,son,splitter){
        if(son == 'Mosama_JobName'){return '<span onclick="openmenu(this)" target="'+son+'" elementId="'+arr['id']+'">'+arr['text']+'</span>';}
        //if(son == 'Mosama_Tasks_fatherof'){if(!Array.isArray(arr[son])){console.log(arr)}}
        if(!Array.isArray(arr[son])){return 'notfound';}
        if(arr[son].length == 0){return 'notfound';}
        if(arr[son].length == 1){
            back='';
            arr[son].forEach(element => {back+='<span onclick="openmenu(this)" target="'+son+'" elementId="'+element['id']+'">'+element['text']+'</span>';});
            return back;
        }
        if(arr[son].length > 1){
            back=[];
            arr[son].forEach(element => {
                if((splitter.includes('ul')) || (splitter.includes('ol'))){back.push('<li onclick="openmenu(this)" target="'+son+'" elementId="'+element['id']+'">'+element['text']+'</li>');
                }else{
                    back.push('<span onclick="openmenu(this)" target="'+son+'" elementId="'+element['id']+'">'+element['text']+'</span>');
                }
            });
            if((splitter.includes('ul')) || (splitter.includes('ol'))){
                text=splitter
                text +=back.join('')
                if(splitter.includes('ul')){text += '</ul>';}if(splitter.includes('ol')){text += '</ol>';}
                
                return text;
            }
            return back.join(splitter);
            //مهندس صيانة كهربائية المراقبة والقياسات درجة الاولى مجموعة تخصصية هندسية
            
            return back;
        }
        console.log(arr[son]);
     return 'amer';
    }
    function cleancode(){
        var removetext=['Mosama_JobTitles','Mosama_Groups','Mosama_Degrees','Mosama_Managers'];
        var removeall=['Mosama_OrgStru_1','Mosama_OrgStru_2','Mosama_OrgStru_4','Mosama_Managers','Mosama_Connections_in','Mosama_Connections_out','Mosama_Tasks_fatherof','Mosama_Tasks_eshraf','Mosama_Tasks_wazifia','Mosama_Tasks_tanfiz','Mosama_Educations','Mosama_Experiences','Mosama_Skills'];
        var removeparent=['Mosama_Goals','Mosama_Competencies'];
        $('page').each(function(e){
            page=$('page')[e];
            pageid=$(page).attr('id');
            mydivsplit=pageid.split('-');
            pagenumber=mydivsplit[1];
            $(removetext).each(function(key,val){
                targetTitle=$('#'+val+'_TITLE_'+pagenumber);
                targetText=$('#'+val+'_TEXT_'+pagenumber)
                targetval=$(targetText).text();
                if(targetval.includes("notfound")){
                    //$(targetTitle).remove()
                    $(targetText).remove()
                } 
            });
            $(removeall).each(function(key,val){
                targetTitle=$('#'+val+'_TITLE_'+pagenumber);
                targetText=$('#'+val+'_TEXT_'+pagenumber)
                targetval=$(targetText).text();
                if(targetval.includes("notfound")){
                    $(targetTitle).remove();
                    $(targetText).remove();
                }
            });
            $(removeparent).each(function(key,val){
                targetTitle=$('#'+val+'_TITLE_'+pagenumber);
                targetText=$('#'+val+'_TEXT_'+pagenumber)
                targetval=$(targetText).text();
                parent1=$('#'+val+'_inDiv_'+pagenumber);
                if(targetval.includes("notfound")){
                    $(parent1).remove()
                }
            });
        });
    }
    function openmenu (e) {
        ElementTarget=$(e).attr('target');
        console.log(ElementTarget);
        if(ElementTarget.includes('Mosama_OrgStr')){ElementTarget='Mosama_OrgStru';}
        if(ElementTarget.includes('Mosama_Connections')){ElementTarget='Mosama_Connections';}
        if(ElementTarget.includes('Mosama_Tasks')){ElementTarget='Mosama_Tasks';}
        ElementId=$(e).attr('elementid');
        ElementAdd=false;
        ElementUpdate=false;
        ElementShow=false;
        if(pers.includes(ElementTarget+'_add')){ElementAdd=links(ElementTarget,'add',ElementId);}
        if(pers.includes(ElementTarget+'_update')){ElementUpdate=links(ElementTarget,'update',ElementId);}
        if(pers.includes(ElementTarget+'_show')){ElementShow=links(ElementTarget,'show',ElementId);}
        var text='';
        title=$(e).html();
        if(ElementShow !== false){
            text+='<span><a href="'+ElementShow+'" class="btn btn-success" target="_blank"><i class="fa fa-list"></i></a></span>';
        }
        if(ElementAdd !== false){
            text+='<span><a href="'+ElementAdd+'" class="btn btn-success" target="_blank"><i class="fa fa-plus"></i></a></span>';
        }
        if(ElementUpdate !== false){
            text+='<span><a href="'+ElementUpdate+'" class="btn btn-warning" target="_blank"><i class="fa fa-pencil-alt"></i></a></span>';
        }
        Swal.fire({
    title: '<strong>' + title + '</strong>',
    icon: 'info',
    html: text,
    showCloseButton: true,
    showCancelButton: false,
    focusConfirm: true,
    confirmButtonText: 'اغلاق',
    
})

    }
    function links(ElementTarget,operator,ElementId){
        if(operator == 'add'){return homelink+'/Mosama/'+ElementTarget+'/create';}
        if(operator == 'update'){return homelink+'/Mosama/'+ElementTarget+'/'+ElementId+'/edit';}
        if(operator == 'show'){return homelink+'/Mosama/'+ElementTarget;}
    }