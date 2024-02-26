export function PrintDiv(elem,Title=null,style=null,css=null)
{
    var hasChildDiv = document.getElementById(elem).querySelector("canvas");
    
if (hasChildDiv !== null) {
    var Obj=hasChildDiv;
    console.log(Obj.toDataURL());
    var img=Obj.toDataURL("image/png");
    var str = '<img style="display: block; margin: 0 auto;" src="'+img+'"/>';
    
    var tmpObj=document.createElement("div");
        tmpObj.innerHTML='<!--THIS DATA SHOULD BE REPLACED-->';
        var ObjParent=Obj.parentNode; //Okey, element should be parented
        ObjParent.replaceChild(tmpObj,Obj); //here we placing our temporary data instead of our target, so we can find it then and replace it into whatever we want to replace to
        ObjParent.innerHTML=ObjParent.innerHTML.replace('<div><!--THIS DATA SHOULD BE REPLACED--></div>',str);
}
    console.log($('#'.element));
    var cssfiles=new Array();
    if(Title == null){
        Title=document.title;
    }
    if(IsValidJSONString(css)){
        css=JSON.parse(css);
        css.forEach(element => {
            if(typeof(element) !== undefined)
            {cssfiles.push('<link href="'+element+'" rel="stylesheet" type="text/css"  media="all"/>');}
        });
    }
    var html=`
    <html dir=rtl language=ar-eg>
        <head>
            <title>`+Title+`</title>
            `+cssfiles.join('')+`
        </head>
        <body>
            <h1 class="border">`+Title+`</h1>
            `+document.getElementById(elem).innerHTML+`
        </body>
    </html>
    `;
    const mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write(html);

    //mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    //mywindow.close();

    return true;
}