@extends('base')

@section('links')
	<title>Login - CodeCharacter</title>
	<link rel="stylesheet" href="{{asset('stylesheets/login.css')}}">
@endsection

@section('main')
<div class="row">
	<div class="large-4 columns">
  </div>
  <div class="large-4 columns">
    <div class="row column log-in-form">
      <h4 class="text-center">Log in with your Pragyan account</h4>
      <label>Email
        <input type="text" placeholder="somebody@example.com">
      </label>
      <label>Password
        <input type="text" placeholder="Password">
      </label>
      <input id="show-password" type="checkbox"><label for="show-password">Show password</label>
      <p><a type="submit" class="button expanded">Log In</a></p>
    </div>
	</div>
	<div class="large-4 columns">
  </div>
</div>
	<script src="{{asset('javascripts/login.js')}}"></script>
@endsection
