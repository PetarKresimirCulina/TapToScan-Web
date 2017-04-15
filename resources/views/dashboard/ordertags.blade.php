@extends('layouts.core')
@section('title', 'Order Tags')
@section('description', 'Order NFC tags for your bar/pub')
@section('keywords', 'taptoscan, order nfc tags, order tags')

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-lg-10 col-md-9 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">Order NFC tags</h1>
				
			</div>
		</div>
	</div>
@stop