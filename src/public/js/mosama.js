Noty.overrideDefaults({
    layout   : 'topRight',
    theme    : 'backstrap',
    timeout  : 2500,
    closeWith: ['click', 'button'],
  });
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  $( document ).ready(function(){
    
    getdata();
  });
  perbutArr=String(perbuttext).split(",");
function getdata(){
    var dataTable = $('#DOES').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": apilink,
        "ordering": true,
        "orderable":      false,
        /*"stateSave":false,
        "stateSaveCallback": function(settings,data) {
            localStorage.setItem( listurlname+'_' + settings.sInstance, JSON.stringify(data) )
          },
        "stateLoadCallback": function(settings) {
          return JSON.parse( localStorage.getItem( listurlname+'_' + settings.sInstance ) )
          },
        */
        "data":sendNewData(columnDefs),
        "columns":setcolumnDefs(columnDefs),
        
     });
     //newItemLink
     
     if(perbutArr[0]==1){
        actionButtonsTop=links('create');
        $(".dataTables_length").prepend(actionButtonsTop);
     }
     
return;
}
function sendNewData(columnDefs){
    return ;
}
function setcolumnDefs(columnDefs){
    var obj=$.parseJSON(columnDefs);
    var arr=new Array();
    $.each( obj, function( key, child ) {
        columnName=child['col'];
        columnlabel=child['label'];
        if(key==0){cellType= "th";}else{cellType="td";}
        if(child.hasOwnProperty('type') == false){columntype='string';orderable=true;}
        if(child['type']== 'string'){
            columntype='string';
            orderable=true;
        }
        if(child['type']== 'number'){
            columntype='integer'    
            orderable=true;
        }
        if(child['type']== 'enum'){
            columntype='string'
            orderable=true;
        }
        if(child['type']== 'multiselect'){
            columntype='array'
            columnName={'targetClass':child['targetClass'],'Targetcols':child['Targetcols']};
            orderable=false;
        }
        arr[key]={
            'targets':key,
            'title': columnlabel,
            'cellType':cellType,
            'name':columnName,
            'orderable':orderable,
            //'type':columntype,
            'render':function(data, type, row, meta){
                if(child['type'] == 'multiselect'){
                    if(data.length == 0){
                        data=['-'];
                    }
                    wdata=new Array();
                    for(i=0;i < data.length;i++){
                        var dod='';
                        if(child.hasOwnProperty('beforeWord')){dod+=child['beforeWord']+' ';}
                        dod+=data[i];
                        if(child.hasOwnProperty('nextWord')){dod+=' '+child['nextWord'];}
                        wdata.push(dod);
                    }
                    data='';
                    data=wdata;
                    return data;
                }
                
                dataa='';
                if(child.hasOwnProperty('beforeWord')){dataa+=child['beforeWord']+' ';}
                if(child.hasOwnProperty('limit')){
                    var htmldata=`<div id="fullHtml" style="display:none">`+data+`
                        <br><span role="link" class="badge badge-primary" style="cursor:pointer;" onclick="readmore(this,\'shortdata\')">(اقرأ أقل)</span>
                         <span role="link" class="badge badge-primary" style="cursor:pointer;" onclick="readmore(this,\'cleandata\')">(اقرأ بدون تنسيق)</span>
                    </div>`;
                    cleandata=decodeHTMLEntities(data);
                    cleandata=removeTags(cleandata);
                    var shortText= cleandata.substring(0,child['limit']);
                    cleandata=`<div id="cleandata" style="display:none">`+cleandata+`
                                <br><span role="link" class="badge badge-primary" style="cursor:pointer;" onclick="readmore(this,\'shortdata\')">(اقرأ أقل)</span>
                                <span role="link" class="badge badge-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')">(عرض كامل)</span>
                                </div>`;
                    shortText= `<div id="shortdata">`+shortText+`.... 
                                <span role="link" class="badge badge-primary" style="cursor:pointer;" onclick="readmore(this,\'cleandata\')">(اقرأ بدون تنسيق)</span>
                                <span role="link" class="badge badge-info" style="cursor:pointer;" onclick="readmore(this,\'fullHtml\')">(عرض كامل)</span><div>`;
                    data=htmldata+cleandata+shortText;
                    //return shortText
                    
                }
                if(child['type'] == 'enum'){
                    if(child.hasOwnProperty('options')){
                        $.each(child['options'],function(k,v){
                            if(data == k){data=v;}
                        });
                    }   
                }
                dataa+=data;
                if(child.hasOwnProperty('nextWord')){dataa+=' '+child['nextWord'];}
                return dataa;
            }
            //'type':columntype,
        };
        
    });

    arr[arr.length]={
        "data": null,
        "render": function ( data, type, row, meta ) {
            dataId=row[0];
            actionButtonsInside='';
            //delete
            if(perbutArr[1]==1){
                actionButtonsInside+=links('destroy',dataId);
             }
             //update
             if(perbutArr[2]==1){
                actionButtonsInside+=links('edit',dataId);
                
             }
            //trash
             if(perbutArr[3]==1){
                actionButtonsInside+=links('trash',dataId);
             }
            return actionButtonsInside;
 
        }
    };
    return arr;
}
	if (typeof trashEntry != 'function') {
	  $("[data-button-type=trash]").unbind('click');
	  function trashEntry(button) {
		var button = $(button);
		var route = button.attr('data-route');
		var row = $("#crudTable a[data-route='"+route+"']").closest('tr');
            Swal.fire({
                icon: "warning",
                title:"تحذير",
                text: "متأكد من اضافة السجل الى سلة المهملات؟؟؟",
                showDenyButton: true,
                confirmButtonText: "سلة المهملات",
                denyButtonText:"الغاء",
            }).then((result) => {
                    if (result.isDenied) {
                        Swal.fire({
			              	title: "لم يتم اضافة السجل الى سلة المهملات",
			              	text: "لم يتم اضافة السجل الى سلة المهملات",
			              	icon: "error",
			              });
                    }
                if (result.isConfirmed) {
                    $.ajax({
                        url: route,
                        type: 'get',
                        success: function(result) {
                            var text,icon;
                            if(result['result'] === 'a'){text="العملية غير صحيحة"; icon='error'}
                            if(result['result'] === 'b'){text="الباكج غير صحيح"; icon='error'}
                            if(result['result'] === 'done'){text="تم نقل السجل الى سلة المهملات بنجاح"; icon='success'}
                            if(icon === 'success'){
                                $(button).parents('tr').remove();
                                if (row.hasClass("shown")) {
                                    row.next().remove();
                                }

                                // Remove the row from the datatable
                                row.remove();
                            }
                            Swal.fire({
                                title: text,
                                text: text,
                                icon: icon,
			              });
                        },
                        error: function(result) {
                            alert('err:'+JSON.stringify(result));
			          // Show an alert with the result
                            swal({
                                title: "trojan.trash_confirmation_not_title",
                                text: "trojan.trash_confirmation_not_message",
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                            });
                        },
                    });
                    }
                });
      }
	}
