function IsValidJSONString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
function setupselect2(vocal){
    return $('select[data-init-function="'+vocal+'"]').select2();
}

function initializeFieldsWithJavascript(container) {
    var selector;
    if (container instanceof jQuery) {
        selector = container;
    } else {
        selector = $(container);
    }
    selector.find("[data-init-function]").each(function () {
        var element = $(this);
        var functionName = element.data('init-function');
        if (typeof window[functionName] === "function") {
            window[functionName](element);
        }
    });
}

function bpFieldInitCropperImageElement(element) {
    // Find DOM elements under this form-group element
    var $mainImage = element.find('[data-handle=mainImage]');
    var $uploadImage = element.find("[data-handle=uploadImage]");
    var $hiddenImage = element.find("[data-handle=hiddenImage]");
    var $rotateLeft = element.find("[data-handle=rotateLeft]");
    var $rotateRight = element.find("[data-handle=rotateRight]");
    var $zoomIn = element.find("[data-handle=zoomIn]");
    var $zoomOut = element.find("[data-handle=zoomOut]");
    var $reset = element.find("[data-handle=reset]");
    var $remove = element.find("[data-handle=remove]");
    var $previews = element.find("[data-handle=previewArea]");
    // Options either global for all image type fields, or use 'data-*' elements for options passed in via the CRUD controller
    var options = {
        viewMode: 2,
        checkOrientation: false,
        autoCropArea: 1,
        responsive: true,
        preview : element.attr('data-preview'),
        aspectRatio : element.attr('data-aspectRatio')
    };
    var crop = element.attr('data-crop');

    // Hide 'Remove' button if there is no image saved
    if (!$mainImage.attr('src')){
        $previews.hide();
        $remove.hide();
    }
    // Initialise hidden form input in case we submit with no change
    $hiddenImage.val($mainImage.attr('src'));


    // Only initialize cropper plugin if crop is set to true
    if(crop){

        $remove.click(function() {
            $mainImage.cropper("destroy");
            $mainImage.attr('src','');
            $hiddenImage.val('');
            $rotateLeft.hide();
            $rotateRight.hide();
            $zoomIn.hide();
            $zoomOut.hide();
            $reset.hide();
            $remove.hide();
            $previews.hide();
        });
    } else {

        $remove.click(function() {
            $mainImage.attr('src','');
            $hiddenImage.val('');
            $remove.hide();
            $previews.hide();
        });
    }

    $uploadImage.change(function() {
        var fileReader = new FileReader(),
            files = this.files,
            file;

        if (!files.length) {
            return;
        }
        file = files[0];

        const maxImageSize = '';
        if(maxImageSize > 0 && file.size > maxImageSize) {

            alert(`Please pick an image smaller than ${maxImageSize} bytes.`);
        } else if (/^image\/\w+$/.test(file.type)) {

            fileReader.readAsDataURL(file);
            fileReader.onload = function () {

                $uploadImage.val("");
                $previews.show();
                if(crop){
                    $mainImage.cropper(options).cropper("reset", true).cropper("replace", this.result);
                    // Override form submit to copy canvas to hidden input before submitting
                    $('form').submit(function() {
                        var imageURL = $mainImage.cropper('getCroppedCanvas').toDataURL(file.type);
                        $hiddenImage.val(imageURL);
                        return true; // return false to cancel form action
                    });
                    $rotateLeft.click(function() {
                        $mainImage.cropper("rotate", 90);
                    });
                    $rotateRight.click(function() {
                        $mainImage.cropper("rotate", -90);
                    });
                    $zoomIn.click(function() {
                        $mainImage.cropper("zoom", 0.1);
                    });
                    $zoomOut.click(function() {
                        $mainImage.cropper("zoom", -0.1);
                    });
                    $reset.click(function() {
                        $mainImage.cropper("reset");
                    });
                    $rotateLeft.show();
                    $rotateRight.show();
                    $zoomIn.show();
                    $zoomOut.show();
                    $reset.show();
                    $remove.show();

                } else {
                    $mainImage.attr('src',this.result);
                    $hiddenImage.val(this.result);
                    $remove.show();
                }
            };
        } else {
            new Noty({
                type: "error",
                text: "<strong>Please choose an image file</strong><br>The file you've chosen does not look like an image."
            }).show();
        }
    });
}
function chairmanselect(element){
    chaimanlink=$('#chairmanselectid').attr('link');
    select_topic(chaimanlink,'chairmanselectid');
}
function select_topic(url,area,old=null){
    if(old == null){}
    weblink=url;
    jQuery.ajax({
        url:weblink,
        dataType: 'json',
        type: 'get',
        success: function(data) {
            $('#demo').html(data);
            //var json = JSON.parse(data);
            nr=$('select#'+area);
            var group='<option></option>';
            $.each(data,function (key,value) {
                group+='<option value="'+value['id']+'"';
                if(old == value['id']){
                    group+=' selected';
                }
                group+='>'+value['title']+'</option>';
            });
            nr.append(group);
        },
        error: function (e,xhr,opt) {
            $('#demo').html("Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
}
function geo(element) {
    geolink=$('#geoid').attr('link');
    select_topic(geolink,'geoid');
}

function alfawatir(element) {
    alfawatirlink=$('#alfawatirid').attr('link');
    select_topic(alfawatirlink,'alfawatirid');
}
function alshakawa(element) {
    alshakawalink=$('#alshakawaid').attr('link');
    select_topic(alshakawalink,'alshakawaid');
}
function about(element) {
    aboutlink=$('#aboutid').attr('link');
    select_topic(aboutlink,'aboutid');
}
function bpFieldInitBrowseMultipleElement(element) {
    var $template = element.find("[data-marker=browse_multiple_template]").html();
    var $list = element.find(".list");
    var $popupButton = element.find(".popup");
    var $clearButton = element.find(".clear");
    var $removeButton = element.find(".remove");
    var $input = element.find('input[data-marker=multipleBrowseInput]');
    var $popupTitle = element.attr('data-popup-title');
    var $onlyMimesArray = element.attr('data-only-mimes');
    var $multiple = element.attr('data-multiple');
    var $sortable = element.attr('sortable');

    if($sortable){
        $list.sortable({
            handle: 'button.move',
            cancel: ''
        });
    }

    element.on('click', 'button.popup', function (event) {
        event.preventDefault();

        var div = $('<div>');
        div.elfinder({
            lang: 'ar',
            customData: {
                _token: 'esGZFTJzn6JXsLvBVnHDPNLNcu10SqRe9LVAcLuA'
            },
            url: 'http://localhost/sinaiwater/public/admin/elfinder/connector',
            soundPath: 'http://localhost/sinaiwater/public/packages/barryvdh/elfinder/sounds',
            dialog: {
                width: 900,
                modal: true,
                title: $popupTitle,
            },
            resizable: false,
            onlyMimes: $onlyMimesArray,
            commandsOptions: {
                getfile: {
                    multiple: $multiple,
                    oncomplete: 'destroy'
                }
            },
            getFileCallback: function (files) {
                if ($multiple) {
                    files.forEach(function (file) {
                        var newInput = $($template);
                        newInput.find('input').val(file.path);
                        $list.append(newInput);
                    });

                    if($sortable){
                        $list.sortable("refresh")
                    }
                } else {
                    $input.val(files.path);
                }

                $.colorbox.close();
            }
        }).elfinder('instance');

        // trigger the reveal modal with elfinder inside
        $.colorbox({
            href: div,
            inline: true,
            width: '80%',
            height: '80%'
        });
    });

    element.on('click', 'button.clear', function (event) {
        event.preventDefault();

        if ($multiple) {
            $input.parents('.input-group').remove();
        } else {
            $input.val('');
        }
    });

    if ($multiple) {
        element.on('click', 'button.remove', function (event) {
            event.preventDefault();
            $(this).parents('.input-group').remove();
        });
    }
}
