addmodaltobody();
$('#closemodal').click(function(){$('.modal').modal('hide');});

const searchButton = document.getElementById('search-button');
const searchInput = document.getElementById('search-input');
const searchresultdiv_id="tols_search_result";
const link_tag_cv=link_home+"magles/tags/tag/:id/show";
const link_edara_cv=link_home+"magles/edara/edara/:id/show";
const link_topic_cv=link_home+"magles/topics/topics/:id/show";
const link_amin_cv=link_home+"magles/amin/amin/:id/show";
const link_qarara_cv=link_home+"magles/qararat/qarara/:id/show";
const link_galasa_cv=link_home+"magles/galasat/galasa/:id/show";
const link_member_cv=link_home+"magles/members/member/:id/show";
const allmembersapi=link_home+"api/magles/members";
const allaminsapi=link_home+"api/magles/amins";
const allgalasatapi=link_home+"api/magles/galasat";
const alltopicsapi=link_home+"api/magles/topics";
var delayInAjaxCall = (function(){var timer = 0;return function(callback, milliseconds){clearTimeout (timer);timer = setTimeout(callback, milliseconds);};})();
searchInput.addEventListener('keyup', () => {
   delayInAjaxCall(function(){
      var searchTerm = $(".search").val();
      var searchlink=link_home+'api/magles/search';
      $.ajax({
         type : postmethod,
         url :searchlink,
         header:{'X-CSRF-TOKEN':crf},
         data:{'q':searchTerm,"_token": crf},
         success:function(data){
            $html='';
            if(!data['result']){$html+='البيانات غير موجودة!';$('#'+searchresultdiv_id).html($html);return showhidediv('hide');}
            if(data['result'] == 'empty'){$html+='البيانات غير موجودة!';$('#'+searchresultdiv_id).html($html);return showhidediv('hide');}
            if(data['result'] == 'fail'){$html+='البيانات غير موجودة!';$('#'+searchresultdiv_id).html($html);return showhidediv('hide');}
            if(!data['data']){return '';}
            var tdata=data['data'];
            $html+=viewsearch_result(tdata);
            $('#'+searchresultdiv_id).html($html)
            return showhidediv('show');
         }
      });
   }, 500 );
});
function viewmembers(type){
   if(type == 'members'){link=allmembersapi;}
   if(type == 'amin'){link=allaminsapi;}
   if(type == 'galsat'){link=allgalasatapi;}
   if(type == 'topics'){link=alltopicsapi;}
   $.ajax({
      type : 'get',
      url :link,
      success:function(data){
         html='';
         html+=sethtmltable(data,type);
         $('.linkresult').html(html);
      }
   });
  }
function viewsearch_result(tdata){
   for(i=0;i < tdata.length; i++){
      ddata=tdata[i];
      if(ddata['datatype'] == 'topics'){
         da_icon="fa fa-map-o";
         da_type_text="مذكرة";
         da_title=ddata['topic_number']+ " - " + ddata['topic_title'];
         da_link_ex=setlink(link_topic_cv,':id',ddata['id'])
         da_text=ddata['topic_text'];
      }
      if(ddata['datatype'] == 'qarar'){
         da_icon="fa fa-check-square-o";
         da_type_text="قرار";
         da_title="قرار رقم ("+ddata['qarar_number']+ ") لسنة "+parseInt(ddata['qarar_year']);
         da_link_ex=setlink(link_qarara_cv,':id',ddata['id'])
         da_text=ddata['qarar_text'];
      }
      $html+='<div class="row border">';
         $html+='<div class="col-sm-1 border-right">';
            $html+='<i class="fa '+da_icon+'" aria-hidden="true"></i> '+da_type_text;
         $html+='</div>';
         $html+='<div class="col-sm border-right">';
            $html+='<a href="'+da_link_ex+'" target="_blank">'+da_title+'</a><br>';
            $html+=da_text;
         $html+='</div>';
      $html+='</div>';
   }
   return $html;
}
function showhidediv(eve) {
   var x = document.getElementById("tols_search_result");
   if(eve == 'show'){x.style.display = "block";}
   if(eve == 'hide'){x.style.display = "none";}
 }
function setlink(type,old,newone){
   return type.replace(old,newone);
}
function sethtmltableheader(type){
   var dadt=[];
   dadt['members']=['الاسم','النشاط','البداية','جلسات','قرارات'];
   dadt['amin']=['الاسم','الجلسات','المذكرات','القرارات'];
   dadt['galsat']=['رقم الجلسة','التاريخ','الموضوعات','القرارات','امين السر'];
   dadt['topics']=['رقم الجلسة','رقم الموضوع','عنوان الموضوع','رقم القرار','مقدم المذكرة'];
   html='';
   for(i=0;i < dadt[type].length; i++){
      html+='<th class="border text-center" data-orderable="true" data-class-name="priority">'+dadt[type][i]+'</th>';
   }
   return html;
}

