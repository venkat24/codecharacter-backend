@extends('base')

@section('links')
  <title>Welcome to Foundation | Banded</title>
  <link rel="stylesheet" href="{{asset('stylesheets/notifications.css')}}">
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center" id="main-heading">Notifications</h2>
      <hr />
    </div>
  </div>
  <div class="row">
      @foreach ($notifications as $notif)
    <div class="twelve columns callout-container primary">
      <div class="callout">
        <h5>{{$notif->title}}</h5>
        <p>{!! $notif->message !!}</p>
      </div>
    </div>
      @endforeach
  </div>
  
@endsection

