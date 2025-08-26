<!-- text input -->
<?php
if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
$value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
// if attribute casting is used, convert to JSON
if (is_array($value)) {
    $value = json_encode((object) $value);
} elseif (is_object($value)) {
    $value = json_encode($value);
} else {
    $value = $value;
}
$videoservices=[
    'youtube'=>['icon'=>'youtube','color'=>'danger'],
    'VK'=>['icon'=>'vk','color'=>'info'],
    'vine'=>['icon'=>'vine','color'=>'teal'],
    'Vimeo'=>['icon'=>'vimeo','color'=>'info'],
    'twitter'=>['icon'=>'twitter','color'=>'info'],
    'telegram'=>['icon'=>'telegram','color'=>'info'],
    'pinterest'=>['icon'=>'pinterest','color'=>'danger'],
    'facebook'=>['icon'=>'facebook','color'=>'primary'],
    'instagram'=>['icon'=>'instagram','color'=>'primary'],
    'link'=>['icon'=>'link','color'=>'primary'],
];
$field['youtube_api_key'] = $field['youtube_api_key'] ?? 'AIzaSyAV1jdCJ5Jc1tFbJ3liax0mz9wCSxSvlDM';
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <input class="video-json" type="hidden" name="{{ $field['name'] }}" value="{{ $value }}">
    <div class="input-group">
        <input @include(fieldview('inc.attributes'), ['default_class' => 'video-link form-control']) type="url" id="{{ $field['name'] }}_link">
        <div class="input-group-append video-previewSuffix video-noPadding">
            <div class="video-preview">
                <span class="video-previewImage"></span>
                <a class="video-previewLink hidden" target="_blank" href="">
                    <i class="fa fa-eye video-previewIcon dummy"></i>
                </a>
            </div>
            <div class="video-dummy">
                <?php
                foreach($videoservices as $a=>$b){
                    echo'<a class="video-previewLink bg-'.$b['color'].' dummy" target="_blank" href="">
                    <i class="fa fa-'.$b['icon'].' video-previewIcon dummy"></i>
                </a>';
                }
                ?>
            </div>
        </div>
    </div>

