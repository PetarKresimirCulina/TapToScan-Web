@extends('layouts.core')
@section('title', __('dashboardUsers.title'))
@section('description', __('dashboardUsers.description'))
@section('keywords', __('dashboardUsers.keywords'))


@section('content')

	<div class="container-fluid">
	
		<div class="row flex">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
			
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.usersManagement')</h1>
				
				@include('includes.alerts')

				<a href="#" class="btn btn-default text-capitalize margin-bottom-2" data-toggle="modal" data-target="#filterResults"><i class="material-icons">format_list_bulleted</i> @lang('actions.filter')</a>
				
				<div class="table-responsive">
					<table id="currentUsers" class="table table-condensed table-hover" style="border-collapse:collapse;">
						<thead>
							<tr class="text-capitalize">
								<th>@lang('dashboardUsers.id')</th>
								<th>@lang('dashboardUsers.email')</th> 
								<th>@lang('dashboardUsers.fullName')</th> 
								<th>@lang('dashboardUsers.status')</th>
								<th>@lang('dashboardUsers.registered')</th> 
								<th>@lang('dashboardUsers.lastLogin')</th> 
								<th class="text-center">@lang('dashboardUsers.actions')</th>
							</tr>
						</thead>
						<tbody>
						@foreach($users as $user)
							<tr data-toggle="collapse" data-target="#demo{{ $user->id }}" class="accordion-toggle">								
								<td>{{ $user->id }}</td>
								<td>{{ $user->email }}</td> 
								<td>{{ $user->first_name . ' ' . $user->last_name }}
								<td id="status{{$user->id}}" data-blocked="{{$user->blocked}}" data-banned="{{$user->banned}}" data-canceled="{{$user->canceled}}">
									@if($user->banned == 1)
										Banned
									@elseif($user->blocked == 1)
										Blocked
									@elseif($user->canceled == 1)
										Canceled
									@else
										Active
									@endif
								</td>
								<td>
									{{ $user->date_registered }}
								</td>
								<td>
									{{ $user->last_login }}
								</td>
								<td class="text-center">
									<a href="#" data-id="{{ $user->id }}" data-toggle="modal" data-target="#confirmDelete" class="btn btn-danger btn-delete"><i class="material-icons">delete</i> @lang('actions.delete')</a>
									
									@if($user->banned == 0)
											<a href="#" data-status="0" data-id="{{ $user->id }}" class="btn btn-success btn-banned text-capitalize btn-tables"><i class="material-icons">block</i> @lang('actions.ban')</a>
									@else
											<a href="#" data-status="1" data-id="{{ $user->id }}" class="btn btn-warning btn-banned text-capitalize btn-tables"><i class="material-icons">warning</i> @lang('actions.unban')</a>
									@endif
									@if($user->blocked == 0)
											<a href="#" data-status="0" data-id="{{ $user->id }}" class="btn btn-success btn-blocked text-capitalize btn-tables"><i class="material-icons">lock_outline</i> @lang('actions.block')</a>
									@else
											<a href="#" data-status="1" data-id="{{ $user->id }}" class="btn btn-warning btn-blocked text-capitalize btn-tables"><i class="material-icons">lock_open</i> @lang('actions.unblock')</a>
									@endif
								
								</td>
							</tr>
							
							<tr>
								<td colspan="7" class="hiddenRow">
									<div class="accordian-body collapse" id="demo{{ $user->id }}">
										<div class="container">
											<div class="col-xs-4">
												<p class="small"><span class="bold">@lang('dashboardUsers.businessName'): </span>{{ $user->business_name }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.address'): </span>{{ $user->address }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.zip'): </span>{{ $user->zip }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.city'): </span>{{ $user->city }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.country'): </span>{{ $user->country }}</p>
											</div>
											<div class="col-xs-4">
												<p class="small"><span class="bold">@lang('dashboardUsers.vatID'): </span>{{ $user->vat_id }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.emailVerified'): </span>{{ $user->email_verified }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.banned'): </span><span id="banned{{$user->id}}">{{ $user->banned }}<span></p>
												<p class="small"><span class="bold">@lang('dashboardUsers.blocked'): </span><span id="blocked{{$user->id}}">{{ $user->blocked }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.admin'): </span>{{ $user->admin }}</p>
											</div>
											<div class="col-xs-4">
												<p class="small"><span class="bold">@lang('dashboardUsers.subscriptionPlan'): </span>
													@php $plan = $user->plan @endphp
													@if($plan)
														{{ $plan->name }}
													@else
														0
													@endif
													</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.braintreeID'): </span>{{ $user->braintree_id }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.cardBrand'): </span>{{ $user->card_brand }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.trialEndsAt'): </span>{{ $user->trial_ends_at }}</p>
												<p class="small"><span class="bold">@lang('dashboardUsers.canceled'): </span>{{ $user->canceled }}</p>
											</div>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="pagination"> {{ $users->appends(Request::except('page'))->links() }} </div>
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
					<form id="addTable" action="{{ route('dashboard.usersManagement', App::getLocale()) }}" method="get">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="email">@lang('dashboardUsers.userEmail')</label>
							<input type="text" class="form-control input-lg" autofocus placeholder="someone@example.com" name="email" id="email">
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
					<h4 class="modal-title text-capitalize">@lang('dashboardUsers.deleteUser')</h4>
				</div>
				<form class="inline" action="{{ route('tags.deleteTable', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<input id="confirmDeleteID" type="hidden" name="tag" value=""/>
					<div class="modal-body text-center">
						<h3>@lang('dashboardUsers.deleteUserQuestion')</h3>
					</div>
					<div class="modal-footer text-center">
						<button type="submit" class="btn btn-success btn-yes">@lang('actions.yes')</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.no')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="confirmBanChange" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardUsers.banUser')</h4>
				</div>
					<div class="modal-body text-center">
						<h3>@lang('dashboardUsers.banUserQuestion')</h3>
					</div>
					<div class="modal-footer text-center">
						<button id="banChangeConfirmation" type="button" data-dismiss="modal" data-id="0" data-calledBy="0" class="btn btn-success btn-yes btn-banned-confirmed">@lang('actions.yes')</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.no')</button>
					</div>
			</div>
		</div>
	</div>
	
	<div id="confirmBlockChange" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardUsers.blockUser')</h4>
				</div>
					<div class="modal-body text-center">
						<h3>@lang('dashboardUsers.blockUserQuestion')</h3>
					</div>
					<div class="modal-footer text-center">
						<button id="blockChangeConfirmation" type="button" data-dismiss="modal" data-id="0" data-calledBy="0" class="btn btn-success btn-yes btn-blocked-confirmed">@lang('actions.yes')</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('actions.no')</button>
					</div>
			</div>
		</div>
	</div>
	
	
