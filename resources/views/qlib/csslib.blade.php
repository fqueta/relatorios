<!--<link rel="stylesheet" href="{{url('/')}}/summernote/summernote.min.css">-->
<link rel="stylesheet" href="{{url('/')}}/css/jquery-ui.min.css">
<link rel="stylesheet" href="{{url('/')}}/css/lib.css?ver={{config('app.version')}}">
@if (isset($_GET['popup']) && $_GET['popup'])
<style>
    aside,.wrapper nav{
        display: none;
    }
    .content-wrapper{
        margin-left:0px !important;
    }

</style>
@endif
<style media="print">
    #DataTables_Table_0_filter label,#DataTables_Table_0_info{
        display: none;
    }
</style>
<div id="preload">
    <div class="lds-dual-ring"></div>
</div>
