@extends('base')

@section('links')
  <title>Welcome to Foundation | Banded</title>
  <link rel="stylesheet" href="{{asset('stylesheets/leaderboard.css')}}">
  <link rel="stylesheet" href="{{asset('stylesheets/notifications.css')}}">
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center" id="main-heading">Leaderboard</h2>
      <hr />
    </div>
  </div>
  <div class="row">
      @foreach ($leaderboard as $user)
    <div class="twelve columns callout-container primary">
      <div class="callout">
        <h5>{{$user->teamName}}</h5>
        <p>{{$user->level}}</p>
        <p>{{$user->score}}</p>
        <a href="#">It's dangerous to go alone, take this.</a>
      </div>
    </div>
      @endforeach
  </div>
@endsection

