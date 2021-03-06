@extends('base')

@section('links')
	<title>Login - CodeCharacter</title>
	<link rel="stylesheet" href="stylesheets/login.css">
@endsection

@section('main')
<div class="row">
    <div class="four columns">
  </div>
  <div class="four columns">
    <div class="row column log-in-form" onkeydown="enter(event)">
      <h4 class="text-center">Log in with your Pragyan account</h4>
      <div class="text-center" style="color: #FF9999">You MUST have a <a target="_blank" href="https://www.pragyan.org/17/home/+login&subaction=register">Pragyan account</a> before registering for Code Character</div>
      <label>Email
        <input type="text" id="username" placeholder="somebody@example.com">
      </label>
      <label>Password
        <input type="password" id="password" placeholder="Password">
      </label>
      <br />
      <p style="text-align:center"><button id="login-button" class="button expanded" onclick="login();return false;">Log In</button></p>
    </div>
	</div>
	<div class="four columns">
  </div>
</div>
	<script src="javascripts/login.js"></script>
@endsection
