<?php
	$multiple='';
	if(isset($field['multiple'])){
		if(($field['multiple'] == "true") || ($field['multiple'] === true)){
			$multiple='multiple';
		}
	}
?>
	{{-- Show the file name and a "Clear" button on EDIT form. --}}
	@if (isset($field['value']))
	@php
		if (is_string($field['value'])) {
			$values = json_decode($field['value'], true) ?? [];
		} else {
			$values = $field['value'];
		}
	@endphp
	@if (count($values))
    <div class="well well-sm existing-file">
    	@foreach($values as $key => $file_path)
    		<div class="file-preview">
    			@if (isset($field['temporary']))
		            <a target="_blank" href="{{ isset($field['disk'])?asset(\Storage::disk($field['disk'])->temporaryUrl($file_path, Carbon\Carbon::now()->addMinutes($field['temporary']))):asset($file_path) }}">{{ $file_path }}</a>
		        @else
		            <a target="_blank" href="{{ isset($field['disk'])?asset(\Storage::disk($field['disk'])->url($file_path)):asset($file_path) }}">{{ $file_path }}</a>
		        @endif
		    	<a href="#" class="btn btn-light btn-sm float-right file-clear-button" title="Clear file" data-filename="{{ $file_path }}"><i class="la la-remove"></i></a>
		    	<div class="clearfix"></div>
	    	</div>
    	@endforeach
    </div>
    @endif
    @endif
	<div class="backstrap-file mt-2">
  <div class="upload__btn-box">
    <label class="upload__btn">
      <p>Upload images</p>
	  <input
			data-number="1"
	        type="file"
	        name="{{ $field['name'] }}[]"
	        value="@if (old(square_brackets_to_dots($field['name']))) old(square_brackets_to_dots($field['name'])) @elseif (isset($field['default'])) $field['default'] @endif"
	        @include(fieldview('inc.attributes'), ['default_class' =>  isset($field['value']) && $field['value']!=null?'upload__inputfile':'upload__inputfile'])
	        {{$multiple}}
			data-max_length="20"
	    >
    </label>
  </div>
  <div class="upload__img-wrap container-fluid"></div>
</div>
@push('after_styles')
@loadOnce('uploadOrLinkstyle')
<style>
	.upload__inputfile {
		width: .1px;
		height: .1px;
		opacity: 0;
		overflow: hidden;
		position: absolute;
		z-index: -1;
  	}
  .upload__btn {
    display: inline-block;
    font-weight: 600;
    color: #fff;
    text-align: center;
    min-width: 116px;
    padding: 5px;
    transition: all .3s ease;
    cursor: pointer;
    border: 2px solid;
    background-color: #4045ba;
    border-color: #4045ba;
    border-radius: 10px;
    line-height: 26px;
    font-size: 14px;
  }
	.upload__img-close {
        width: 24px;
        height: 24px;	
        border-radius: 50%;
		position: absolute;
        background-color: rgba(0, 0, 0, 0.5);
        top: 10px;
        right: 10px;
        text-align: center;
        line-height: 24px;
        z-index: 1;
        cursor: pointer;
	}
	.upload__img-filename{
		
	}
	.upload__img-close:after {
          content: '\2716';
          font-size: 14px;
          color: white;
        }

	.img-bg {
		height:40px;
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover;
		position: relative;
		padding-bottom: 100%;
	}
</style>
@endLoadOnce
@loadStyleOnce('js/packages/noty/noty.css')
	@endpush
