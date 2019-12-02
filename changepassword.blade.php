@extends('layouts.admin')
@section('content')
	<div class="">
        <div class="page-title">
            <div class="title_left">
              	<h3>User Profile</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              	<div class="x_panel">
                	<div class="x_title">
                  		<h2>User Profile <small>Edit User</small></h2>
                  		<div class="clearfix"></div>
                	</div>
                	<div class="x_content">
                  		<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                    		<div class="profile_img">
		                      	<!-- end of image cropping -->
		                      	<div id="crop-avatar">
		                        <!-- Current avatar -->
		                        	<div title="" class="avatar-view" data-original-title="Change the avatar">
		                        		<% HTML::image('public/admin/img/user.png','',array('alt'=>'CDMU User')) %>
		                        	</div>
		                      	</div>		                      
                    		</div>
                    		@if(is_object(Auth::user()))
				                <h3><% Auth::user()->old_password %> <% Auth::user()->new_password %> <% Auth::user()->lastname %></h3>
			                    <ul class="list-unstyled user_data">
			                      <li><i class="fa fa-map-marker user-profile-icon"></i> CDMU,Bhubaneswar,Odisha, India</li>
			                      <li>
			                        <i class="fa fa-briefcase user-profile-icon"></i> <% Auth::user()->username %>
			                      </li>
			                    </ul>
								<a class="btn btn-success">Active</a>
							@endif
                  		</div>
                  		<div class="col-md-9 col-sm-9 col-xs-12">
                  			<div role="alert" class="alert alert-success alert-dismissible fade in" style="display:none;" id="sucMsgDiv">
			                  	<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
			                  	<span class="sucmsgdiv"></span>
			                </div>
			                <div role="alert" class="alert alert-danger alert-dismissible fade in" style="display:none;" id="failMsgDiv">
			                  	<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
			                  	<span class="failmsgdiv"></span>
			                </div>
		                	<div class="row">
	            				<div class="col-md-12 col-sm-12 col-xs-12">
	            					<div class="row">
				                  		<% Form::open(array('url'=>'','class'=>'form-horizontal form-label-left','id'=>'entryFrm')) %>
				                  		<% Form::hidden('id',Auth::user()->id, array('class'=>'form-control','id'=>'id')) %>
				                  			<div class="form-group">
						                      	<label for="first-name" class="control-label col-md-3 col-sm-3 col-xs-12">Old Password <span style="color:red">*</span></label>
						                      	<div class="col-md-6 col-sm-6 col-xs-12">
						                        	<% Form::text('User[old_password]',Input::old('old_password'), array('class'=>'col-lg-6 form-control','id'=>'old_password')) %>
						                      	</div>
						                    </div>
						                    <div class="form-group">
						                      	<label for="first-name" class="control-label col-md-3 col-sm-3 col-xs-12">New password <span style="color:red">*</span></label>
						                      	<div class="col-md-6 col-sm-6 col-xs-12">
						                        	<% Form::text('User[new_password]',Input::old('new_password'), array('class'=>'col-lg-6 form-control','id'=>'new_password')) %>
						                      	</div>
						                    </div>
						            		<div class="form-group">
						                      	<label for="first-name" class="control-label col-md-3 col-sm-3 col-xs-12">Confirm New Password <span style="color:red">*</span></label>
						                      	<div class="col-md-6 col-sm-6 col-xs-12">
						                        	<% Form::text('User[new_password_confirmation]',Input::old('new_password_confirmation'), array('class'=>'col-lg-6 form-control','id'=>'new_password_confirmation')) %>
						                      	</div>
						                    </div>
											<div class="ln_solid"></div>
											<div class="form-group">
						                      	<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						                      		<% Form::button('<i class="fa fa-save"></i> Update',array('class'=>'btn btn-success frmbtngroup','onclick'=>'javascript:changePassword();','ondblclick' => 'javascript:void(0);')); %>
													<% Form::button('<i class="fa fa-undo"></i> Reset',array('class'=>'btn btn-primary frmbtngroup','onclick'=>'javascript:resetFormVal('."'entryFrm',0".');','ondblclick' => 'javascript:void(0);')); %>                                                        
						                      	</div>
						                    </div>
					            		<% Form::close() %>
					            	</div>
				                </div>
	            			</div>
                  		</div>
            		</div>
              	</div>
            </div>
        </div>
    </div>
@stop