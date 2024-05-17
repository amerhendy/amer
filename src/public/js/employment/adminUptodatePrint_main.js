
(function(){
$.ajaxSetup({ cache: true });
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
faileajax=function(e,FN){
    par=$(e).parent();
    ref=$('<i class="fa fa-refresh text-success" aria-hidden="true" data-bs-refresh="'+$(e).attr('id')+'"></i>')
    $(par).append($(ref))
    $(ref).on('click',function(){
        window[FN](e)
    });
};
var URL=api+"employmentReports/PrintForm"
var collectPage=false;
var start=0;
var currentPage=1;
var limit=15;
var total=0;
var clientinfo=JSON.parse(localStorage.getItem('clientInfo'));
SETQRCODE=function(data) {
    lov=$('.SETQRCODE').qrcode({
        mode: 0,
        render: 'canvas',
        minVersion: 1,
        maxVersion: 40,
        ecLevel: 'L',
        left: 0,
        top: 0,
        size: 180,
        fill: '#000',
        background: '#fff',
        radius: 0,
        quiet: 0,
        mSize: 0.1,
        mPosX: 0.5,
        mPosY: 0.5,
        text:data,
    });
}
///////////////////////////////////////////////////////////////
createFaceDownloadsFN=function(person,page){
    if(!person.Downloads){
        return ;
    }
    html='';
    var downloadLinks=person.Downloads;
    if(!downloadLinks.length == 0){
        if(downloadLinks.length == 1){
            html+=`<tr>
                <th colspan=2>`+jstrans['Employment_Reports']['printForm']['actions']['Downloads']+`</th>
                <td colspan="8" style="text-align:left;">`+downloadLinks[0]+`</td>
                <td colspan=4>QRCODE</td>
            <tr>`;
        }else{
            html+=`<tr>
                <th colspan=2 rowspan=3 >`+jstrans['Employment_Reports']['printForm']['actions']['Downloads']+`</th>
                <td colspan="8" style="text-align:left;">`+downloadLinks[0]+`</td>
                <td colspan=4>QRCODE</td>
            </tr>`;
            for(i=1;i<=downloadLinks.length-1;i++){
                html+=`<tr>
                <td colspan="8" style="text-align:left;">`+downloadLinks[i]+`</td>
                <td colspan=4>QRCODE</td>
            </tr>`;
            }
        }
        
    }
    return html;
}
createFaceGrievanceFN=function(person,type,page){
    if(objkey_exists(person,'Grievance') == false){return;}
    if(objkey_exists(person.Grievance,type) == false){return;}
    if(type == 'apply'){
        var lang='AppliedGrievance';
    }else if(type == 'Editorial'){
        var lang='WritingGrievance';
    }else if(type == 'Practical'){
        var lang='PracticalGrievance';
    }
    var emptyresutl={
                    lang:jstrans['Employment_Grievance'][lang],
                    result:jstrans['Employment_Grievance']['GrievanceNotDone'],
                };
    var result=true
    if(objkey_exists(person,'Grievance') == false){var result=false;}
    if(objkey_exists(person['Grievance'],type) == false){result=false;}
    if(person['Grievance'][type].length == 0){var result=false;}
    html="";
    html+=`<tr>
                <th colspan=2>`+jstrans['Employment_Grievance'][lang]+`</th>`;
    if(result == false){
        html+=`<td colspan=12>`+jstrans['Employment_Grievance']['GrievanceNotDone']+`</td>`;
    }else{
        html+=`<td colspan=12>`+jstrans['Employment_Grievance']['GrievanceDone']+`   `+person['Grievance'][type][0]['created_at']+`</td>
        `;
    }
    html+=`</tr>`;
    return html;
    
}
createFaceFN=function(person){
    //set Messages
    $.each(person.Face.lastStage.Message,function(k,v){person.Face.lastStage.Message[k]=remove_html_tags(v);});
    //Annonce_id
    if(person.Face.lastStage.Message == ''){var msg='';}else{msg=jstrans['Employment_Reports']['lastStage']['Message']+`:`+person.Face.lastStage.Message.join('-');}
    var html='';
    var printableData=new Array()
    //////create html colspan and rowspan
    html+=`<tr>
            <th colspan=2>`+jstrans['Employment_StartAnnonces']['plural']+`</th>
            <td colspan=12>`+jstrans['Employment_StartAnnonces']['homepage_annonce_number']+` (`+person.Face.Annonce_id.Number+`) `+jstrans['Employment_StartAnnonces']['homepage_annonce_foryear']+` `+person.Face.Annonce_id.Year+`</td>
    </tr>`;
    html+=`<tr>
            <th colspan=2>`+jstrans['Employment_Jobs']['plural']+`</th>
            <td colspan=12>`+person.Face.Job_id.Mosama_JobNames+`</td>
    </tr>`;
    html+=`<tr>
            <th colspan=2>`+jstrans['Employment_People']['uid']+`</th>
            <td colspan=5>`+person.Face.id+`</td>
            <th colspan=2>`+jstrans['Employment_People']['NID']+`</th>
            <td colspan=5>`+person.Face.NID+`</td>
    </tr>`;
    
    html+=`<tr>
            <th colspan=2>`+jstrans['Employment_People']['FULLname']+`</th>
            <td colspan=12>`+person.Face.Fname+` `+person.Face.Sname+` `+person.Face.Tname+` `+person.Face.Lname+`</td>
    </tr>`;
    html+=`<tr>
            <th colspan=2 rowspan=3>`+jstrans['Employment_People']['Connection']['Connection']+`</th>
            <th colspan=2>`+jstrans['Employment_People']['Connection']['Email']+`</th>
            <td colspan=10>`+person.Face.ConnectEmail+`</td>
    </tr>
    <tr><th colspan=2>`+jstrans['Employment_People']['Connection']['LandLine']+`</th><td colspan=10>`+person.Face.ConnectLandline+`</td></tr>
    <tr><th colspan=2>`+jstrans['Employment_People']['Connection']['Mobile']+`</th><td colspan=10>`+person.Face.ConnectMobile+`</td></tr>
    `;
    html+=`<tr>
            <th colspan=2>`+jstrans['Employment_Health']['Employment_Health']+`</th>
            <td colspan=12>`+person.Face.Health_id+`</td>
    </tr>`;
    html+=`<tr>
            <th colspan=2 rowspan=4>`+jstrans['Employment_Reports']['lastStage']['lastStage']+`</th>
            <th colspan=2>`+jstrans['Employment_Reports']['lastStage']['Name']+`</th>
            <td colspan=10>`+person.Face.lastStage.Text+`</td>
    </tr>
    <tr><th colspan=2>`+jstrans['Employment_Reports']['lastStage']['Result']+`</th><td colspan=10>`+person.Face.lastStage.Result+`</td></tr>
    <tr><th colspan=2>`+jstrans['Employment_Reports']['lastStage']['Message']+`</th><td colspan=10>`+person.Face.lastStage.Message+`</td></tr>
    <tr><th colspan=2>`+jstrans['Employment_Reports']['lastStage']['Date']+`</th><td colspan=10>`+person.Face.lastStage.created_at+`</td></tr>
    `;
    html+=`<tr style="page-break-inside:avoid; page-break-after:always"></tr>`;
    //console.log(jstrans['Employment_People']);
    return html;
}
setBodyLastEntryFN=function(person,page){
    html=``;
    var Title=jstrans['Employment_Reports']['printForm']['actions'][page];
    html+=`<tr><td colspan="12" class="text-center"><h3>`+Title+`</h3></td></tr>`;
    var htmlArray=new Array();
    var annonce_number=person.Face.Annonce_id.Number;
    var annonce_year=person.Face.Annonce_id.Year;
    //console.log(person.Applydata);
        if(page == 'CheckApplyData'){
            var myData=person.Applydata;
            var personId=myData.id;
            var personNid=myData.NID;
            var stageNameTrans=jstrans['Employment_Reports']['ApplyResult'];
            var Message=myData.Stage_id.Message;
            var Result=myData.Stage_id.Result;
            var created_at=myData.Stage_id.created_at;
            var Stage_id=myData.Stage_id.Text;
        }
        if(page == 'LastEntry'){
            var myData=person.LastEntry;
            myData.Stage_id=person.StageList.LastEntry
            var personId=person.Face.id;
            var personNid=person.Face.NID;
            var stageNameTrans=jstrans['Employment_Reports']['ApplyResult'];
            var Message=myData.Stage_id.Message;
            var Result=myData.Stage_id.Result;
            var created_at=myData.Stage_id.created_at;
            var Stage_id=myData.Stage_id.Text;
        }
        //console.log(myData);
        var Mosama_JobNames=myData.Job_id.Mosama_JobNames;
            var JobCode=myData.Job_id.Code;
            var FullName=myData.Fname+` `+ myData.Sname+` `+ myData.Tname+` `+ myData.Lname
            var Sex=myData.Sex;
            var AgeYears=myData.AgeYears;
            var AgeMonths=myData.AgeMonths;
            var AgeDays=myData.AgeDays;
            var BirthDate=myData.BirthDate;
            var bornPlace=myData.BornGov + ` - `+myData.BornCity;
            var LivePlace=myData.LiveGov + ` - `+myData.LiveCity + ` - `+myData.LiveAddress;
            var ConnectMobile=myData.ConnectMobile;
            var ConnectLandline=myData.ConnectLandline;
            var ConnectEmail=myData.ConnectEmail;
            var Employment_Health=myData.Health_id;
            var Employment_MaritalStatus=myData.MaritalStatus_id;
            var Employment_Army=myData.Arm_id
            var Employment_Ama=myData.Ama_id
            var Mosama_Educations=myData.Education_id;
            var EducationYear=myData.EducationYear;
            var Tamin=myData.Tamin;
            var Khebra=myData.Khebra;
            var DriverDegree=myData.DriverDegree;
            var DriverEnd=myData.DriverEnd;
            var DriverStart=myData.DriverStart;
    html+=`<tr>
                <th colspan=2>${jstrans['Employment_People']['uid']}</th>
                <td colspan=4>${personId}</td>
                <th colspan=2>${jstrans['Employment_People']['NID']}</th>
                <td colspan=4>${personNid}</td>
            </tr>`;
    html+=`<tr>
                <th colspan=2>${jstrans['Employment_StartAnnonces']['plural']}</th>
                <td colspan=4>`+jstrans['Employment_StartAnnonces']['homepage_annonce_number']+` (${annonce_number}) `+jstrans['Employment_StartAnnonces']['homepage_annonce_foryear']+` ${annonce_year}</td>
                <th colspan=2>${jstrans['Employment_Jobs']['plural']}</th>
                <td colspan=4>${Mosama_JobNames}<br>`+jstrans['Employment_Jobs']['Code']+` (${JobCode})</td>
            </tr>`;
    html+=`<tr>
                <th colspan=2>${jstrans['Employment_People']['FULLname']}</th>
                <td colspan=4>${FullName}</td>
                <th colspan=2>${jstrans['Employment_People']['Sex']['Sex']}</th>
                <td colspan=4>${Sex}</td>
            </tr>`;
    //age
    html+=`<tr>
                <th colspan=2>${jstrans['Employment_People']['Age']['Age']}</th>
                <td colspan=4>${AgeYears} ${jstrans['Employment_People']['Age']['AgeYears']} - ${AgeMonths} ${jstrans['Employment_People']['Age']['AgeMonths']} - ${AgeDays} ${jstrans['Employment_People']['Age']['AgeDays']}</td>
                <th colspan=2>${jstrans['Employment_People']['BirthDate']}</th>
                <td colspan=4>${BirthDate}</td>
            </tr>`;
    //BirthDate
    html+=`<tr>
                <th colspan=2>${jstrans['Employment_People']['bornPlace']['bornPlace']}</th>
                <td colspan=4>${bornPlace}</td>
                <th colspan=2>${jstrans['Employment_People']['LivePlace']['LivePlace']}</th>
                <td colspan=4>${LivePlace}</td>
            </tr>`;
    //BirthDate
    html+=`<tr>
                <th colspan=2 rowspan=3>${jstrans['Employment_People']['Connection']['Connection']}</th>
                <th colspan=2>${jstrans['Employment_People']['Connection']['Mobile']}</th>
                <td colspan=8>${ConnectMobile}</td>
            </tr>
            <tr>
                <th colspan=2>${jstrans['Employment_People']['Connection']['LandLine']}</th>
                <td colspan=8>${ConnectLandline}</td>
            </tr>
            <tr>
                <th colspan=2>${jstrans['Employment_People']['Connection']['Email']}</th>
                <td colspan=8>${ConnectEmail}</td>
            </tr>
            `;
    // health
    //Ama_id
    //Arm_id
    html+=`<tr>
            <th colspan=2>${jstrans['Employment_Health']['Employment_Health']}</th>
            <td colspan=4>${Employment_Health}</td>
            <th colspan=2>${jstrans['Employment_MaritalStatus']['Employment_MaritalStatus']}</th>
            <td colspan=4>${Employment_MaritalStatus}</td>
        </tr>`;
    html+=`<tr>
        <th colspan=2>${jstrans['Employment_Army']['Employment_Army']}</th>
        <td colspan=4>${Employment_Army}</td>
        <th colspan=2>${jstrans['Employment_Ama']['Employment_Ama']}</th>
        <td colspan=4>${Employment_Ama}</td>
    </tr>`;
    html+=`<tr>
        <th colspan=2>${jstrans['Employment_People']['Mosama_Educations']['Mosama_Educations']}</th>
        <td colspan=4>${Mosama_Educations}</td>
        <th colspan=3>${jstrans['Employment_People']['Mosama_Educations']['year']}</th>
        <td colspan=3>${EducationYear}</td>
    </tr>`;
    html+=`<tr>
        <th colspan=2>${jstrans['Employment_People']['Khebra']['Khebra']}</th>
        <td colspan=4>${Khebra}</td>
        <th colspan=3>${jstrans['Employment_People']['Tamin']['Tamin']}</th>
        <td colspan=3>${Tamin}</td>
    </tr>`;
    if(DriverDegree !== null){
        html+=`<tr>
        <th colspan=2>${jstrans['Employment_People']['Employment_Drivers']['DriverDegree']}</th>
        <td colspan=2>${DriverDegree}</td>
        <th colspan=2>${jstrans['Employment_People']['Employment_Drivers']['DriverStart']}</th>
        <td colspan=2>${DriverStart}</td>
        <th colspan=2>${jstrans['Employment_People']['Employment_Drivers']['DriverEnd']}</th>
        <td colspan=2>${DriverEnd}</td>
    </tr>`;
    }
    if(page == 'CheckApplyData'){
        html+=`<tr>
            <th colspan=2>${stageNameTrans}</th>
            <td colspan=2>${Result}</td>
            <th colspan=2>${jstrans['Employment_Reports']['lastStage']['Date']}</th>
            <td colspan=2>${created_at}</td>
            <th colspan=2>${jstrans['Employment_Reports']['lastStage']['Message']}</th>
            <td colspan=2>${Message}</td>
        </tr>`;
    }
    if(page == 'LastEntry'){
        ////نضع سبب تحوله للتظلم
        html+=`<tr>
                <th colspan=2>${jstrans['Employment_Reports']['lastStage']['Entry']}</th>
                <td colspan=10>
                `+jstrans['Employment_Reports']['lastStage']['Name']+`:`+person.StageList.LastEntry.Text+`<br>`
                +jstrans['Employment_Reports']['lastStage']['Date']+`:`+person.StageList.LastEntry.created_at+`<br>`
                +jstrans['Employment_Reports']['lastStage']['Result']+`:`+person.StageList.LastEntry.Result+`<br>`
                +jstrans['Employment_Reports']['lastStage']['Message']+`:`+person.StageList.LastEntry.Message+`
                </td>
            </tr>
            `;
    }
    if(collectPage == true){
        if(Data.types !== 'faceWfile'){
            html+=(setBodyDegreesFN(person))
            html+=(createBodyDownloadsFN(person))
            html+=(createBodyGrievanceFN(person,'apply'));
            html+=(createBodyGrievanceFN(person,'Editorial'));
            html+=(createBodyGrievanceFN(person,'Practical'));
    }
    }
    
    return html;
}
createBodyDownloadsFN=function(person){
    return createFaceDownloadsFN(person,'body')
}
createBodyGrievanceFN=function(person,type){
    return createFaceGrievanceFN(person,type,'body')
}
createFaceDegreesFN=function(person,page){
    if(!in_array(Data.actions,'Degrees')){return false;}
    html="";
    if(person.Degrees == null){
        collectDegreesEditorial=parseFloat(0);collectDegreesPractical=parseFloat(0);collectDegreesInterview=parseFloat(0);
        writeDegreesEditorial='.....';writeDegreesPractical='....';writeDegreesInterview='....';
    }else{
        if(person.Degrees.Editorial == null){writeDegreesEditorial='.....';collectDegreesEditorial=parseFloat(0);}else{writeDegreesEditorial=collectDegreesEditorial=parseFloat(person.Degrees.Editorial);}
        if(person.Degrees.Practical == null){writeDegreesPractical='....';collectDegreesPractical=parseFloat(0);}else{writeDegreesPractical=collectDegreesPractical=parseFloat(person.Degrees.Practical);}
        if(person.Degrees.Interview == null){writeDegreesInterview='....';collectDegreesInterview=parseFloat(0);}else{writeDegreesInterview=collectDegreesInterview=parseFloat(person.Degrees.Interview);}
    }            
    var totalDegrees=collectDegreesEditorial+collectDegreesPractical+collectDegreesInterview;
    if(page == 'body'){
        html+=`<tr>
            <th colspan=2 rowspan=4>`+jstrans['Employment_Reports']['printForm']['actions']['Degrees']+`</th>
            <th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['DegreeTahriry']+`</th>
            <td colspan=10>`+writeDegreesEditorial+`</td>
            </tr>
            <tr><th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['DegreeAmaly']+`</th><td colspan=10>`+writeDegreesPractical+`</td></tr>
            <tr><th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['DegreeMeeting']+`</th><td colspan=10>`+writeDegreesInterview+`</td></tr>
            <tr><th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['TotalDegrees']+`</th><td colspan=10>`+totalDegrees+`</td></tr>
            `;
    }
    if(page == 'face'){
        html+=`<tr>
            <th colspan=3>`+jstrans['Employment_Reports']['printForm']['actions']['Degrees']+`</th>
            <th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['DegreeTahriry']+`</th>
            <td colspan=2>`+writeDegreesEditorial+`</td>
            <th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['DegreeAmaly']+`</th><td colspan=2>`+writeDegreesPractical+`</td>
            <th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['DegreeMeeting']+`</th><td colspan=2>`+writeDegreesInterview+`</td>
            <th colspan=2>`+jstrans['Employment_Reports']['UpToDateForm']['TotalDegrees']+`</th><td colspan=2>`+totalDegrees+`</td>
            </tr>
            `;
    }
    return html;
}
setBodyDegreesFN=function(person){
    return createFaceDegreesFN(person,'body');
}
setHeaderFN=function(Pages,MyData){
    $.each(Pages,function(k,v){
        var personId=v.id;
        var PageName=v.name;
        person=MyData.filter((Person)=>Person.Face.id==personId);
        var annonce_number=person[0].Face.Annonce_id.Number;
        var annonce_year=person[0].Face.Annonce_id.Year;
        var jobName=person[0].Face.Job_id.Mosama_JobTitles;
        var uid=person[0].Face.id;
        var nid=person[0].Face.NID;
        var serverDate=person[0].PrintDate;
        var HeaderDiv=$('page[data-id='+personId+'][data-for='+PageName+'] .header');
        var annonceNumberDiv=$(HeaderDiv).find('#annonceNmber');
        var annonceNumberDiv=$(HeaderDiv).find('#annonceNmber');
        var annonceYearDiv=$(HeaderDiv).find('#annonceYear');
        var shortJobName=$(HeaderDiv).find('#shortJobName');
        var uidSpan=$(HeaderDiv).find('#Uid');
        var NidSpan=$(HeaderDiv).find('#Nid');
        var DateSpan=$(HeaderDiv).find('#Date');
        var pageidentify=$(HeaderDiv).find('#pageidentify');
        var pageidentifyText=jstrans['Employment_Reports']['printForm']['fileTite'][PageName];
        $(annonceNumberDiv).text(annonce_number);
        $(annonceNumberDiv).text(annonce_number);
        $(annonceYearDiv).text(annonce_year);
        $(shortJobName).text(jobName);
        $(uidSpan).text(uid);
        $(NidSpan).text(nid);
        $(DateSpan).text(serverDate);
        $(pageidentify).text(pageidentifyText)
    });
    return;
    
    
}
createPageFN=function(Pages){
    $.each(Pages,function(k,v){
        var personId=v.id;
        var h=$("#PageTemplate").html();
        var h=$(h).clone();
        var gf=$(h).attr('data-id',personId);
        var gf=$(h).attr('data-for',v.name);
        $('body').append(h);
    })
    if(objkey_exists(Data,'section')){
        if(Data.section == 'Seatings'){
            $('thead').html('');
            $('tfoot').html('');
        }
    }
    console.log(Data);
}
WantedpagesFN=function(data){
    var pages=new Array();
    var insideFace=new Array();
    if(in_array(Data.actions,'Full')){
        Data.actions=[ "LastEntry", "Downloads", "CheckApplyData", "GrievanceApply", "GrievanceEditorial", "GrievancePractical", "Degrees" ]
    }
    if(Data.types == 'faceWfile'){
        pages.push('face');
    }
    
    for(i=0;i<=Data.actions.length-1;i++){
        pages.push(Data.actions[i]);
    }
    var insideFaceArr=['Degrees','GrievancePractical','GrievanceEditorial','GrievanceApply','Downloads'];
    
    if(Data.types == 'faceWfile'){
        $.each(insideFaceArr,function (k,v) {
            if(in_array(Data.actions,v)){
                pages=unsetArrayelement(pages,v);
                pages.push('collect');
            }
        });
    }else{
        
            $.each(insideFaceArr,function (k,v) {
                if(in_array(Data.actions,v)){
                    pages=unsetArrayelement(pages,v);
                    pages.push('collect');
                }
            });
    }
    pages=removeDuplicates(pages);
    $ret=new Array();
    for(i=0;i<=data.data.length-1;i++){
        uid=data.data[i].Face.id;
        pages.forEach(element => {
            $ret.push({id:uid,name:element});
        });
    }
    return $ret;
}
checkWantedFaceFN=function(){
    createFace=false;
    if(Data.actions.length == 1){
        if(in_array(Data.actions,'Degrees')){
            if(Data.types !== 'file'){
                createFace=true;
            }
        }else if(in_array(Data.actions,'Downloads')){
            if(Data.types !== 'file'){
                createFace=true;
            }
        }else if(in_array(Data.actions,'GrievanceApply')){
            if(Data.types !== 'file'){
                createFace=true;
            }
        }else if(in_array(Data.actions,'GrievanceEditorial')){
            if(Data.types !== 'file'){
                createFace=true;
            }
        }else if(in_array(Data.actions,'GrievancePractical')){
            if(Data.types !== 'file'){
                createFace=true;
            }
        }else{
            if(Data.types !== 'file'){
                createFace=true;
            }
        }
        //
    }else{
        if(Data.types == 'file'){
            createFace=false;
            }else{
                createFace=true;
            }
    }
    return createFace;
}

createHtml=function(data){
    var checkWantedFace=checkWantedFaceFN();
    var Wantedpages=WantedpagesFN(data);
    var myData=data.data;
    createPageFN(Wantedpages);
    setHeaderFN(Wantedpages,myData);
    if(checkWantedFace == true){
        $.each(Wantedpages,function(k,v){
            var personId=v.id;
            var PageName=v.name;
            person=myData.filter((Person)=>Person.Face.id==personId);
            var MainDiv=$('page[data-id='+personId+'][data-for="face"] table.pagecontent tbody');
            MainDiv.html(createFaceFN(person[0]));
        });
    }
    //var collectPage=false;
    if(MultidimentionsArray_search(Wantedpages,'name','collect') !== false){
        collectPage=true;
    }
    $.each(Wantedpages,function(k,v){
        var personId=v.id;
        var PageName=v.name;
        person=myData.filter((Person)=>Person.Face.id==personId);
        if(PageName === 'collect'){
            var MainDiv=$('page[data-id='+personId+'][data-for="'+PageName+'"] table.pagecontent tbody');
            MainDiv.append(setBodyDegreesFN(person[0]))
            MainDiv.append(createBodyDownloadsFN(person[0]))
            MainDiv.append(createBodyGrievanceFN(person[0],'apply'));
            MainDiv.append(createBodyGrievanceFN(person[0],'Editorial'));
            MainDiv.append(createBodyGrievanceFN(person[0],'Practical'));
        }
        if(PageName == 'LastEntry'){
            var MainDiv=$('page[data-id='+personId+'][data-for="'+PageName+'"] table.pagecontent tbody');
            MainDiv.append(setBodyLastEntryFN(person[0],PageName,collectPage))
        }
        
        if(PageName == 'CheckApplyData'){
            var MainDiv=$('page[data-id='+personId+'][data-for="'+PageName+'"] table.pagecontent tbody');
            MainDiv.append(setBodyLastEntryFN(person[0],PageName,collectPage))
        }
        
    });
}
window.addEventListener("beforeprint", (event) => {
    $.each(document.getElementsByClassName('pagecontent'),function(k,v){
        v.style.transform = ("scale(85%)");
        v.style.margin = ("0 0 0 -15%");
    })
    $.each($('td'),function(k,v){
        v.style['word-wrap']='normal';
        v.style['display']='table-cell';
    })
    
  });
  // left: 37, up: 38, right: 39, down: 40,
// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
  var keys = { 40: 1, 32: 1, 34: 1,35:1};
function preventDefault(e) {
    e.preventDefault();
  }
  
  function preventDefaultForScrollKeys(e) {
    if (keys[e.keyCode]) {
      preventDefault(e);
      return false;
    }
  }
  var supportsPassive = false;
try {
  window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
    get: function () { supportsPassive = true; } 
  }));
} catch(e) {}

var wheelOpt = supportsPassive ? { passive: false } : false;
var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';

// call this to Disable
function disableScroll() {
  window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
  window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
  window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
  window.addEventListener('keydown', preventDefaultForScrollKeys, false);
}

// call this to Enable
function enableScroll() {
  window.removeEventListener('DOMMouseScroll', preventDefault, false);
  window.removeEventListener(wheelEvent, preventDefault, wheelOpt); 
  window.removeEventListener('touchmove', preventDefault, wheelOpt);
  window.removeEventListener('keydown', preventDefaultForScrollKeys, false);
}
const loaderEl = `<div id="preloader"></div>`;
const hideLoader = () => {
    $('#preloader').remove()
};
const showLoader = () => {
    $('body').prepend(loaderEl);
};
const getQuotes = async (page, limit) => {
    Data.current_page=page;
    const API_URL = URL;
    const settings = {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        //credentials: "same-origin",
        redirect: "manual",
        //referrerPolicy: "no-referrer",
        body: JSON.stringify(Data),
        headers: {
            //Accept: 'application/json',
            "Content-Type": "application/x-www-form-urlencoded",
        //"Accept": "application/json, text/javascript, */*; q=0.01",
        "Content-Type": "application/json",
        'Authorization':clientinfo['token'],
        'Vary':'Authorization',
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest'
        },xhrFields: {				// Uses the jquery-ajax-native plugin for blobs.
            responseType: "blob"
        },
    };
    const response = await fetch(API_URL,settings);
    // handle 404
    if (!response.ok) {
        throw new Error(`An error occurred: ${response.status}`);
    }
    return await response.json();
}
createSeatingSection=function(data){
    
    //Wantedpages=['Face'];
    //createPageFN(Wantedpages);
    //createPage
    $.each(data,function(k,v){
        //$('tbody').append(v);
    });
}
const loadQuotes = async (page, limit) => {    
    // show the loader
    showLoader();

    // 0.5 second later
    setTimeout(async () => {
        try {
            // if having more quotes to fetch
            if (hasMoreQuotes(page, limit, total)) {
                // call the API to get quotes
                const response = await getQuotes(page, limit);
                console.log(response);
                var data=response;
                
                var iframe = $('<iframe>');
                iframe.attr('src','/pdf/yourpdf.pdf?options=first&second=here');
                $('body').hmtl(iframe);
                return;
                if(isObjext(data)){
                    total=data.total;
                    if(objkey_exists(Data,'section')){
                        if(Data.section == 'Seatings'){
                            return createSeatingSection(data.data);
                        }
                    }
                    var ids=new Array();
                    $.each(data['data'],function(k,v){
                        ids.push(v.Face.id);
                    })
                
                    createHtml(data);
                }
                enableScroll()
                total = response.total;
            }
        } catch (error) {
            console.log(error.message);
        } finally {
            hideLoader();
        }
    }, 500);

};
const hasMoreQuotes = (page, limit, total) => {
    
    const startIndex = (page - 1) * limit + 1;
    return total === 0 || startIndex < total;
};
        ///disableScroll()

  window.addEventListener('scroll', () => {
    const {
        scrollTop,
        scrollHeight,
        clientHeight
    } = document.documentElement;
    if (scrollTop + clientHeight >= scrollHeight - 5 &&
        hasMoreQuotes(currentPage, limit, total)) {
        currentPage=currentPage+1;
        loadQuotes(currentPage, limit);
        
        //fetchDataXhr();
    }
}, {
    passive: true
});
loadQuotes(currentPage, limit);
})(jQuery);