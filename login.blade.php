<?php

$companyname             	= 	BaseController::getCompanyname(1);
?>
@extends('layouts.adminlogin')
@section('content')
	<div class="">
	    <div id="wrapper">
	      	<div id="login" class="animate form">
		        <section class="login_content">
		        	@if(Session::has('error'))
	                    <div role="alert" class="alert alert-danger alert-dismissable">
	                        <% Session::get('error') %>
	                    </div>
	                @endif
	                @if(Session::has('message'))	
	                    <div role="alert" class="alert alert-success alert-dismissable">
	                        <strong></strong>									
	                        <% Session::get('message') %>
	                    </div>
	                @endif
	                <% Form::open(array('url'=>'user/signin','role'=>'form','id'=>'form-login')) %>     
	                	<h1>Login Form</h1>   
						<div>
							<% Form::text('email', null, array('class'=>'form-control', 'placeholder'=>'Email','id'=>'email')) %>				
						</div>
						<div>
							<% Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password','id'=>'password')) %>				
						</div>
						<div class="text-left">
							<button type="submit" class="btn btn-primary submit">LOGIN</button>
				            <a class="reset_pass" href="<% URL::to('user/lostpassword') %>">Lost your password?</a>
			            </div>
						<div class="clearfix"></div>
			            <div class="separator">
			              	<!-- <p class="change_link">New to site?
			                	<a href="#toregister" class="to_register"> Create Account </a>
			              	</p> -->
			              	<div class="clearfix"></div>
			              	<br />
				            <div>
				               	<h4><i style="font-size: 26px;"></i> <% $companyname %></h4>
				                <p><% date('Y') %> All Rights Reserved. <% $companyname %> </p>
				            </div>
			            </div>
			        <% Form::close() %>
		          <!-- form -->
		        </section>
		        <!-- content -->
	      	</div>
    	</div>
  	</div>
@stop