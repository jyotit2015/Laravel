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
	<div class="registerpart">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="panel panel-danger">
						<div class="panel-heading">Your E-Mail Address has been Expired</div>
						<div class="panel-body">
							<strong>hello <% $layoutArr['name'] %>, </strong><br><br><br>
							
							<p>You can log on to your personal Login pages By Registration again,For New Registration 
							<a href="<% URL::to('user/userregister') %>">Click Here </a></p>

						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
@stop