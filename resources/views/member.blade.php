@extends('base')

@section('links')
  <title>Welcome to Foundation | Banded</title>
  <link rel="stylesheet" href="{{asset('stylesheets/teams.css')}}">
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center">Member of Team {{Session::get('team_name')}}</h2>
      <hr />
    </div>
  </div>
  
  <div class="row">
    <div class="eight columns">
      <p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit. Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine spare ribs reprehenderit culpa. Boudin aliqua adipisicing rump corned beef.</p>
      
      <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>

    </div>
    <div class="four columns">
      <img src="http://placehold.it/400x300&text=[img]" />
    </div>
  </div>
  <div class="row">
    <div class="twelve columns">
      <button class="button" onclick="leave()">Leave Team</button>
    </div>
  </div>

  <br />
  
<script src="{{asset('javascripts/member.js')}}"></script>
@endsection
