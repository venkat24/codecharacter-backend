@extends('base')

@section('links')
  <title>Welcome to Foundation | Banded</title>
  <link rel="stylesheet" href="{{asset('stylesheets/leaderboard.css')}}">
  <link rel="stylesheet" href="{{asset('stylesheets/notifications.css')}}">
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center" id="main-heading">Scores</h2>
      <hr />
    </div>
  </div>
  <div class="row">
    <h5 style="text-align:center">Submissions have not yet been opened</h5>
  </div>
@endsection


