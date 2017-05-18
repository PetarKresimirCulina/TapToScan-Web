@extends('layouts.core')
@section('title', __('dashboardTags.title'))
@section('description', __('dashboardTags.description'))
@section('keywords', __('dashboardTags.keywords'))


@section('content')

	<div class="container-fluid">
	
		<div class="row flex">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
			
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.tagsManagement')</h1>
				
				@include('includes.alerts')

			@if(session()->has('results'))
				@php $results = session()->get('results') @endphp
				<a href="{{ route('dashboard.tagsManagement', App::getLocale()) }}" class="btn btn-default text-capitalize margin-bottom-2" ><i class="material-icons">keyboard_arrow_left</i> @lang('actions.back')</a>
				
				<div class="table-responsive">
					<table id="currentTables" class="table table-hover">
						<tr class="text-capitalize">
							<th>@lang('dashboardTags.tagId')</th>
						</tr>
						
						@foreach($results as $result)
							<tr>
								<td>{{ $result }}</td>
							</tr>
						@endforeach
						
					</table>
				</div>
			@else
				<a href="#" class="btn btn-success text-capitalize margin-bottom-2" data-toggle="modal" data-target="#addTag"><i class="material-icons">add_circle_outline</i> @lang('dashboardTags.addTag')</a>
				<a href="#" class="btn btn-primary text-capitalize margin-bottom-2" data-toggle="modal" data-target="#addTagBulk"><i class="material-icons">library_add</i> @lang('dashboardTags.addBulk')</a>
				<a href="#" class="btn btn-default text-capitalize margin-bottom-2" data-toggle="modal" data-target="#filterResults"><i class="material-icons">format_list_bulleted</i> @lang('actions.filter')</a>
				
				<div class="table-responsive">
					<table id="currentTables" class="table table-hover">
					<thead>
						<tr class="text-capitalize">
							<th>@lang('dashboardTags.tagId')</th>
							<th>@lang('dashboardTags.tableName')</th> 
							<th>@lang('dashboardTags.user')</th> 
							<th class="text-center">@lang('dashboardTags.status')</th>
							<th class="text-center">@lang('dashboardTags.actions')</th>
							<th>@lang('dashboardTags.createdAt')</th> 
							<th>@lang('dashboardTags.updatedAt')</th> 
						</tr>
					</thead>

					<tbody>
						@foreach($tags as $tag)
							<tr>
								<td>{{ $tag->id }}</td>
								<td>{{ $tag->name }}</td> 
								<td>@if($tag->userData == null)
									-
									@else
										{{ $tag->userData->email }}
									@endif</td> 
								<td class="text-center">
									@if($tag->active == 1)
											<a href="#" data-status="0" data-id="{{ $tag->id }}" class="btn btn-success btn-status text-capitalize btn-tables"><i class="material-icons">check_circle</i> @lang('dashboardTags.active')</a>
									@else
											<a href="#" data-status="1" data-id="{{ $tag->id }}" class="btn btn-warning btn-status text-capitalize btn-tables"><i class="material-icons">warning</i> @lang('dashboardTags.notActive')</a>
									@endif
								</td>
								<td class="text-center">
									<a href="#" data-id="{{ $tag->id }}" data-toggle="modal" data-target="#confirmDelete" class="btn btn-danger btn-delete"><i class="material-icons">delete</i> @lang('actions.delete')</a>
								</td>
								<td>
									{{ $tag->created_at }}
								</td>
								<td>
									{{ $tag->updated_at }}
								</td>
							</tr>
						@endforeach
					</tbody>
					</table>
				</div>
				<div class="pagination"> {{ $tags->appends(Request::except('page'))->links() }} </div>
			</div>
			@endif
		</div>
	</div>
	
	
	<div id="addTag" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardTags.addTag')</h4>
				</div>
				<div class="modal-body">
					<form id="addTable" action="{{ route('tags.addAdmin', App::getLocale()) }}" method="post">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="tag">@lang('dashboardTags.tagId')</label>
							<input type="text" class="form-control input-lg" required autofocus placeholder="ABCD1234" name="tag" id="tag">
						</div>
						
						<div class="row text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('dashboardTags.addTag')</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">@lang('actions.close')</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="addTagBulk" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardTags.addBulk')</h4>
				</div>
				<div class="modal-body">
					<form id="addTable" action="{{ route('tags.addBulkAdmin', App::getLocale()) }}" method="post">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="no">@lang('dashboardTags.numOfTags')</label>
							<input type="number" class="form-control input-lg" required autofocus placeholder="5" min="0" name="no" id="no">
						</div>
						
						<div class="row text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('actions.generate')</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">@lang('actions.close')</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="filterResults" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('actions.filter')</h4>
				</div>
				<div class="modal-body">
					<form id="addTable" action="{{ route('dashboard.tagsManagement', App::getLocale()) }}" method="get">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="serial">@lang('dashboardTags.tagId')</label>
							<input type="text" class="form-control input-lg" autofocus placeholder="ABCD1234" name="serial" id="serial">
						</div>
						<div class="form-group">
							<label for="user">@lang('dashboardTags.userEmail')</label>
							<input type="text" class="form-control input-lg" autofocus placeholder="someone@example.com" name="user" id="user">
						</div>
						
						<div class="row text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('actions.filter')</button>
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
					<h4 class="modal-title text-capitalize">@lang('dashboardTags.deleteTag')</h4>
				</div>
				<form class="inline" action="{{ route('tags.deleteTable', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<input id="confirmDeleteID" type="hidden" name="tag" value=""/>
					<div class="modal-body text-center">
						<h3>@lang('dashboardTags.deleteTagQuestion')</h3>
					</div>
					<div class="modal-footer text-center">
						<button type="submit" class="btn btn-success btn-yes">@lang('actions.yes')</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.no')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	
@stop

@section('javascript')

<script>
	
	$(document).ready(function() {
		
		$(".btn-tables").click(function(e) {
			e.preventDefault();
			
			var btn = $(this);
			if($(btn).data('requestRunning') ) {
				return;
			}
			$(btn).data('requestRunning', true);
			
			$.ajax({
				type: "POST",
				url: "{{ route('tags.changeStatus', App::getLocale()) }}",
				data: { "tag": $(this).data("id"), "status":  $(this).data("status") },
					success: function(data ){
						if(data == 'Success')
						{
							if($(btn).data('status') == 1)
							{
								$(btn).addClass("btn-success").removeClass("btn-warning").html("<i class=\"material-icons\">check_circle</i> @lang('dashboardTags.active')");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 0);
							}
							else
							{
								$(btn).addClass("btn-warning").removeClass("btn-success").html("<i class=\"material-icons\">warning</i> @lang('dashboardTags.notActive')");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 1);
								
							}
							
						}
					},
					error: function(xhr, status, error) {
						alert(error);
					}
			});
		});
		
		 $(".btn-delete").click(function(e){
			$('#confirmDeleteID').val($(this).data('id'));
			
		 });
		
	});
	
	
	</script>
	<script src="{{ URL::asset('js/ajax.js') }}"></script>
	
	@stop