@push('after_scripts')
@loadScriptOnce('js/packages/noty/noty.min.js')
@loadOnce('bpFieldInitUploadMultipleElement')
        <!-- no scripts -->
        <script>
        	function bpFieldInitUploadMultipleElement(element) {
        		var fieldName = element.attr('data-field-name');
        		var clearFileButton = element.find(".file-clear-button");
        		var fileInput = element.find("input[type=file]");
        		var inputLabel = element.find("label.backstrap-file-label");

		        clearFileButton.click(function(e) {
		        	e.preventDefault();
		        	var container = $(this).parent().parent();
		        	var parent = $(this).parent();
		        	// remove the filename and button
		        	parent.remove();
		        	// if the file container is empty, remove it
		        	if ($.trim(container.html())=='') {
		        		container.remove();
		        	}
		        	$("<input type='hidden' name='clear_"+fieldName+"[]' value='"+$(this).data('filename')+"'>").insertAfter(fileInput);
		        });

		        fileInput.change(function() {
	                inputLabel.html("Files selected. After save, they will show up above.");
		        	// remove the hidden input, so that the setXAttribute method is no longer triggered
					$(this).next("input[type=hidden]:not([name='clear_"+fieldName+"[]'])").remove();
		        });
        	}
			
jQuery(document).ready(function () {
  ImgUpload();
  
});
function createnewinput(fileInput){
	var html='<input';
		$.each(fileInput.attributes,function(l,m){
			if(m.name !== 'data-number'){
				html+=` `+m.name+`="`+m.value+`"`;
			}else{
				oldnumber=parseInt(m.value)
				newnumber=oldnumber+1;
				html+=` `+m.name+`="`+newnumber+`"`;
			}
			//console.log(m.value);
		});
		html+='>';
		console.log(html);
}
function ImgUpload(e) {
  var imgWrap = "";
  var imgArray = [];
  $('.upload__inputfile').each(function () {
    $(this).on('change', function (e) {
		imgWrap = $(this).closest('.backstrap-file').find('.upload__img-wrap');
      var maxLength = $(this).attr('data-max_length');
      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      var iterator = 0;
	  console.log($(this).closest('.upload__btn').find('input[type=file]'));
	  var fileInput=$(this);
	  createnewinput(fileInput[0]);
      filesArr.forEach(function (f, index) {
		//if (!f.type.match('image.*')) {return;}
        if (imgArray.length > maxLength) {
          return false
        } else {
          var len = 0;
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i] !== undefined) {
              len++;
            }
          }
	  if("{{$multiple}}" !== "multiple"){
		imgArray=[];
				$(imgWrap).html('');
		}
          if (len > maxLength) {
            return false;
          } else {
				imgArray.push(f);
			const {
					host, hostname, href, origin, pathname, port, protocol, search
					} = window.location
            var reader = new FileReader();
            reader.onload = function (e) {
				if (f.type === 'application/pdf') {
					filepreview='';
					icon="pdf.png";
				}
				else if (f.type.match('audio.*')) {
					icon="sound.png";
					filepreview=` <audio controls><source src="`+e.target.result+`" type="audio/mpeg"></audio> `;
        		}
				else if (f.type.match('image.*')) {	
					icon="picture.png";
					filepreview='<img src="'+e.target.result+'" width="60">';
				}else{
					icon="document.png";
					filepreview='';
				}
				var html = `<div class='row border align-items-center'>
									
									<div class="col-sm-1 align-self-start" data-number='`+ $(".upload__img-close").length + `' data-file="` + f.name +`" class='img-bg'>
										<img src=" `+ websitelink+`/images/filetypes/`+icon +`" width="60px">
									</div>
									<div class="col-sm-1">`+filepreview+`</div>
									<div class='col-sm align-middle'>`+f.name+`</div>
									<div class='col-sm-5 align-middle'><i class="fa fa-trash" aria-hidden="true"></i></div>
								</div>`;
              imgWrap.append(html);
			  //console.log($(fileInput));
			  $(fileInput).before($(fileInput).clone());
			  //$("<input type='hidden' name='clear_"+fieldName+"[]' value='"+$(this).data('filename')+"'>")
              iterator++;
			  
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  });
  //
  
  $('body').on('click', ".fa-trash", function (e) {
    var file = $(this).parent().data("file");
    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        imgArray.splice(i, 1);
        break;
      }
    }
	view_noty('success','تم حذف الملف');
    $(this).parent().parent().remove();
  });
}
        </script>
@endLoadOnce
    @endpush