@stop

@section('javascript')

<script>
	
	$(document).ready(function() {
		
		$(".btn-banned").click(function(e) {
			e.stopPropagation();
			e.preventDefault();
			var id = $(this).data('id');
			$('.btn-banned-confirmed').data('id', id);
			$('.btn-banned-confirmed').data('calledBy', $(this));
			$('#confirmBanChange').modal('show');
		});
		
		$(".btn-blocked").click(function(e) {
			e.stopPropagation();
			e.preventDefault();
			var id = $(this).data('id');
			$('.btn-blocked-confirmed').data('id', id);
			$('.btn-blocked-confirmed').data('calledBy', $(this));
			$('#confirmBlockChange').modal('show');
		});
		
		$(".btn-banned-confirmed").click(function(e) {

			var btn = $(this).data('calledBy');
			btn = $(btn);
			if($(btn).data('requestRunning') ) {
				return;
			}
			$(btn).data('requestRunning', true);
			
			$.ajax({
				type: "POST",
				url: "{{ route('users.banned', App::getLocale()) }}",
				data: { "user": $(this).data("id"), "status":  $(this).data("status") },
					success: function(data ){
						if(data == 'Success')
						{
							if($(btn).data('status') == 1)
							{
								$(btn).addClass("btn-success").removeClass("btn-warning").html("<i class=\"material-icons\">block</i> Ban");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 0);
								$('#banned'+$(btn).data("id")).text('0');
								$('#status'+$(btn).data("id")).data('banned', '0');
								modifyStatus($('#status'+$(btn).data("id")), $(btn).data("id"));
								return;
							}
							else
							{
								$(btn).addClass("btn-warning").removeClass("btn-success").html("<i class=\"material-icons\">warning</i> Unban");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 1);
								$('#banned'+$(btn).data("id")).text('1');
								$('#status'+$(btn).data("id")).data('banned', '1');
								modifyStatus($('#status'+$(btn).data("id")), $(btn).data("id"));
								return;
							}
							
						}
					},
					error: function(xhr, status, error) {
						alert(error);
					}
			});
		});
		
		$(".btn-blocked-confirmed").click(function(e) {

			var btn = $(this).data('calledBy');
			btn = $(btn);
			if($(btn).data('requestRunning') ) {
				return;
			}
			$(btn).data('requestRunning', true);
			
			$.ajax({
				type: "POST",
				url: "{{ route('users.blocked', App::getLocale()) }}",
				data: { "user": $(this).data("id"), "status":  $(this).data("status") },
					success: function(data ){
						if(data == 'Success')
						{
							if($(btn).data('status') == 1)
							{
								$(btn).addClass("btn-success").removeClass("btn-warning").html("<i class=\"material-icons\">lock_outline</i> Block");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 0);
								$('#blocked'+$(btn).data("id")).text('0');
								$('#status'+$(btn).data("id")).data('blocked', '0');
								modifyStatus($('#status'+$(btn).data("id")), $(btn).data("id"));
								return;
							}
							else
							{
								$(btn).addClass("btn-warning").removeClass("btn-success").html("<i class=\"material-icons\">lock_open</i> Unblock");
								$(btn).data('requestRunning', false);
								$(btn).data('status', 1);
								$('#blocked'+$(btn).data("id")).text('1');
								$('#status'+$(btn).data("id")).data('blocked', '1');
								modifyStatus($('#status'+$(btn).data("id")), $(btn).data("id"));
								return;
							}
							
						}
					},
					error: function(xhr, status, error) {
						alert(error);
					}
			});
		});
		
		 $(".btn-delete").click(function(e){
			e.stopPropagation();
			$('#confirmDeleteID').val($(this).data('id'));
			$('#confirmDelete').modal('show');
		 });
		 
		 function modifyStatus(element, id){
			var banned = element.data('banned');
			var blocked = element.data('blocked');
			var canceled = element.data('canceled');
			
			if(banned == '1'){
				element.text('Banned');
			}
			else if(blocked == '1'){
				element.text('Blocked');
			}
			else if(canceled == '1'){
				element.text('Canceled');
			}
			else {
				element.text('Active');
			}
		 }
		
	});
	
	
	</script>
	<script src="{{ URL::asset('js/ajax.js') }}"></script>
	
	@stop