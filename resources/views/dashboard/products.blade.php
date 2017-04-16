@extends('layouts.core')
@section('title', __('dashboardProducts.title'))
@section('description', __('dashboardProducts.description'))
@section('keywords', __('dashboardProducts.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.products')</h1>
				<h4 class="margin-bottom-2">@lang('dashboardProducts.category'): {{ $category->name }}</h4>
				
				@include('includes.alerts')

				<a href="{{ url(App::getLocale(), 'categories') }}" class="btn btn-default text-capitalize margin-bottom-2"><i class="material-icons">keyboard_arrow_left</i> @lang('actions.back')</a>
				<a href="#" class="btn btn-success text-capitalize margin-bottom-2" data-toggle="modal" data-target="#addProd"><i class="material-icons">add_circle_outline</i> @lang('dashboardProducts.addNewProduct')</a>

				
				<div class="table-responsive">
					<table id="currentTables" class="table table-hover">
						<tr class="text-capitalize">
							<th>@lang('dashboardProducts.productName')</th>
							<th>@lang('dashboardProducts.productPrice')</th> 
							<th>@lang('dashboardProducts.productAction')</th>
						</tr>
						@foreach($products as $product)
							<tr>
								<td>{{ $product->name }}</td>
								<td>{{ $product->formatCurrency(Lang::locale(), $product->price) }}</td> 
								<td>
									<a href="#" class="btn btn-primary btn-edit" data-toggle="modal" data-target="#editProd" data-id="{{ $product->id }}" data-price="{{ $product->price }}" data-name="{{ $product->name }}" data-currency="{{ $product->currency->id }}"><i class="material-icons">edit</i> @lang('actions.edit')</a>
									<a href="#" class="btn btn-danger btn-delete" data-toggle="modal" data-target="#confirmDelete" data-id="{{ $product->id }}"><i class="material-icons">delete</i> @lang('actions.delete')</button>
								</td>
							</tr>
						@endforeach
					</table>
				</div>
				
			</div>
		</div>
	</div>
	
	
	<div id="addProd" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardProducts.addProduct')</h4>
				</div>
				<div class="modal-body">
					<form id="addProduct" action="{{ route('products.add', App::getLocale()) }}" method="post">
					{{ csrf_field () }}
						<input type="hidden" name="cat" value="{{ $category->id }}"/>
						<div class="form-group">
							<label for="name">@lang('dashboardProducts.productName')</label>
							<input type="text" class="form-control input-lg" required placeholder="Coca-Cola 0.5L" name="name" id="name"  required autofocus>
						</div>
						<div class="form-group">
							<label for="price">@lang('dashboardProducts.productPrice')</label>
							<input type="number" class="form-control input-lg" step="any" min="0" required placeholder="10.00" 1" name="price" id="price" required>
						</div>
						<div class="form-group">
							<label for="currency">@lang('dashboardProducts.priceCurrency')</label>
							<select class="form-control" name="currency" id="currency" required>
								@foreach($currencies as $currency)
									<option value="{{ $currency->id }}">{{$currency->country }} - {{ $currency->currency }} ({{ $currency->symbol }})</option>
								@endforeach
								
							</select>
						</div>
						
						<div class="row text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('dashboardProducts.addProduct')</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">@lang('actions.close')</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="confirmDelete" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardProducts.deleteProduct')</h4>
				</div>
				<form class="inline" action="{{ route('products.delete', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="cat" value="{{ $category->id }}" />
					<input id="confirmDeleteID" type="hidden" name="id" value=""/>
					<div class="modal-body text-center">
						<h3>@lang('dashboardProducts.deleteProductQuestion')</h3>
					</div>
					<div class="modal-footer text-center">
						<button type="submit" class="btn btn-success btn-yes">@lang('actions.yes')</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.no')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="editProd" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardProducts.productEdit')</h4>
				</div>
				<form id="editProduct" action="{{ route('products.edit', App::getLocale()) }}" method="post">
					<div class="modal-body">
						{{ csrf_field () }}
						<input type="hidden" name="cat" value="{{ $category->id }}"/>
						<input id="id" type="hidden" name="id" value=""/>
						<div class="form-group">
							<label for="name">@lang('dashboardProducts.productName')</label>
							<input type="text" class="form-control input-lg" required placeholder="Coca-Cola 0.5L" name="name" id="name" autofocus>
						</div>
						<div class="form-group">
							<label for="price">@lang('dashboardProducts.productPrice')</label>
							<input type="number" class="form-control input-lg" step="0.01" min="0" required placeholder="10.00" 1" name="price" id="price">
						</div>
						<div class="form-group">
							<label for="currency">@lang('dashboardProducts.priceCurrency')</label>
							<select class="form-control" name="currency" id="currency" required>
								@foreach($currencies as $currency)
									<option value="{{ $currency->id }}">{{$currency->country }} - {{ $currency->currency }} ({{ $currency->symbol }})</option>
								@endforeach
								
							</select>
						</div>
					</div>
					<div class="modal-footer text-center">
						<button type="submit" class="btn btn-success">@lang('actions.save')</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.cancel')</button>
					</div>
				</form>
			</div>	
		</div>
	</div>
	
@stop

@section('javascript')

<script>
		$(document).ready(function() {
				
			$(".btn-delete").click(function(e){
				$('#confirmDeleteID').val($(this).data('id'));
			});
		});
		
		$(".btn-edit").click(function(e){
				var id = $(this).data('id');
				var name = $(this).data('name');
				var price = $(this).data('price');
				var currency = $(this).data('currency');
				
				$('#editProduct #id').val(id);
				$('#editProduct #name').val(name);
				$('#editProduct #price').val(price);
				$('#editProduct #currency').val(currency);
			});
	</script>
	
	@stop