@extends('layouts.core')
@section('title', 'Billing')
@section('description', 'Manage your account billing and invoices')
@section('keywords', 'taptoscan, billing, user, dashboard')

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">Billing</h1>
				
			</div>
		</div>
	</div>
@stop