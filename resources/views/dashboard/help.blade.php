@extends('layouts.core')
@section('title', 'Help')
@section('description', 'TapToScan help documentation')
@section('keywords', 'taptoscan, help')

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-lg-10 col-md-9 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">Help</h1>
				
			</div>
		</div>
	</div>
@stop