<!-- base64-image.blade -->
@php
    if (!is_null(old(square_brackets_to_dots($field['name'])))) {
        $value = old(square_brackets_to_dots($field['name']));
    } elseif(isset($field['src']) && isset($entry)) {
        $value = $entry->find($entry->id)->{$field['src']}();
    } else {
        $value = $field['value'] ?? $field['default'] ?? '';
    }
@endphp
<div class="col-sm">
    <div class="row">
        <div class="col-sm-12 btn-group">
            <div class="btn btn-sm btn-file btn-outline-primary">
                {{ trans('AMER::actions.choose_file') }} 
                <input type="file" accept="image/*" data-handle="uploadImage"  @include(fieldview('inc.attributes'), ['default_class' => 'hide'])>
                <input type="hidden" data-handle="hiddenImage" name="{{ $field['name'] }}" value="{{ $value }}">
            </div>
            @if(isset($field['crop']) && $field['crop'])
            <button class="btn btn-sm" data-handle="rotateLeft" type="button" style="display: none;"><i class="fa fa-rotate-left"></i></button>
            <button class="btn btn-sm" data-handle="rotateRight" type="button" style="display: none;"><i class="fa fa-rotate-right"></i></button>
            <button class="btn btn-sm" data-handle="zoomIn" type="button" style="display: none;"><i class="fa fa-search-plus"></i></button>
            <button class="btn btn-sm" data-handle="zoomOut" type="button" style="display: none;"><i class="fa fa-search-minus"></i></button>
            <button class="btn btn-sm" data-handle="reset" type="button" style="display: none;"><i class="fa fa-times"></i></button>
            @endif
            <button class="btn btn-sm" data-handle="remove" type="button"><i class="fa fa-trash"></i></button>
        </div>
        <div class="col-sm-8"  data-handle="previewArea" style="margin-bottom: 20px;"><img data-handle="mainImage" src=""></div>
        @if(isset($field['crop']) && $field['crop'])
            <div class="col-sm-3" data-handle="previewArea">
                <div class="docs-preview clearfix">
                    <div class="img-preview preview-lg">
                        <img src="" style="display: block; min-width: 0px !important; min-height: 0px !important; max-width: none !important; max-height: none !important; margin-left: -32.875px; margin-top: -18.4922px; transform: none;">
                    </div>
                </div>
            </div>
            @endif
            <input type="hidden" class="hiddenFilename" name="{{ $field['filename'] }}" value="">
    </div>
</div>
    @push('after_styles')
        @loadStyleOnce("js/packages/cropperjs/dist/cropper.min.css")
        @loadOnce('base64style')
        <style>
            .hide {
                display: none;
            }
            .image .btn-group {
                margin-top: 10px;
            }
            img {
                max-width: 100%; /* This rule is very important, please do not ignore this! */
            }
            .img-container, .img-preview {
                width: 100%;
                text-align: center;
            }
            .img-preview {
                float: left;
                margin-right: 10px;
                margin-bottom: 10px;
                overflow: hidden;
            }
            .preview-lg {
                width: 263px;
                height: 148px;
            }

            .btn-file {
                position: relative;
                overflow: hidden;
            }
            .btn-file input[type=file] {
                position: absolute;
                top: 0;
                right: 0;
                min-width: 100%;
                min-height: 100%;
                font-size: 100px;
                text-align: right;
                filter: alpha(opacity=0);
                opacity: 0;
                outline: none;
                background: white;
                cursor: inherit;
                display: block;
            }
        </style>
        @endLoadOnce
    @endpush
    @push('after_scripts')
    @loadScriptOnce('js/packages/cropperjs/dist/cropper.min.js')
    @loadScriptOnce('js/packages/jquery-cropper/dist/jquery-cropper.min.js')
    @loadOnce('bpFieldInitBase64CropperImageElement')
        <script>
            function bpFieldInitBase64CropperImageElement(element) {

                    // Find DOM elements under this form-group element
                    var $mainImage = element.find('[data-handle=mainImage]');
                    var $uploadImage = element.find("[data-handle=uploadImage]");
                    var $hiddenImage = element.find("[data-handle=hiddenImage]");
                    var $hiddenFilename = element.find(".hiddenFilename");
                    var $rotateLeft = element.find("[data-handle=rotateLeft]");
                    var $rotateRight = element.find("[data-handle=rotateRight]");
                    var $zoomIn = element.find("[data-handle=zoomIn]");
                    var $zoomOut = element.find("[data-handle=zoomOut]");
                    var $reset = element.find("[data-handle=reset]");
                    var $remove = element.find("[data-handle=remove]");
                    var $previews = element.find("[data-handle=previewArea]");
                    var options = {
                        viewMode: 2,
                        checkOrientation: false,
                        autoCropArea: 1,
                        responsive: true,
                        preview : element.find('.img-preview'),
                        aspectRatio : element.attr('data-aspectRatio')
                    };
                    var crop = element.attr('data-crop');
                    if (!$hiddenImage.val()){
                        $previews.hide();
                        $remove.hide();
                    }
                    $mainImage.attr('src', $hiddenImage.val());
                    if(crop){

                        $remove.click(function() {
                            $mainImage.cropper("destroy");
                            $mainImage.attr('src','');
                            $hiddenImage.val('');
                            if (filename == "true"){
                                $hiddenFilename.val('removed');
                            }
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
                            $hiddenFilename.val('removed');
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
                        if (/^image\/\w+$/.test(file.type)) {
                            $hiddenFilename.val(file.name);
								fileReader.readAsDataURL(file);
                            fileReader.onload = function () {
                                $uploadImage.val("");
                                $previews.show();
                                if(crop){
                                    $mainImage.cropper(options).cropper("reset", true).cropper("replace", this.result);
                                    // update the hidden input after selecting a new item or cropping
                                    $mainImage.on('ready cropstart cropend', function() {
                                        var imageURL = $mainImage.cropper('getCroppedCanvas').toDataURL(file.type);
                                        $hiddenImage.val(imageURL);
                                        return true;
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
                        }else{
                            new Noty({
                                type: "error",
                                text: "<strong>Please choose an image file</strong><br>The file you've chosen does not look like an image."
                            }).show();
                        }
                    });
                    if(crop) {
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
                    }
            }
        </script>
        @endLoadOnce
    @endpush