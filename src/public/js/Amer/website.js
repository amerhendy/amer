$(function() {
//set functions
    jQuery.fn.id = function() {
        return this.attr('id');
    };
    jQuery.fn.class = function() {
        return this.attr('class');
    };
    jQuery.fn.name = function() {
        return this.attr('name');
    };
    jQuery.fn.required = function() {
        return this.attr('required') ?? false;
    };
    jQuery.fn.type = function() {
        return this.attr('type') ?? false;
    };
    checkOnlineStatus = async () => {
        try {
          const online = await fetch(api,{
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Vary':'Authorization',
                'Authorization':JSON.parse(localStorage.getItem("clientInfo"))['token']
              }
          });
          return online.status >= 200 && online.status < 300; // either true or false
        } catch (err) {
          return false; // definitely offline
        }
      };
    updateOnlineStatus=(type=null,result=null)=>{
        const overlydiv=$('#overlay');
        const overlytext=$('#overlytext');
        if(type == 'server'){
            if(result == false){
                view_noty('error',"Server Connection Lost");
            }else{
                view_noty('success',"Server Connection Lost");
            }
            
        }else{
            if(navigator.onLine === true){
                view_noty('success',"Interner Connection restore");
            }else{
                view_noty('error',"Interner Connection Lost");
                //$(overlydiv).show();
            }
        }
    }
    UnixTime=(date,method)=>{
        if(method == 'to'){
            return date.getTime()/1000
        }else{
            return new Date(date * 1000);
        }
    }
    view_noty=(type='warning', val)=>{
        new Noty({
            type: type,
            text: val
        }).show();
    }
    generateUUID=()=>{
        return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c =>
            (+c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> +c / 4).toString(16)
          );
    }
    slugify=(str)=>{
        str = str.replace(/^\s+|\s+$/g, ''); // trim leading/trailing white space
        str = str.toLowerCase(); // convert string to lowercase
        str = str.replace(/[^a-z0-9 -]/g, '') // remove any non-alphanumeric characters
                 .replace(/\s+/g, '-') // replace spaces with hyphens
                 .replace(/-+/g, '-'); // remove consecutive hyphens
        return str;
      };
    generatePassword=(passwordlength=null,options=null)=>{
        /*
        generatePassword(12,[0,1,2,3])
        options:
        0: letters only
        1:numbers
        2:symboles
        
         */
        if(passwordlength === null){var passwordlength = 8;}
        var charset = new Array();
        if(options == null){
            options=[0,1];
        }
        const shuffle = str => [...str].sort(()=>Math.random()-.5).join('');
        if(options !== null){
            if(options.includes(0)){
                charset.push(shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"));
            }
            if(options.includes(1)){
                charset.push(shuffle("0123456789"));
            }
            if(options.includes(2)){
                charset.push(shuffle("~!#$%^&*()_+-=\|][}{'\";:/?\\>.<,"));
            }
        }
        charset=shuffle(charset.join(''));
        var retVal = "";
        for (var i = 0, n = charset.length; i < passwordlength; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        return retVal;
    }
    insertAfter=(referenceNode, newNode)=>{
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }
    removeElement=(elementId)=>{
        // Removes an element from the document
        var element = document.getElementById(elementId);
        element.parentNode.removeChild(element);
    }
    showerror=(title=null, text=null, type = null)=>{
        if (type === null) { type = 'error'; }
        Swal.fire({
                title: '<strong>' + title + '</strong>',
                icon: type,
                html: text,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: true,
                inputAutoFocus:true,
                confirmButtonText: 'اغلاق',
            });
            //$('#trojanmodel .modal-dialog .modal-content .modal-header .modal-title').text(title)
            //$('#trojanmodel .modal-dialog .modal-content .modal-body .container-fluid .row .col').html(text)
            //$('#trojanmodel').modal('show');
    }
    setspan=(clss, value, order, tis)=>{
        if (tis === 'undefined') {
            tis = '/';
        }
        $(clss).each(function() {
            var str = '';
            $a = Array.isArray(value);
            if ($a === false) {
    
                $(this).html(value);
            } else {
                value.forEach(function(item, index) {
                    if (order) {
                        if(typeof tis == "string" && tis.indexOf('li') > -1){
                            str += tis + item[order] + '</li>';
                        } else {
                            str += item[order] + ' ' + tis + ' ';
                        }
    
                    } else {
    
                        if (tis.include('li')) { alert('d'); } else {
                            str += item + ' ' + tis + ' ';
                        }
                    }
                });
                str = str.substring(0, str.length - 2);
                $(this).html(str);
            }
        });
    }
    getcode=(lol)=>{
        if (typeof lol === 'undefined') {
            var encodedurl = websitelink + '/tools/code?url=' + encodeURIComponent(document.URL);
        } else {
            var encodedurl = websitelink + '/tools/code?url=' + encodeURIComponent(lol);
        }
        //let params = 'scrollbars=no,resizable=yes,status=no,location=yes,toolbar=no,menubar=no,width=500,height=500,left=-1000,top=-1000';
        //let params='';
        //newwindow=window.open(encodedurl,'popUpWindow',params);
        //if (window.focus) {newwindow.focus()}
        return false;
    
    }
    goback=()=>{
        $('#trojanmodel').modal('hide');
    }
    addScript=(url)=>{
        var script = document.createElement('script');
        script.type = "text/javascript";
        script.src = url;
        document.getElementsByTagName('body')[0].appendChild(script);
    }
    remove_html_tags=(str)=>{
        if ((str === null) || (str === null)) {
            return false;
        } else {
            str = str.toString();
            return str.replace(/<[^>]*>/g, '');
        }
    }
    popitup=(url, name)=>{
        newwindow = window.open(url, name, 'channelmode=yes,directories=no,fullscreen=yes,location=no,menubar=no,status=yes,height=500,width=500', false);
        if (window.focus) { newwindow.focus() }
        return false;
    }
    dd=(val)=>{
        return console.log(val);
    }
    msgbox=(val)=>{
        alert(val);
    }
    ln=()=>{
        var e = new Error();
        if (!e.stack) try {
          // IE requires the Error to actually be throw or else the Error's 'stack'
          // property is undefined.
          throw e;
        } catch (e) {
          if (!e.stack) {
            return 0; // IE < 10, likely
          }
        }
        var stack = e.stack.toString().split(/\r\n|\n/);
        // We want our caller's frame. It's index into |stack| depends on the
        // browser and browser version, so we need to search for the second frame:
        var frameRE = /:(\d+):(?:\d+)[^\d]*$/;
        do {
          var frame = stack.shift();
        } while (!frameRE.exec(frame) && stack.length);
        return frameRE.exec(stack.shift())[1];
      }
      /* -- Integer -- */
    isNumeric=(str)=>{
        if(typeof str === 'number'){return true;}
        if (typeof str != "string") return false // we only process strings!  
        return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
               !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
    }
    isFloat=(value)=>{
        if (
          typeof value === 'number' &&
          !Number.isNaN(value) &&
          !Number.isInteger(value)
        ) {
          return true;
        }
      
        return false;
    }
    /* -- String -- */
    split_text=(text, vars)=>{
        return text.split(vars);
    }
    trim=(id)=>{
        if (id != null)
            id.value = id.value.toString().replace(/^\s+|\s+$/g, "");
    }
    replacestr=(str,$replace)=>{
        $len=(str.match(/\?/g) || []).length;
        $find =Array($len);
        $find.fill('?');
        $replace=Object.values($replace)
        String.prototype.replaceArray = function(find, replace) {
        var replaceString = this;
        for (var i = 0; i < find.length; i++) {
            replaceString = replaceString.replace(find[i], replace[i]);
        }
        return replaceString;
    };
    var textarea = str;
    var find = $find;
    var replace = $replace;
    textarea = textarea.replaceArray(find, replace);
        return textarea
    }
    replacejstrans=(text,rep)=>{
        $.each(rep,function($k,$v){
            text=text.replace($k,$v);
        });
        return text;
    }
    startwith=(str,searchString, position=0)=>{
        if(position == 0){
            return str.startsWith(searchString);
        }else{
            return str.startsWith(searchString,position);
        }
    }
    /* -- Array -- */
    compareArrays = (a, b) => {
        if (a.length !== b.length) return false;
        else {
          // Comparing each element of your array
          for (var i = 0; i < a.length; i++) {
            if (a[i] !== b[i]) {
              return false;
            }
          }
          return true;
        }
    };
    //Remove negative values from array
    ra=(x)=>{
        while (x.length && x[x.length - 1] < 0) {
            x.pop();
        }
        for (var i = x.length - 1; i >= 0; i--) {
            if (x[i] < 0) {
    
                x[i] = x[x.length - 1];
                x.pop();
            }
        }
        return x;
    }
    ///check if value in array
    in_array=(arr,value)=>{
        return arr.includes(value);
    }
    ///check if multiple in array
    multipleInArray=(arr, values)=>{
        return values.every(value => {
          return arr.includes(value);
        });
    }
    // find index in array
    array_search=(arr,val)=>{
        const isEqualNumber = (element) => element == val;
        return arr.findIndex(isEqualNumber);
    }
    //find index in multidimention array
    MultidimentionsArray_search=(arr,key,val)=>{
        if(is_array(arr) === false){return false;}
        var index;
        $.each(arr,function(k,v){
            if(v[key] == val){index = k;}
        });
        if(index){return index;}else{
            return false;
        }
        return index;
    }
    //check if is array
    is_array=(arr)=>{
        return Array.isArray(arr);
    }
    //unset element in array
    unsetArrayelement=(myArray,value)=>{
        var key=myArray.indexOf(value);
        for (var key in myArray) {
            if (myArray[key] == value) {
                myArray.splice(key, 1);
            }
        }
        return myArray;
    }
    //This will sort your array
    //array.sort(SortByName);
    SortBy=(field)=>{
        return function(a,b){
            return (a[field] > b[field]) - (a[field] < b[field])
            //var aName = a.name.toLowerCase();
            //var bName = b.name.toLowerCase(); 
            //return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
        }
      }
    removeDuplicates=(arr)=>{
        return [...new Set(arr)];
    }
    removeMultipleDuplicates=(arr,key1=null,key2=null)=>{
        var result = arr.reduce(function(memo, e1){
            var matches = memo.filter(function(e2){
            return e1[key1] == e2[key1] && e1[key2] == e2[key2]
        })
        if (matches.length == 0)
            memo.push(e1)
            return memo;
        }, [])
        return result;
    }
    /* -- object --*/
    /////convert multidimention object to single array
    objToSingleArray=(variable)=>{
        if(!exists(variable)){return {'st':'error',line:ln()};}
        var arrToConvert =Object.values(variable);
        var newArr = [];
        for(var i = 0; i < arrToConvert.length; i++)
        {
            newArr = newArr.concat(arrToConvert[i]);
        }
        return newArr;
    }
    exists=(variable)=>{
        return (typeof variable === 'undefined') ? false : true
    }
    IsValidJSONString=(str)=>{
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
    isJson=(str)=>{
        return IsValidJSONString(str);
    }
    isObjext=(obj)=>{
        if(typeof obj === 'object' && obj !== null && obj !== undefined){
            return true;
        }else{
            return false;
        }
    }
    isEmpty=(obj)=>{
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                return false;
            }
        }
    
        return JSON.stringify(obj) === JSON.stringify({});
    }
    object_keys=(obj)=>{
        if(isObjext(obj) == false){return false;}
        return Object.keys(obj);
    }
    objkey_exists=(obj,key)=>{
        return key in obj
    }
    objValueArr=(arr,selected)=>{
        var newArr=new Array();
        Object.keys(arr).forEach(function(key, index) {
            newArr.push(arr[index][selected]);
          });
          return newArr;
    }
    getKeyByValue=(Obj, value )=>{
        results=new Array();
        for( var prop in Obj ) {
            if( Obj.hasOwnProperty( prop ) ) {
                 if( Obj[ prop ] === value )
                    results.push(prop);
                     //return prop;
            }
        }
        return results;
    }
    objArrsKeyByArrKey=(obj,key,selected)=>{
        var wanted="";
        $.each(obj,function(k,v){
            if(isObjext(v)){
                if(objkey_exists(v,key)){
                    if(v[key] == selected){
                        wanted=k;
                    }
                }
            }
        })
        if(wanted !== ''){
            return wanted;
        }else{
            return false;
        }
    }
    /* -- html -- */
    //consoleText(['موضوع 1 ', 'مضوع 2'], 'text',);
    consoleText=(words, id, colors)=>{
        if (colors === undefined) colors = ['#337ab7'];
        var visible = true;
        var con = document.getElementById('console');
        var letterCount = 1;
        var x = 1;
        var waiting = false;
        var target = document.getElementById(id)
        target.setAttribute('style', 'color:' + colors[0])
        window.setInterval(function() {
            if (letterCount === 0 && waiting === false) {
                waiting = true;
                target.innerHTML = words[0].substring(0, letterCount)
                window.setTimeout(function() {
                    var usedColor = colors.shift();
                    colors.push(usedColor);
                    var usedWord = words.shift();
                    words.push(usedWord);
                    x = 1;
                    target.setAttribute('style', 'color:' + colors[0])
                    letterCount += x;
                    waiting = false;
                }, 100)
    
            } else if (letterCount === words[0].length + 1 && waiting === false) {
                waiting = true;
                window.setTimeout(function() {
                    x = -1;
                    letterCount += x;
                    waiting = false;
                }, 100)
            } else if (waiting === false) {
                target.innerHTML = words[0].substring(0, letterCount)
                letterCount += x;
            }
        }, 120)
    }
    limitText=(data,number)=>{
        var maintinanceText,showtext,sourceText;
        if(data == null){
            return '-';
        }
        if(Array.isArray(data)){
            if(data.length > 0){
                maintinanceText=data.join(' - ');
                sourceText=data.join(' - ');
            }
        }else{
            sourceText=maintinanceText=data;
        }
        
        if(maintinanceText.length > number){
            if(Array.isArray(data)){
    
            }
            var showtext=`<span data='`+sourceText+`' id='`+generatePassword(12,[0])+`' onclick='readmoreTable(this)' >`+maintinanceText.slice(0,number)+`...</span>`;
        }else{
            showtext=sourceText;
        }
        return showtext;
    }
    readmoreTable=(e)=>{
        showerror($(e).html(),$(e).attr('data'),'info');
    }
    loader_div=(style='')=>{
        html = `<div id="loader" class="container-fluid d-flex justify-content-center full-width-div" style="${style}">
                    <div class="my-auto">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
        `;
        $('body').prepend(html);
    }
    remove_loader_div=()=>{
        $('#loader').remove();
    }
    setOverlyDiv=()=>{
        const overlydiv=$('<div id="overlay"></div>');
        const overlystyle=`position: fixed;display: none;width: 100%;height: 100%;top: 0;left: 0;right: 0;bottom: 0;background-color: rgba(1,1,1,0.5);z-index: 9999;cursor: pointer`;
        $(overlydiv).attr('style',overlystyle);
        var overlytext=`<div id="overlytext" style="position: absolute;top: 50%;left: 50%;font-size: 50px;color: black;background-color:white;width:100%;text-align:center;transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);"></div>`;
        //$(overlytext).attr('style',``);
        
        $(overlydiv).html($(overlytext))
        $('body').append($(overlydiv));
    }
    (function(old) {
        $.fn.attr = function() {
          if(arguments.length === 0) {
            if(this.length === 0) {
              return null;
            }
      
            var obj = {};
            $.each(this[0].attributes, function() {
              if(this.specified) {
                obj[this.name] = this.value;
              }
            });
            return obj;
          }
      
          return old.apply(this, arguments);
        };
      })($.fn.attr);
      const addStyle = (() => {
        const style = document.createElement('style');
        document.head.append(style);
        return (styleString) => style.textContent = styleString;
      })();
    setpagefooter=()=>{
        var newfooterd = '<!-- website.js --><div class="text-center py-3" id="nodeFooter"></div><!-- website.js -->';
        var pagefooters = document.getElementsByClassName('page-footer');
        if(pagefooters.length == 0){
            return;
        }    
        if (pagefooters.length == 1) {
            pagefooters[0].innerHTML += newfooterd;
        } else {
            pagefooters[pagefooters.length - 1].innerHTML += newfooterd;
        }

        if(sessionStorage.getItem("footer")){
            $('#nodeFooter').html(sessionStorage.getItem("footer"));
            return;
        }
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                empDetails(this,'root');
            }
        };
        xmlhttp.open("GET", websitelink+"js/web.xml", true);
        xmlhttp.send();
    };
    empDetails=(xml)=>{
        let i;
        let xmlDoc =xml.responseXML.getElementsByTagName('root')[0];
        let x = xmlDoc.getElementsByTagName("link");
        var img="";
        for (i = 0; i < x.length; i++) {
            var elem=x[i];
            var href=elem.getElementsByTagName('href')[0].innerHTML;
            var src=elem.getElementsByTagName('src')[0].innerHTML;
            var height=elem.getElementsByTagName('height')[0].innerHTML;
            var width=elem.getElementsByTagName('width')[0].innerHTML;
            img+=`<a href="${href}" target="_blank"><img src="data:image/png;base64,${src}" width="${width}" height="${height}"></a>`;
        }
        sessionStorage.setItem("footer",img);
        $('#nodeFooter').html(img);
    }
    if(window.Amer === undefined){window.Amer={};}
    if (top !== self) top.location.replace(self.location.href);
    $('style').append('.nsscwwbgcolor{background-color:#0a384f;color:#fff;}.bg-gradient{background-image: var(--mdb-gradient);}');
    $('style').append(`
    .loader {
        position: absolute !important;top: 50%;left: 50%;
      width: 60px;
      aspect-ratio: 4;
      background: radial-gradient(closest-side at calc(100%/6) 50%,#000 90%,#0000) 0/75% 100%;
      position: relative;
      animation: l15-0 1s infinite linear;
    }
    .loader::before {
      content:"";
      position: absolute;
      background: inherit;
      clip-path: inset(0 0 0 50%);
      inset: 0;
      animation: l15-1 0.5s infinite linear;
    }
    @keyframes l15-0 { 
        0%,49.99% {transform: scale(1)}
        50%,100%  {transform: scale(-1)} 
    }
    @keyframes l15-1 { 
        0%       {transform: translateX(-37.5%) rotate(0turn)} 
        80%,100% {transform: translateX(-37.5%) rotate(1turn)} 
    }`);
    setpagefooter();
    setOverlyDiv();
    AOS.init();
    $('.counter-count').each(function() {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 5000,
            easing: 'swing',
            step: function(now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        updateOnlineStatus();
        window.addEventListener('online',  updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
    });
    setInterval(async () => {
        const result = await checkOnlineStatus();
        updateOnlineStatus('server',result)
      }, 60000);
});

    
      
      
    
    //pagefooters[1].innerHTML += newfooterd;
    