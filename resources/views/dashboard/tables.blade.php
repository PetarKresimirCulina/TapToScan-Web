@extends('layouts.core')
@section('title', __('dashboardTables.title'))
@section('description', __('dashboardTables.description'))
@section('keywords', __('dashboardTables.keywords'))


@section('content')

	<div class="container-fluid">
	
		<div class="row flex">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.tables')</h1>
				
				@include('includes.alerts')

				<a href="#" class="btn btn-success text-capitalize margin-bottom-2" data-toggle="modal" data-target="#addTag"><i class="material-icons">add_circle_outline</i> @lang('dashboardTables.addTable')</a>
				<p class="small" id="tagsCounter" data-active="{{ Auth::user()->tagsActive->count() }}" data-limit="{{ Auth::user()->plan->tags_limit }}">@lang('dashboardTables.tableTagsActive'): {{ Auth::user()->tagsActive->count() }}/{{ Auth::user()->plan->tags_limit }} | @lang('dashboardTables.currentPlan'): <a href="{{ route('dashboard.billing', App::getLocale()) }}">{{ Auth::user()->plan->name }}</a></p>
				
				<div class="table-responsive">
					<table id="currentTables" class="table table-hover">
						<tr class="text-capitalize">
							<th>@lang('dashboardTables.tagId')</th>
							<th>@lang('dashboardTables.tableName')</th> 
							<th class="text-center">@lang('dashboardTables.status')</th>
							<th>@lang('dashboardTables.actions')</th>
						</tr>
						@foreach($tags as $tag)
							<tr>
								<td>{{ $tag->id }}</td>
								<td>{{ $tag->name }}</td> 
								<td class="text-center">
									@if($tag->active == 1)
											<a href="#" data-status="0" data-id="{{ $tag->id }}" class="btn btn-success btn-status text-capitalize btn-tables"><i class="material-icons">check_circle</i> @lang('dashboardTables.active')</a>
									@else
											<a href="#" data-status="1" data-id="{{ $tag->id }}" class="btn btn-warning btn-status text-capitalize btn-tables"><i class="material-icons">warning</i> @lang('dashboardTables.notActive')</a>
									@endif
								</td>
								<td>
									<a href="#" class="btn btn-primary btn-edit" data-toggle="modal" data-target="#editTag" data-tag="{{ $tag->id }}" data-name="{{ $tag->name }}"><i class="material-icons">edit</i> @lang('actions.rename')</a>
									<a href="#" data-id="{{ $tag->id }}" data-toggle="modal" data-target="#confirmDelete" class="btn btn-danger btn-delete"><i class="material-icons">delete</i> @lang('actions.delete')</button>
								</td>
							</tr>
						@endforeach
					</table>
				</div>
				<div class="pagination"> {{ $tags->appends(Request::except('page'))->links() }} </div>
			</div>
		</div>
	</div>
	
	
	<div id="addTag" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardTables.addTable')</h4>
				</div>
				<div class="modal-body">
					<form id="addTable" action="{{ route('tags.add', App::getLocale()) }}" method="post">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="tag">@lang('dashboardTables.tagIdNo')</label>
							<input type="text" class="form-control input-lg" required autofocus placeholder="ABCD1234" name="tag" id="tag">
						</div>
						<div class="form-group">
							<label for="name">@lang('dashboardTables.tableName')</label>
							<input type="text" class="form-control input-lg" required placeholder="Table 1" name="name" id="name">
						</div>
						<div class="form-group">
							<label for="status">@lang('dashboardTables.tableStatus')</label>
							<select class="form-control" name="status" id="status" required>
								<option value="1">@lang('dashboardTables.active')</option>
								<option value="0">@lang('dashboardTables.inactive')</option>
							</select>
						</div>
						
						<div class="row text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('dashboardTables.addTable')</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">@lang('actions.close')</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="editTag" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardTables.renameTable')</h4>
				</div>
				<form id="editTable" action="{{ route('tags.edit', App::getLocale()) }}" method="post">
					<div class="modal-body">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="tag">@lang('dashboardTables.tableName')</label>
							<input id="editTagID" type="hidden" name="tag" value=""/>
							<input id="editTagName" type="text" class="form-control input-lg" required autofocus name="editTagName">
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
	
	<div id="confirmDelete" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardTables.deleteTable')</h4>
				</div>
				<form class="inline" action="{{ route('tags.deleteTable', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<input id="confirmDeleteID" type="hidden" name="tag" value=""/>
					<div class="modal-body text-center">
						<h3>@lang('dashboardTables.deleteTableQuestion')</h3>
					</div>
					<div class="modal-footer text-center">
						<button type="submit" class="btn btn-success btn-yes">@lang('actions.yes')</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.no')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="tagLimit" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardTables.tagLimitTitle')</h4>
				</div>
				<div class="modal-body text-center">
					<p>@lang('dashboardTables.tagLimitMsg')</p>
					<a href="{{ route('dashboard.billing', App::getLocale()) }}" class="btn btn-primary text-capitalize">@lang('dashboardTables.upgradePlan')</a>
				</div>
				<div class="modal-footer text-center">
					<a href="#" class="btn btn-danger text-capitalize" data-dismiss="modal">@lang('actions.close')</a>
				</div>
			</div>
		</div>
	</div>
	
	
@stop

@section('javascript')

<script>
	
	$(document).ready(function() {
		
		$(".btn-edit").click(function() {
			var btn = $(this);
			var tag = $(btn).data('tag');
			var text = $(btn).data('name');
			$('#editTagName').val(text);
			$('#editTagID').val(tag);
		});
		
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
								$(btn).addClass("btn-success").removeClass("btn-warning").html("<i class=\"material-icons\">check_circle</i> @lang('dashboardTables.active')");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 0);
								var activeCounter = $('#tagsCounter').data('active') + 1;
								var tagLimit = $('#tagsCounter').data('limit');
								$('#tagsCounter').data('active', activeCounter);
								$('#tagsCounter').html("@lang('dashboardTables.tableTagsActive'): " + activeCounter + "/" + tagLimit + " | @lang('dashboardTables.currentPlan'): <a href=\"{{ route('dashboard.billing', App::getLocale()) }}\">{{ Auth::user()->plan->name }}</a>");
							}
							else
							{
								$(btn).addClass("btn-warning").removeClass("btn-success").html("<i class=\"material-icons\">warning</i> @lang('dashboardTables.notActive')");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 1);
								var activeCounter = $('#tagsCounter').data('active') - 1;
								var tagLimit = $('#tagsCounter').data('limit');
								$('#tagsCounter').data('active', activeCounter);
								$('#tagsCounter').html("@lang('dashboardTables.tableTagsActive'): " + activeCounter + "/" + tagLimit + " | @lang('dashboardTables.currentPlan'): <a href=\"{{ route('dashboard.billing', App::getLocale()) }}\">{{ Auth::user()->plan->name }}</a>");
							}
							
						} else if(data = 'Tag limit reached'){
							$(btn).data('requestRunning', false);
							$('#tagLimit').modal('show');
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