function sethtmltable(data,type){
   html='';
   html+='<table id="DOES" class="display stripe table-border nowrap row-border order-column cell-border compact">';
      html+='<thead class=""><tr>';
         html+=sethtmltableheader(type);
      html+='</tr></thead>';
      html+='<tbody>';
         html+=sethtmltablebody_members(data,type);
      html+='</tbody>';
      html+='<tfoot>';
         html+=sethtmltableheader(type);
      html+='</tfoot>';
   html+='</table>';
   return html;
}
function viewdata (e){
   nextdiv=$(e).next();
   nextdivclass=nextdiv.attr('class');
   nextdiv.toggle();
}
function getprearabic(data){
   $pre='';
   if(data == 'member_col'){$pre='عضو منتخب';}
   else if(data == 'member_job'){$pre='عضو معين';}
   else if(data == 'chairman'){$pre='رئيس مجلس ادارة';}
   else if(data == 'memberplus'){$pre='عضو منتدب';}
   else if(data == 'chairmanplus'){$pre='رئيس مجلس ادارة وعضو منتخب';}
   else if(data == 'neqaba'){$pre='عضو نقابى';}
   return $pre;
}
function sethtmltablebody_members(tdata,type){
   html='';
   start_tr='<tr class="border" id="tr_:id">';
   end_tr='</tr>';
   if(type == 'members'){
         for(i=0;i<tdata.length;i++){
            data=tdata[i];
            html+=setlink(start_tr,':id',data['info']['id'])
               html+='<th class="text-right border">';
                  html+='<a href="'+setlink(link_member_cv,':id',data['info']['id'])+'" target="_blank">';
                     html+='<span  id="name_'+data['info']['id']+'">'+data['info']['member_name']+'</span> - '+getprearabic(data['info']['pre']);
                  html+='</a>';
               html+='</th>';
               html+='<TD class="text-center border">';
                  if(data['info']['finish'] == '0'){ html+='<i class="fa fa-power-on" aria-hidden="true"></i>نشط';}else{html+='<i class="fa fa-power-off" aria-hidden="true"></i>خامل';}
               html+='</td>';
               html+='<TD class="text-center border">';
                  if(data['info']['start_date'] == null){html+='';}else{html+=data['info']['start_date'];}
               html+='</td>';
               html+='<TD class="text-center border">';
                  if(data['galasat'].length == 0){
                     html+='';
                  }else{
                     html+='<a href="javascript:void(0)" onclick="viewdata(this)" member-id="'+data['info']['id']+'" data-button-type="galasat">'+data['galasat'].length+'</a>';
                  }
                  html+='<div style="display:none;">';
                     for(o=0;o < data['galasat'].length; o++){
                        galsa=data['galasat'][o];
                        html+='<a href="'+setlink(link_galasa_cv,':id',galsa['id'])+'" target="_blank">'+galsa['galsa_num']+'</a> - ';
                     }
                  html+='</div>';
               html+='</td>';
               html+='<TD class="text-center border">';
                  if(data['qararat'].length == 0){
                     html+='';
                  }else{
                     html+='<a href="javascript:void(0)" onclick="viewdata(this)" member-id="'+data['info']['id']+'" data-button-type="qararat">'+data['qararat'].length+'</a>';
                  }
                  html+='<div style="display:none;">';
                     for(o=0;o < data['qararat'].length; o++){
                        qarar=data['qararat'][o];
                        html+='<a href="'+setlink(link_qarara_cv,':id',qarar['id'])+'" target="_blank">'+qarar['qarar_number']+'/'+parseInt(qarar['qarar_year'])+'</a> - ';
                     }
                  html+='</div>';
               html+='</td>';
            html+=end_tr;
      }
   }else if(type == 'amin'){
      for(i=0;i<tdata.length;i++){
         data=tdata[i];
         html+=setlink(start_tr,':id',data['info']['id'])
            html+='<th class="text-right border">';
               html+='<a href="'+setlink(link_amin_cv,':id',data['info']['id'])+'" target="_blank">';
                     html+='<span  id="name_'+data['info']['id']+'">'+data['info']['name']+'</span>';
                  html+='</a>';
            html+='</th>';
            html+='<TD class="text-center border">';
               if(data['galasat']){
                  html+='<a href="javascript:void(0)" onclick="viewdata(this)" member-id="'+data['info']['id']+'" data-button-type="qararat">'+data['galasat']['data'].length+'</a>';
               }else{html+='0';}
            html+='</td>';
            html+='<TD class="text-center border">';
               if(data['topics']){
                  html+='<a href="javascript:void(0)" onclick="viewdata(this)" member-id="'+data['info']['id']+'" data-button-type="qararat">'+data['topics']['data'].length+'</a>';
               }else{html+='0';}
            html+='</td>';
            html+='<TD class="text-center border">';
               if(data['qarart']){
                  html+='<a href="javascript:void(0)" onclick="viewdata(this)" member-id="'+data['info']['id']+'" data-button-type="qararat">'+data['qarart']['data'].length+'</a>';
               }else{html+='0';}
            html+='</td>';
         html+=end_tr;
      }
   }else if(type == 'galsat'){
      for(i=0;i<tdata.length;i++){
         data=tdata[i];
         html+=setlink(start_tr,':id',data['id'])
            html+='<th class="text-right border">';
               html+='<a href="'+setlink(link_galasa_cv,':id',data['id'])+'" target="_blank">';
               html+='<span  id="name_'+data['id']+'">'+data['galsa_num']+'</span>';
            html+='</a>';
         html+='</th>';
         html+='<TD class="text-center border">';
            html+=data['galsa_date'];
         html+='</td>';
         html+='<TD class="text-center border">';
            html+=data['topics'].length;
         html+='</td>';
         html+='<TD class="text-center border">';
            html+=data['magles_qararat'].length;
         html+='</td>';
         html+='<TD class="border">';
            html+='<a href="'+setlink(link_amin_cv,':id',data['magles_amin']['id'])+'" target="_blank">';
               html+='<span  id="name_'+data['magles_amin']['id']+'">'+data['magles_amin']['name']+'</span>';
            html+='</a>';
         html+='</td>';
         html+end_tr;
      }
   }else if(type == 'topics'){
      for(i=0;i<tdata.length;i++){
         data=tdata[i];
         html+=setlink(start_tr,':id',data['id'])
            html+='<th class="text-right border">';
                  html+='<a href="'+setlink(link_galasa_cv,':id',data['galsa_id_select']['id'])+'" target="_blank">';
                  html+='<span  id="name_'+data['id']+'">'+data['galsa_id_select']['galsa_num']+'</span>';
               html+='</a>';
            html+='</th>';
            html+='<TD class="text-center border">';
                  html+='<a href="'+setlink(link_topic_cv,':id',data['id'])+'" target="_blank">';
                  html+=data['topic_number'];
               html+='</a>';
            html+='</td>';
            html+='<TD class="text-center border">';
               html+=data['topic_title'];
            html+='</td>';
            html+='<TD class="text-center border">';
               if(data['magles_qararat'] !== null){
                  html+='<a href="'+setlink(link_qarara_cv,':id',data['magles_qararat']['id'])+'" target="_blank">';
                     html+=data['magles_qararat']['qarar_number']+' لسنة '+parseInt(data['magles_qararat']['qarar_year']);
                  html+='</a>';
               }else{html+='';}
            html+='</td>';
            html+='<TD class="text-center border">';
               if(data['magles_topics_wared_edarat'] !== null){
                     for(i=0;i<data['magles_topics_wared_edarat'].length;i++){
                        edara=data['magles_topics_wared_edarat'][i];
                        html+='<a href="'+setlink(link_edara_cv,':id',edara['id'])+'" target="_blank">';
                           html+=edara['name'];
                        html+='</a>';
                     }
                  
               }else{html+='';}
            html+='</td>';
         html+end_tr;
      }
   }
  return html;
}
function viewviewedaratserialserial (data){
   html='';
   for(i=0;i<data.length;i++){
      html+='<a href="'+setlink(link_edara_cv,':id',data['id'])+'" target="_blank">'+data[i]['name']+'</a><br>';
   }
   $('.modal-body').html(html);
   $('.modal').modal('show')
}
function viewtagsserial (data){
   html='';
   for(i=0;i<data.length;i++){
      html+='<a href="'+setlink(link_tag_cv,':id',data['id'])+'" target="_blank">'+data[i]['tag']+'</a><br>';
   }
   $('.modal-body').html(html);
   $('.modal').modal('show')
}
function viewqararatserial (data){
   html='';
   for(i=0;i<data.length;i++){
      html+='<a href="'+setlink(link_qarara_cv,':id',data['id'])+'" target="_blank">قرار رقم ('+data[i]['qarar_number']+') لسنة '+parseInt(data[i]['qarar_year'])+'</a><br>';
   }  
   $('.modal-body').html(html);
   $('.modal').modal('show')
}
function viewtopicsserial (data){
   //return console.log(data);
   html='';
   for(i=0;i<data.length;i++){
      if(data[i]['galsa_num']){
         html+='جلسة رقم ('+data[i]['galsa_num']+') بتاريخ '+data[i]['galsa_date']+'<br>';
      }
      html+='<a href="'+setlink(link_topic_cv,':id',data['id'])+'" target="_blank"> الموضوع ('+data[i]['topic_number']+') </a> : '+data[i]['topic_title']+'<hr>';
   }
   $('.modal-body').html(html);
   $('.modal').modal('show')
}
function viewgalasatserial (data){
   html='';
   for(i=0;i<data.length;i++){
      html+='<a href="'+setlink(link_galasa_cv,':id',data['id'])+'" target="_blank">جلسة رقم ('+data[i]['galsa_num']+') بتاريخ '+data[i]['galsa_date']+'</a><br>';
   }
   $('.modal-body').html(html);
   $('.modal').modal('show')
}
function addmodaltobody(){
   html='';
   html+='<div class="modal" tabindex="-1" role="dialog">';
       html+='  <div class="modal-dialog" role="document">';
           html+='<div class="modal-content">';
               html+='<div class="modal-header">';
               html+='<span class="header-text"></span>';
               html+='<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
               html+='</div>';
               html+='<div class="modal-body"></div>';
               html+='<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal" id="closemodal">Close</button></div>';
           html+='</div>';
       html+='</div>';

   html+='</div>';
   $("body").prepend(html);
}
