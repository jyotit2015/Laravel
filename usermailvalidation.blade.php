<?php

$companyname             	= 	BaseController::getCompanyname(1);
?>
@extends('layouts.register')
@section('content')
    <header class="headermain">
        <div class="container">
            <div class="col-sm-4 col-md-5 col-lg-6">
                <div class="logo">
                    <a href="#">
                        <% HTML::image('public/login/img/logo.png','',array('alt'=>'Multi Channel Import', 'class'=>'img-responsive')) %>		
                    </a>
                </div>
            </div>
            <div class="col-sm-8 col-md-7 col-lg-6 loginpart">

            </div>
        </div>
    </header>
    <div class="registerpart">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">Confirmation of Your E-Mail Address</div>
                        <div class="panel-body">
                            <div role="alert" class="alert alert-success alert-dismissible fade in" style="display:none;" id="sucMsgDiv">
                                <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                                <span class="sucmsgdiv"></span>
                            </div>
                            <div role="alert" class="alert alert-danger alert-dismissible fade in" style="display:none;" id="failMsgDiv">
                                    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                                    <span class="failmsgdiv"></span>
                            </div>
                            <% Form::open(array('role'=>'form','id'=>'login-verify')) %>   
                            <% Form::hidden('id', $layoutArr['id'], array('class'=>'form-control', 'id'=>'id')) %>
                            <% Form::hidden('verfy_token', $layoutArr['token'], array('class'=>'form-control', 'id'=>'verfy_token')) %>  
                            <strong>hello </strong> <% $layoutArr['name'] %>, 
                            <p>Welcome to our Login Panel, Hope you enjoy our service a lot.</p>
                            <p>
                            To confirmed your e-mail address <b><% $layoutArr['email'] %></b> & personalise your Login ,Please click below:</p>
                            <div class="col-sm-4">
                                <div class="form-group">
                                        <button type="button" onclick="verifyLogin();" value="Login" class="btn btn-main btn-block">Verify your mail</button>
                                </div>
                            </div>
                            <% Form::close() %>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop