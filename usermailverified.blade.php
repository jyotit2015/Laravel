<?php

$companyname             	= 	BaseController::getCompanyname(1);
//       echo "<pre>"; print_r($layoutArr); echo "<pre>"; exit;
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
                        <div class="panel panel-primary">
                            <div class="panel-heading">Your E-Mail Address has been verified</div>
                            <div class="panel-body">
                                <strong>hello <% $layoutArr['name'] %>, </strong><br><br><br>
                                <p>
                                You have successfully confirmed your e-mail address <strong><% $layoutArr['email'] %></strong>.</p>
                                <p>You can log on to your personal Login pages here:
                                <a href="<% URL::to('user/userregister') %>">for Lgoin Click Here </a></p>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
	</div>
@stop