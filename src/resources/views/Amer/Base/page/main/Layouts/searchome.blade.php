@push('styles')
<style>
    .results tr[visible='false'],
  .no-result{
    display:none;
  }

  .results tr[visible='true']{
    display:table-row;
  }

  .counter{
    padding:8px; 
    color:#ccc;
  }
</style>
@endpush
@push('scripts')

<script title="" type="application/javascript">
    //$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    crf="{{ csrf_token() }}";
    link_home="{{url('')}}/";
    postmethod="post";
    getmethod="get";
    jv_errors=[];
</script>

<script>
    //$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

</script>
<script>
  
</script>
@endpush
<div class="container omg">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <div class="form-group pull-right">
      <form class="input-group w-auto my-auto d-none d-sm-flex searchform">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <input autocomplete="off" type="search" class="search form-control rounded" placeholder="Search" style="min-width: 125px;" id="search-input">
        <span class="searchbtn btn btn-primary input-group-text border-0 d-none d-lg-flex" id="search-button"><i class="fas fa-search"></i></span>
    </form>
      <div class="container-fluid" id='tols_search_result'></div>
  </div>  
</nav>