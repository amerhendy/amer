@extends(Baseview('app'))
<?php
$route=$Amer->getRoute();
$SingularPageTitle=$Amer->getSubheading()?? $Amer->entity_name;
$PluralPageTitle=$Amer->getHeading() ?? $Amer->entity_name_plural;
if(!isset($breadcrumbs)){
        $breadcrumbs=[];
        $breadcrumbs[trans("AMER::auth.admin")]=Route('Admin');
        $breadcrumbs[$PluralPageTitle]=url($Amer->route);
        $breadcrumbs[trans('AMER::actions.'.$Amer->getcurrentOperation())] =false;
    }
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
?>
@php
$routename = Route::currentRouteName();
@endphp
@section('header')
	<section class="container-fluid">
	</section>
@endsection
@section('content')
    @include(mainview('main.Forms.header'))
    @include(mainview('main.Forms.formcontent'), [ 'fields' => $Amer->fields(), 'action' => 'create' ])
    @include(mainview('main.Forms.footer'))
@endsection
