@extends('layouts.core')
@section('title', 'Order Tags')
@section('description', 'Order NFC tags for your bar/pub')
@section('keywords', 'taptoscan, order nfc tags, order tags')

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">Order NFC tags</h1>
				
				@include('includes.alerts')
				@include('includes.blocked')
				
			</div>
		</div>
	</div>
@stop