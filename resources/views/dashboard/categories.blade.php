@extends('layouts.core')
@section('title', __('dashboardCategories.title'))
@section('description', __('dashboardCategories.description'))
@section('keywords', __('dashboardCategories.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardCategories.title')</h1>
				
				@include('includes.alerts')

				<a href="#" class="btn btn-success text-capitalize margin-bottom-2" data-toggle="modal" data-target="#addCat"><i class="material-icons">add_circle_outline</i> @lang('dashboardCategories.addCategory')</a>

				<div class="table-responsive">
					<table id="currentTables" class="table table-hover">
						<tr class="text-capitalize">
							<th>@lang('dashboardCategories.categoryIcon')</th>
							<th>@lang('dashboardCategories.categoryName')</th> 
							<th>@lang('dashboardCategories.categoryProducts')</th>
							<th>@lang('dashboardCategories.categoryActions')</th>
						</tr>
						@foreach($categories as $category)
							<tr>
								<td><img class="category-img" src="{{ URL::to('/') . '/img/icons/svg/' . $category->icon->icon_res }}"alt="Image"/></td>
								<td><a class="link-no-decoration" href="{{ route('products.view', ['lang' => App::getLocale(),'catID' => $category->id]) }}">{{ $category->name }}</a></td> 
								<td><a href="{{ route('products.view', ['lang' => App::getLocale(),'catID' => $category->id]) }}" class="btn btn-success"><span class="badge">{{ $category->products->count() }}</span> @lang('dashboardCategories.categoryEditProducts')</a></td> 
								<td>
									<a href="#" class="btn btn-primary btn-edit" data-toggle="modal" data-target="#editCat" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-icon="{{ $category->icon->id }}"><i class="material-icons">edit</i> @lang('actions.edit')</a>
									<a href="#" class="btn btn-danger btn-delete" data-toggle="modal" data-target="#confirmDelete" data-id="{{ $category->id }}"><i class="material-icons">delete</i> @lang('actions.delete')</button>
								</td>
							</tr>
						@endforeach
					</table>
				</div>
				<div class="pagination"> {{ $categories->appends(Request::except('page'))->links() }} </div>
			</div>
		</div>
	</div>
	
	<div id="addCat" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardCategories.addCategory')</h4>
				</div>
				<div class="modal-body">
					<form id="addCategory" action="{{ route('category.add', App::getLocale()) }}" method="post">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="name">@lang('dashboardCategories.categoryName')</label>
							<input type="text" class="form-control input-lg" required autofocus placeholder="Category name" name="name" id="name">
						</div>
						<div class="form-group">
							<label for="icon">@lang('dashboardCategories.categoryIcon')</label>
						</div>
						<div class="row pre-scrollable">
							<div class="form-group">
						
								@foreach($icons as $icon)
								<div class="col-xs-2 margin-bottom-1">
									<input type="radio" class="custom-radio" name="icon" id="inlineRadio{{$icon->id}}"  required value="{{ $icon->id }}"> <img class="category-img-select" src="{{ URL::to('/') . '/img/icons/svg/' . $icon->icon_res }}"alt="Image"/></input>
								</div>
								@endforeach
							
							</div>
						</div>
						<div class="row text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('dashboardCategories.addCategoryBtn')</button>
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
					<h4 class="modal-title text-capitalize">@lang('dashboardCategories.deleteCategory')</h4>
				</div>
				<form class="inline" action="{{ route('category.delete', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<input id="confirmDeleteID" type="hidden" name="id" value=""/>
					<div class="modal-body text-center">
						<h3>@lang('dashboardCategories.deleteCategoryQuestion')</h3>
						<p>@lang('dashboardCategories.deleteCategoryQuestion2')</p>
					</div>
					<div class="modal-footer text-center">
						<button type="submit" class="btn btn-success btn-yes">@lang('actions.yes')</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.no')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="editCat" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardCategories.categoryEdit')</h4>
				</div>
				<form id="editCatForm" action="{{ route('category.edit', App::getLocale()) }}" method="post">
					<div class="modal-body">
						{{ csrf_field () }}
						<div class="form-group">
							<label for="name">@lang('dashboardCategories.categoryName')</label>
							<input id="catID" type="hidden" name="id" value=""/>
							<input id="catName" type="text" class="form-control input-lg" required name="name" autofocus>
						</div>
						
						<div class="row pre-scrollable">
							<div class="form-group">
						
								@foreach($icons as $icon)
								<div class="col-xs-2 margin-bottom-1">
									<input type="radio" class="custom-radio" name="icon" id="inlineRadioModal{{$icon->id}}" value="{{ $icon->id }}" required> <img class="category-img-select" src="{{ URL::to('/') . '/img/icons/svg/' . $icon->icon_res }}"alt="Image"/></input>
								</div>
								@endforeach
							
							</div>
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
			
			$(".btn-edit").click(function(e){
				var id = $(this).data('id');
				var name = $(this).data('name');
				var icon = $(this).data('icon');
				
				$('#editCat #catID').val(id);
				$('#editCat #catName').val(name);
				$('#editCat #editCatForm #inlineRadioModal' + icon).prop('checked', true);
				
			});
			
			$('#editCat').on('hidden.bs.modal', function () {
				$('#editCat #editCatForm input[type=radio]').each(function(){
					$(this).prop('checked', false);
				});
			});
			
		});
	</script>
@stop