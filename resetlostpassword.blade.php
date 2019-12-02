<?php

$companyname             	= 	BaseController::getCompanyname(1);
?>
@extends('layouts.register')
@section('content')
	<header class="headermain">
		<div class="container">
			<div class="col-sm-4 col-md-5 col-lg-6">
				<div class="logo"><a href="#">
				<% HTML::image('public/login/img/logo.png','',array('alt'=>'Multi Channel Import', 'class'=>'img-responsive')) %>		
				</a></div>
			</div>
			<div class="col-sm-8 col-md-7 col-lg-6 loginpart">
				
			</div>
		</div>
	</header>
	<div class="carousel slide carousel-fade customcarousal" data-ride="carousel">
		<div class="carousel-inner" role="listbox">
			<div class="item active"> </div>
			<div class="item"> </div>
			<div class="item"> </div>
		</div>
	</div>
	<div class="registerpart">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-sm-push-6">
					<div class="registertoday">
						<div class="header_register">
							<h1>Reset Password</h1>
						</div>
						<% Form::open(array('role'=>'form','id'=>'form-resetpswd')) %>    
		                <% Form::hidden('User[token]',$layoutArr['token']) %>
						<% Form::hidden('User[id]',$layoutArr['id']) %>   
							<div class="body_register">
								<div role="alert" class="alert alert-success alert-dismissible fade in" style="display:none;" id="sucMsgDiv">
				                  	<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
				                  	<span class="sucmsgdiv"></span>
				                </div>
				                <div role="alert" class="alert alert-danger alert-dismissible fade in" style="display:none;" id="failMsgDiv">
				                  	<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
				                  	<span class="failmsgdiv"></span>
				                </div>
								<div class="form-group form-group-lg">
									<% Form::password('User[new_password]',array('class'=>'form-control','id'=>'new_password','autocomplete'=>'off','placeholder'=>"Enter New Password")) %> 
								</div>
								
								<div class="form-group registerbtn">
									<button type="button" value="Start Selling" onclick="resetPassword();" class="btn btn-blue btn-block btn-lg text-uppercase">Submit</button>
								</div>
							</div>
						<% Form::close() %>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop