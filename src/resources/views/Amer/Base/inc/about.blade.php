 @section('after_scripts')
 <script title="" type="application/javascript">
var sitelink="{{url('')}}";
var file=sitelink+'/readme.txt';
jQuery.ajax({
        url:file,
        beforeSend: function() {

    },
    complete: function(){

    },
        dataType: 'text',
        type: 'get',
        success: function(data) {
            $('pre').html(data);
            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
</script>
@endsection

    <div class="container omg">
        <div class='nvbar'>
            <nav aria-label="nav" class="d-flex justify-content-center text-right">
                <pre class="grey lighten-3 px-3 mb-0 line-numbers  language-html">
                </pre>
            </nav>
        </div>
    </div>
