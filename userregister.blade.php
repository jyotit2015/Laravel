<?php

$companyname             	= 	BaseController::getCompanyname(1);
?>
@extends('layouts.register')
@section('content')
    <header class="headermain">
        <div class="container">
            <div class="col-sm-4 col-md-5 col-lg-6">
                    <div class="logo"><a href="<% URL::to('/'); %>">
                    <% HTML::image('public/login/img/logo.png','',array('alt'=>'Multi Channel Import', 'class'=>'img-responsive')) %>		
                    </a></div>
            </div>
            <div class="col-sm-8 col-md-7 col-lg-6 loginpart">
                <% Form::open(array('url'=>'user/usersignin','role'=>'form','id'=>'form-login')) %>     
                    <div class="row">
                        <div class="col-sm-4 paddingright0">
                            <div class="form-group">
                                    <% Form::text('email_login', null, array('class'=>'form-control', 'placeholder'=>'Email','id'=>'email_login')) %>
                            </div>
                        </div>
                        <div class="col-sm-4 paddingright0">
                            <div class="form-group">
                                    <% Form::password('password_login', array('class'=>'form-control', 'placeholder'=>'Password','id'=>'password_login')) %>	
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                    <button type="submit" value="Login" class="btn btn-main btn-block">LOGIN</button>
                            </div>
                        </div>
                    </div>
                <% Form::close() %>
                @if(Session::has('error'))
                    <div class="error-message">
                        <strong><% Session::get('error') %> </strong>
                    </div>
                @endif
                @if(Session::has('message'))	
                    <div class="success-message">
                        <strong><% Session::get('message') %> </strong>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-6 paddingright0">
                        <label class="checkbox customcheckbox">
                        <input type="checkbox">
                        <span> Remember Me</span> </label>
                    </div>
                    <div class="col-sm-6 forgotpass"> <a href="<% URL::to('user/forgotpassword') %>">Forgot Your Password?</a> </div>
                </div>
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
                                <h1>Register Today</h1>
                        </div>
                        <div class="body_register">
                            <div role="alert" class="alert alert-success alert-dismissible fade in" style="display:none;" id="sucMsgDiv">
                                <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                                <span class="sucmsgdiv"></span>
                            </div>
                            <div role="alert" class="alert alert-danger alert-dismissible fade in" style="display:none;" id="failMsgDiv">
                                    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                                    <span class="failmsgdiv"></span>
                            </div>
                            <% Form::open(array('url'=>'','role'=>'form','id'=>'form-registration')) %>     
                                <div class="form-group">
                                    <% Form::text('User[fullname]', null, array('class'=>'form-control', 'placeholder'=>'Full Name','id'=>'fullname')) %>
                                </div>
                                <div class="form-group">
                                    <% Form::text('User[email]', null, array('class'=>'form-control', 'placeholder'=>'Email Address','id'=>'email')) %>
                                </div>
                                <div class="form-group">
                                    <% Form::text('User[mobile]', null, array('class'=>'form-control', 'placeholder'=>'Phone Number','id'=>'mobile')) %>
                                </div>
                                <div class="form-group">
                                    <% Form::password('User[password]', array('class'=>'form-control', 'placeholder'=>'Password','id'=>'password')) %>
                                </div>
                                <div class="form-group">
                                    <% Form::password('User[password_confirmation]', array('class'=>'form-control', 'placeholder'=>'Re-enter Password','id'=>'password_confirmation')) %>
                                </div>
                                <div class="form-group registerbtn">
                                    <% Form::button('<span class="startselling_icon"></span>Start Selling',array('class'=>'btn btn-blue btn-lg btn-block text-uppercase frmbtngroup','onclick'=>'javascript:addNewRegistration();','ondblclick' => 'javascript:void(0);')); %>
                                </div>
                            <% Form::close() %>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop