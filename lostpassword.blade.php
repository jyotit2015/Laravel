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
	                        <strong>Error !</strong>
	                        <% Session::get('error') %>
	                    </div>
	                @endif
	                @if(Session::has('message'))	
	                    <div role="alert" class="alert alert-success alert-dismissable">
	                        <strong></strong>									
	                        <% Session::get('message') %>
	                    </div>
	                @endif
	                <div role="alert" class="alert alert-success alert-dismissible fade in" style="display:none;" id="sucMsgDiv">
	                  	<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
	                  	<span class="sucmsgdiv"></span>
	                </div>
	                <div role="alert" class="alert alert-danger alert-dismissible fade in" style="display:none;" id="failMsgDiv">
	                  	<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
	                  	<span class="failmsgdiv"></span>
	                </div>
	                <% Form::open(array('role'=>'form','id'=>'form-lostpswd')) %>     
	                	<h1>Lost Password</h1>
	                	<p>Enter your email address below and we'll send you instructions to reset your password.</p>   
						<div>
							<% Form::text('User[email_forgot]', '', array('class'=>'form-control', 'placeholder'=>'Email Address','id'=>'email_forgot')) %>
						</div>
						<div class="text-left">
							<button type="button" class="btn btn-primary submit" onclick="validateLostpswdMail();">Reset Password</button>
			            </div>
						<div class="clearfix"></div>
			            <div class="separator">
			              	<p class="change_link">
			                	I remembered. Let me <a href="<% URL::to('user/login') %>" class="to_register"><b>Sign in</b></a>again. 
			              	</p>
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