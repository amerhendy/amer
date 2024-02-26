$.Amer=[];
function loadFileToElement(filename, elementId)
    {
    var xmlHTTP = new XMLHttpRequest();
    try
    {
    xmlHTTP.open("GET", filename+'.xml', false);
    xmlHTTP.send(null);
    }
    catch (e) {
        window.alert("Unable to load the requested file.");
        return;
    }
    return xmlHTTP.responseXML;
    }
    function loadtopics($parent=null){
        if($parent === null){
            myData={fetch:'all',Regulations:Regulation_id};
            $dataSourc=mainlink+"?"+$.param(myData);
        }
        $.ajax({
            url: $dataSourc,
            type: 'GET',
            cache:true,
            async:true,
            crossDomain:true,
            dataType:'json',
            statusCode: {
                200: function() {
                //alert( "تم تحميل الملفات بنجاح" );
                },
                505: function(){
                    alert("لم يتم تحميل البيانات");
                },
                404: function(){
                    alert("لم يتم تحميل البيانات");
                }
            },
            error: function (result) {
                console.log('err:'+JSON.stringify(result));
                dd(result.status);
            },
            success:function(data){
                $.Amer['Regulations_Topics']=data.Regulations_Topics;
                $.Amer['Regulations_Articles']=data.Regulations_Articles;
                $.Amer['Regulations_topic_article']=data.Regulations_topic_article;
            }
        });
    }
    loadtopics();
    var Regulations_Topics=$.Amer['Regulations_Topics'];
    var Regulations_Articles=$.Amer['Regulations_Articles'];
    var Regulations_topic_article=$.Amer['Regulations_topic_article'];
    var sidepaneldisplay=$('#mySidepanel');
    var closebtn=$('#closebtn');
    var madashows=$('#madashows');
    var myOverlay=$('#myOverlay');
    var searchbyword=$('#searchbyword');
    var searchbymoad=$('#searchbymoad');
    var frontpage=$('#frontpage');
    function defaultview(){
        $(closebtn).hide();
        $(sidepaneldisplay).hide();
        $(searchbyword).hide();
        $(searchbymoad).hide();
        $(myOverlay).hide();
        $(madashows).hide();
    }
    function openNav() {
        setlastview();
        $(sidepaneldisplay).show();
        $(closebtn).show();
        $(myOverlay).hide();
        $(searchbyword).hide();
        $(searchbymoad).hide();
        $(madashows).hide();
    }
    function closeNav() {  
        $(sidepaneldisplay).hide();
        $(closebtn).hide();
        $(myOverlay).hide();
        $(searchbyword).hide();
        $(searchbymoad).hide();
        $(madashows).hide();
        returntolastview();
    }
    function openSearch() {
        setlastview();
        closeNav();
        $(closebtn).show();
        $(myOverlay).show();
        $(searchbyword).show();
        $(madashows).hide();
    }
    function openmoadSearch(){
        setlastview();
        closeNav();
        $(closebtn).show();
        $(myOverlay).show();
        $(searchbymoad).show();
        $(madashows).hide();
    }
    function filterFunction(){
        q=$('#myInput').val();
        size = q.length;
        if (q == null || q == "")
        {
            document.getElementById("searchresult").innerHTML= "من فضلك ادخل كلمة للبحث";
            return false;
        }
        $('#searchresult').html("");
        var x = $.Amer["Regulations_Articles"];
        for (i = 1; i <x.length; i++) {
            mada_number=x[i]["number"];
            mada_text=x[i]["text"];
            mada_text=removeTags(mada_text);
            mada_text=mada_text.trim();
            let index = mada_text.indexOf(q);
            if(index !== -1){
              start=index-10;
              end=index+10;
              resulttext=mada_text.substr(start,end);
              resultmada=mada_number;
              id=x[i]['id'];
              if(id !== null)
              {
                searchtml='<li class="list-group-item" id="madasearchresult" onclick="seemada(['+id+'],\'single\');" data-id="'+id+'">';
                  searchtml+='<span class="float-right">مادة رقم ('+mada_number+'): </span>'
                  searchtml+='<span class="float-center">'+resulttext+'</span>'
                //searchtml+='<li class="list-group-item" id="madasearchresult" onclick="seemada('+x[i].getAttribute('id')+',\'single\');closeSearch();">'+resulttext+'</li>';
                searchtml+='</li>';
              }
              $('#searchresult').append(searchtml);
            }
        }
        
    }
  function preparemoadforsearch()
    {
        var Articles = $.Amer["Regulations_Articles"];
        seleect='<select name="searchmadaselect" id="searchmadaselect">'
        for (i = 0; i <Articles.length; i++) {
            $id=Articles[i]['id']
            mada_number=Articles[i]["number"];
        if($id !== null){
            seleect+='<option value="'+$id+'">'+mada_number+'</option>'
        }
    }
    seleect+='</select>'
    $('#searchselectdiv').html(seleect)
    }
    function loadbyTopic (id){
        var wantedmoad=new Array();
        $('#madashows').html('');
        var x = $.Amer['Regulations_topic_article'];
        for (i = 0; i <x.length; i++) {
            Topic_id=x[i]["Topic_id"]
            Article_id=x[i]["Article_id"];
            if(Topic_id == id){
                wantedmoad.push(Article_id);
            }
        }
        wantedmoad = [...new Set(wantedmoad)];
        seemada(wantedmoad,'list');
    }
    function seemadabysearch(){
        seemada([$('#searchmadaselect').val()],'single');
    }
    function seemada(ids=[],mode=null){
        defaultview();
        if(ids=='frontpage'){
            $(frontpage).show();
            $(madashows).hide();
            return;
        }
        $(frontpage).hide();
        $(madashows).show();
        var xmlDoc = Regulations_Articles;
        var x = $.Amer["Regulations_Articles"];
        if(ids == 'all'){
            $(madashows).html('');
            var newids=new Array();
            for (i = 0; i <x.length; i++) {
                    id=x[i]['id'];
                    newids.push(id);
                    mada_number=x[i]["number"];
                    mada_text=x[i]["text"];
                    if(x[i]["mp3"].length !==0)
                    {audio=x[i]["mp3"];}else{audio=null;}
                    audio=null;
                    mada_text = decodeHTMLEntities(mada_text);
                    res=seemadahtml(id,mada_number,mada_text,audio);
                    if(id !== null){$(madashows).append(res);}
            }
            
            set_tags(newids);
        }else{
        var ids=ids.map(function(x){return parseInt(x);});
        
        for (i = 0; i <x.length; i++) {
                if(ids.includes(parseInt(x[i]['id']))){
                    id=x[i]['id'];
                    mada_number=x[i]["number"];
                    mada_text=x[i]["text"];
                    if(x[i]["mp3"].length !==0)
                    {audio=x[i]["mp3"];}else{audio=null;}
                    mada_text = decodeHTMLEntities(mada_text);
                    res=seemadahtml(id,mada_number,mada_text,audio);
                    if(mode == 'list'){
                        $(madashows).append(res);
                    }else{
                        $(madashows).html(res);
                    }
                }
        }
        
        set_tags(ids);
    }
    }
    function seemadahtml(id,mada_number,mada_text,audio){
        html='';
        html+='<div mada_id="'+id+'" class="border">';
            html+='<div class="row align-items-center text-white bg-purple rounded shadow-sm btn btn-sm btn-primary" onclick="seemada(['+id+'])" id="madaName">'+
            '<div class="col-sm-8">';
            if(mada_number.search('جدول') == -1){
                html+='<h1 class="h4 mb-0 text-white lh-1">مادة رقم (<span id="madanumber" mada_id="'+id+'">'+mada_number+'</span>)</h4>';
            }else{
                html+='<h1 class="h4 mb-0 text-white lh-1"><span id="madanumber" mada_id="'+id+'">'+mada_number+'</span></h4>';
            }
            html+='</div>'+
        '<div class="col-sm-2"><i class="fa fa-print" aria-hidden="true" onclick="PrintElem(\'mada\','+id+')"></i></div>'+
            '</div>';
            
            html+='<div class="row  text-right justify-content-center float-right d-flex flex-row justify-content-end" role="group"  style="text-align: center;" id="loadtags" mada_id='+id+'></div>'+
                '<div class="text-body-secondary pt-3" id="ReadMada"  style="text-align: right;" mada_id="'+id+'">'+mada_text+'</div>';
                if(audio !== null){
                html+='<div class="rounded shadow-sm">';
                html+='<audio style="width:100%;border:5px solid lightsalmon;border-radius: 50px;" controls mada_id="'+id+'"><source src="'+audio+'" type="audio/mpeg"></audio>';
                html+='</div>';
                }
                html+='</div>';
        return html;
    }
    function set_tags(ids){
        var loadtopics=new Array( );
        var ids=ids.map(function(x){return parseInt(x);});
        var x = $.Amer['Regulations_topic_article'];
        for (i = 0; i <x.length; i++) {
            Article_id=parseInt(x[i]["Article_id"]);
            Topic_id=parseInt(x[i]["Topic_id"]);
          if(ids.includes(Article_id)){
            loadtopics.push({Article_id,Topic_id});
            }
        }
        load_topics(loadtopics);
    }
    function load_topics(topics_id,text_id=null){
    var x = $.Amer['Regulations_Topics'];
    for (i = 0; i <x.length; i++) {
        dbid=parseInt(x[i]['id']);

        $.each(topics_id, function (index, value) {
            if(dbid == parseInt(value['Topic_id'])){
            const arr = ['secondary', 'success','danger','warning','info']
            const index = Math.floor(Math.random() * arr.length);	// 2
            text=x[i]["text"];
            htmltag="<div class='col-sm btn btn-sm btn-"+arr[index]+"' onclick='loadbyTopic("+dbid+")' role='link' style=''>";
            htmltag+="<span class=''>"+text+"</span> ";
            htmltag+='</div>';  
            var targethtml=$('#loadtags[mada_id="'+value['Article_id']+'"]');
            $(targethtml).append(htmltag);
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
            str = str.replace(/&nbsp;/gmi, ' ');
            str = str.replace(/&amp;/gmi, '&');
            str = str.replace(/&quot;/gmi, '"');
            str = str.replace(/&apos;/gmi, '\'');
            str = str.replace(/&ndash;/gmi, '-');
            str = str.replace(/[\r\n]/gm, '');
            return str;
    }
    
    function setlastview(){
        if($(frontpage).css('display') != 'none'){
            $('#lastview').val('frontpage');
        }
        if($(madashows).css('display') != 'none'){
            $('#lastview').val('madashows');
        }
    }
    function returntolastview(){
        if($('#lastview').val() == '' || $('#lastview').val() == 'frontpage'){$(frontpage).show();}
        if($('#lastview').val() == 'madashows'){$(madashows).show();}
    }
    function ihide(elementId){
        element=$('#'+elementId);
        if($(elementId).css('display') == 'none'){$(elementId).hide();}
    }
    function ishow(elementId){
        element=$('#'+elementId);
        if($(elementId).css('display') == 'block'){$(elementId).show('slow');}
    }
    function createmenu(father=null){
        var Topics = $.Amer['Regulations_Topics'];
        arr=new Array();
        for (i = 0; i < Topics.length; i++) {
        if(Topics[i]['id'] !== null){
            arr[i]={'ID':Topics[i]['id'],'Caption':Topics[i]["text"],'ParentID':Topics[i]["father"]};
        }
        }
        ul=$("#mySidepanel ul");
        li=$(ul).children();
        $(li[1]).html(buildNavigation(arr));
    }
    function buildNavigation(items, parent = 0) {
    var next = function (items, parent) {
    return items.filter(function (item) {
    return (item.ParentID == parent);
    })
    }
    var output = '<ul class="list-group">';
    var subItems = next(items, parent)
    
    for (var key in subItems) {
    output += '<li class="list-group-item " style="margin:0px;padding-top:0px;padding-bottom:0px">';
    output += '<span onclick="loadbyTopic('+subItems[key].ID+');" role="link">'+subItems[key].Caption+'</span>';
    var subItems2 = next(items, subItems[key].ID)
    if (subItems2) {
    output += buildNavigation(items, subItems[key].ID);
    }
    
    output += '</li>';
    }
    output += '</ul>';
    return output;
    }
    function PrintElem(type,id=null)
{
    if(type == 'mada'){
        data=$('#ReadMada[mada_id='+id+']').html();
    }
    if(type == 'all'){
        data=$('#madashows').html();
    }
    dd(data);
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    var html=`
    <html lang="ar-eg">
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="`+websitelink+`/css/printpage.css" rel="stylesheet" />
    <link href="`+websitelink+`/css/bootstrap/bootstrap.min.css" rel="stylesheet" />
    <link href="`+websitelink+`/css/bootstrap/bootstrap.rtl.min.css" rel="stylesheet" />
    <link href="`+websitelink+`/css/bootstrap/bootstrap-grid.min.css" rel="stylesheet" />
    <link href="`+websitelink+`/css/bootstrap/bootstrap-reboot.min.css" rel="stylesheet" />
    <link href="`+websitelink+`/css/bootstrap/bootstrap-utilities.min.css" rel="stylesheet" />
    <link href="`+websitelink+`/css/awesom/all.min.css" rel="stylesheet" />
    <style>
        
        </style>
    </head>
    <body>
    <page size="A4">
    `+data+`
    </page>
    </body>
    </html>
    `;
    //mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    //mywindow.document.write('</head><body >');
    //mywindow.document.write('<h1>' + document.title  + '</h1>');
    //mywindow.document.write(document.getElementById(elem).innerHTML);
    //mywindow.document.write('</body></html>');
    mywindow.document.write(html);
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    //mywindow.close();

    return true;
}
$( document ).ajaxComplete(function( event,request, settings ) {
    $('#openNav').on('click',function(){openNav();});
    $('#openSearch').on('click',function(){openSearch();});
    $('#closebtn').on('click',function(){closeNav();});
    $('#openmoadSearch').on('click',function(){openmoadSearch();});
    $('#myInput').on('keyup',function(){filterFunction()});
    $('body').append('<input type="hidden" name="lastview" id="lastview">');
    //create side pannel
    createmenu(0);
    //defaultview
    defaultview();
    seemada('frontpage');
    preparemoadforsearch();
});