if (typeof deleteEntry != 'function') {
  $("[data-button-type=delete]").unbind('click');

  function deleteEntry(button) {
    // ask for confirmation before deleting an item
    // e.preventDefault();
    var route = $(button).attr('data-route');
    swal({
      title: "تحذير",
      text: "Are you sure you want to delete this item?",
      icon: "warning",
      buttons: ["الغاء", "حذف"],
      dangerMode: true,
    }).then((value) => {
        if (value) {
            $.ajax({
              url: route,
              type: 'DELETE',
              success: function(result) {
                  if (result == 1) {
                    $(button).parents('tr').remove();
                      new Noty({
                        type: "success",
                        text: "<strong>Item Deleted</strong><br>The item has been deleted successfully."
                      }).show();

                      // Hide the modal, if any
                      $('.modal').modal('hide');
                  } else {
                      // if the result is an array, it means
                      // we have notification bubbles to show
                        if (result instanceof Object) {
                            // trigger one or more bubble notifications
                            Object.entries(result).forEach(function(entry, index) {
                              var type = entry[0];
                              entry[1].forEach(function(message, i) {
                                new Noty({
                                type: type,
                                text: message
                              }).show();
                              });
                            });
                        } else {// Show an error alert
                          swal({
                              title: "NOT deleted",
                            text: "There's been an error. Your item might not have been deleted.",
                              icon: "error",
                              timer: 4000,
                              buttons: false,
                          });
                        }
                  }
              },
              error: function(result) {
                  // Show an alert with the result
                  swal({
                      title: "NOT deleted",
                    text: "There's been an error. Your item might not have been deleted.",
                      icon: "error",
                      timer: 4000,
                      buttons: false,
                  });
              }
          });
        }
    });

  }
}

function decodeHTMLEntities (str) {
    if(str && typeof str === 'string') {
        // strip script/html tags
        str = str.replace(/&lt;/gmi, '<');
        str = str.replace(/&gt;/gmi, '>');
        str = str.replace(/&nbsp;/gmi, ' ');
        str = str.replace(/&amp;/gmi, '&');
        str = str.replace(/&quot;/gmi, '"');
        str = str.replace(/&apos;/gmi, '\'');
        str = str.replace(/&ndash;/gmi, '-');
    }
    return str;
}
function removeTags(str) {

    if ((str===null) || (str===''))
        return false;
    else
        str = str.toString();
        str = str.replace( /(<([^>]+)>)/ig, '');
        str = str.replace(/&lt;/gmi, '<');
        str = str.replace(/&gt;/gmi, '>');
        str = str.replace(/&nbsp;/gmi, '');
        str = str.replace(/&amp;/gmi, '&');
        str = str.replace(/&quot;/gmi, '"');
        str = str.replace(/&apos;/gmi, '\'');
        str = str.replace(/&ndash;/gmi, '-');
        str = str.replace(/[\r\n]/gm, '');
        str = str.replace(/\s\s+/g, ' ');
        str = str.replace(/  +/g, ' ');
        str = str.replace(/\s{2,}/g,' ');
        return str;
}

function readmore(e,type){
    mainobject=$(e).parent();
    td=$(mainobject).parent()
    tdchilds=$(td).children();
    for(i=0;i<tdchilds.length;i++){
        if($(tdchilds[i]).attr('id') == type){
            targetdiv=$(tdchilds[i]);
        }
    }
    $(targetdiv).css('display','block')
    $(mainobject).css('display','none')
}