@if (isset($field['hint']))
<small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
@endif
@include(fieldview('inc.wrapper_end'))

    @push('after_styles')
        <style media="screen">
            .video-previewSuffix {
                border: 0;
                min-width: 68px; }
            .video-noPadding {
                padding: 0; }
            .video-preview {
                display: none; }
            .video-previewLink {
                 color: #fff;
                 display: block;
                 width: 2.375rem; height: 2.375rem;
                 text-align: center;
                 float: left; }
            .video-previewLink.youtube {
                background: #DA2724; }
            .video-previewLink.vimeo {
                background: #00ADEF; }
            .video-previewIcon {
                transform: translateY(7px); }
            .video-previewImage {
                float: left;
                display: block;
                width: 2.375rem; height: 2.375rem;
                background-size: cover;
                background-position: center center; }
        </style>
    @endpush
    @push('after_scripts')
        <script>
        var tryYouTube = function( link ){
            var id = null;

            // RegExps for YouTube link forms
            var youtubeStandardExpr = /^https?:\/\/(www\.)?youtube.com\/watch\?v=([^?&]+)/i; // Group 2 is video ID
            var youtubeAlternateExpr = /^https?:\/\/(www\.)?youtube.com\/v\/([^\/\?]+)/i; // Group 2 is video ID
            var youtubeShortExpr = /^https?:\/\/youtu.be\/([^\/]+)/i; // Group 1 is video ID
            var youtubeEmbedExpr = /^https?:\/\/(www\.)?youtube.com\/embed\/([^\/]+)/i; // Group 2 is video ID

            var match = link.match(youtubeStandardExpr);

            if (match != null){
                id = match[2];
            }
            else {
                match = link.match(youtubeAlternateExpr);

                if (match != null) {
                    id = match[2];
                }
                else {
                    match = link.match(youtubeShortExpr);

                    if (match != null){
                        id = match[1];
                    }
                    else {
                        match = link.match(youtubeEmbedExpr);

                        if (match != null){
                            id = match[2];
                        }
                    }
                }
            }

            return id;
        };

        var tryVimeo = function( link ){

            var id = null;
            var regExp = /(http|https):\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;

            var match = link.match(regExp);

            if (match){
                id = match[3];
            }

            return id;
        };

        var fetchYouTube = function( videoId, callback, apiKey ){

            var api = 'https://www.googleapis.com/youtube/v3/videos?id='+videoId+'&key='+apiKey+'&part=snippet';

            var video = {
                provider: 'youtube',
                id: null,
                title: null,
                image: null,
                url: null
            };

            $.ajax({
                dataType: "jsonp",
                url: api,
                crossDomain: true,
                success: function (data) {
                    console.log(data);
                    if (typeof(data.items[0]) != "undefined") {
                        var v = data.items[0].snippet;

                        video.id = videoId;
                        video.title = v.title;
                        video.image = v.thumbnails.maxres ? v.thumbnails.maxres.url : v.thumbnails.default.url;
                        video.url = 'https://www.youtube.com/watch?v=' + video.id;

                        callback(video);
                    }
                }
            });
        };

        var fetchVimeo = function( videoId, callback ){

            var api = 'https://vimeo.com/api/v2/video/'+videoId+'.json';

            var video = {
                provider: 'vimeo',
                id: null,
                title: null,
                image: null,
                url: null
            };

            fetch(api).then(function(response) {
                if(response.ok) {
                    response.json().then(function(v) {

                        v = v[0];

                        video.id = v.id;
                        video.title = v.title;
                        video.image = v.thumbnail_large || v.thumbnail_small;
                        video.url = v.url;
                        callback(video);
                    });
                }
            });
        };

        var parseVideoLink = function( link, callback, apiKey ){

            var response = {success: false, message: 'unknown error occured, please try again', data: [] };

            try {
                var parser = document.createElement('a');
            } catch(e){
                response.message = 'Please post a valid youtube/vimeo url';
                return response;
            }


            var id = tryYouTube(link, apiKey);

            if( id ){

                return fetchYouTube(id, function(video){

                    if( video ){
                        response.success = true;
                        response.message = 'video found';
                        response.data = video;
                    }

                    callback(response);
                },apiKey);
            }
            else {

                id = tryVimeo(link);

                if( id ){

                    return fetchVimeo(id, function(video){

                        if( video ){
                            response.success = true;
                            response.message = 'video found';
                            response.data = video;
                        }

                        callback(response);
                    });
                }
            }

            response.message = 'We could not detect a YouTube or Vimeo ID, please try obtain the URL again'
            return callback(response);
        };

        var updateVideoPreview = function(video, container){

            var pWrap = container.find('.video-preview'),
                pLink = container.find('.video-previewLink').not('.dummy'),
                pImage = container.find('.video-previewImage').not('dummy'),
                pIcon  = container.find('.video-previewIcon').not('.dummy'),
                pSuffix = container.find('.video-previewSuffix'),
                pDummy  = container.find('.video-dummy');

            pDummy.hide();

            pLink
            .attr('href', video.url)
            .removeClass('youtube vimeo hidden')
            .addClass(video.provider);

            pImage
            .css('backgroundImage', 'url('+video.image+')');

            pIcon
            .removeClass('la-vimeo la-youtube')
            .addClass('la-' + video.provider);
            pWrap.fadeIn();
        };

        var videoParsing = false;

        function bpFieldInitVideoElement(element) {
            var $this = element,
                jsonField = $this.find('.video-json'),
                linkField = $this.find('.video-link'),
                pDummy = $this.find('.video-dummy'),
                pWrap = $this.find('.video-preview'),
                apiKey = $this.attr('data-youtube-api-key');
                try {
                    var videoJson = JSON.parse(jsonField.val());
                    jsonField.val( JSON.stringify(videoJson) );
                    linkField.val( videoJson.url );
                    updateVideoPreview(videoJson, $this);
                }
                catch(e){
                    pDummy.show();
                    pWrap.hide();
                    jsonField.val('');
                    linkField.val('');
                }

            linkField.on('focus', function(){
                linkField.originalState = linkField.val();
            });

            linkField.on('change', function(){
                if( linkField.originalState != linkField.val() ){

                    if( linkField.val().length ){

                        videoParsing = true;

                        parseVideoLink( linkField.val(), function( videoJson ){

                            if( videoJson.success ){
                                linkField.val( videoJson.data.url );
                                jsonField.val( JSON.stringify(videoJson.data) );
                                updateVideoPreview(videoJson.data, $this);
                            }
                            else {
                                pDummy.show();
                                pWrap.hide();
                                new Noty({
                                    type: "error",
                                    text: videoJson.message
                                }).show();
                            }

                            videoParsing = false;
                        },apiKey);
                    }
                    else {
                        videoParsing = false;
                        jsonField.val('');
                        $this.find('.video-preview').fadeOut();
                        pDummy.show();
                        pWrap.hide();
                    }
                }
            });
        }

        jQuery(document).ready(function($) {
            $('form').on('submit', function(e){
                if( videoParsing ){
                    new Noty({
                        type: "error",
                        text: "<strong>Please wait.</strong><br>Video details are still loading, please wait a moment or try again."
                    }).show();
                    e.preventDefault();
                    return false;
                }
            })
        });
        </script>

    @endpush
