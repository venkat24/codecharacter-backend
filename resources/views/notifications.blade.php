@extends('base')

@section('links')
  <title>Notifications - CodeCharacter</title>
  <link rel="stylesheet" href="stylesheets/notifications.css">
  <link rel="stylesheet" href="stylesheets/docs.css">
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
    <div class="one columns"></div>
    <div class="twelve columns callout-container primary">
      <div class="callout">
        <h5>{!! $notif->title !!}</h5>
        <p>{!! $notif->message !!}</p>
      </div>
    </div>
    <div class="one columns"></div>
      @endforeach
  </div>
  <script type="text/javascript" src="javascripts/notifications.js"></script>
@endsection

