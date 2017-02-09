@extends('base')

@section('links')
  <title>Team - CodeCharacter</title>
  <link rel="stylesheet" href="stylesheets/teams.css">
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
      <p>You are currently a member of this team. You may leave this team if you wish to do so. </p>
      <p>You can make submissions as part of the team. However, you cannot remove or add members to the team.</p>
    <h5>Your Team</h5>
    <ul id="team-members-list">
    </ul>
      

    </div>
    <div class="four columns">
      <img src="images/wizard2.png" />
    </div>
  </div>
  <div class="row">
    <div class="twelve columns">
      <button class="button" onclick="leave()">Leave Team</button>
    </div>
  </div>

  <br />
  
<script src="javascripts/member.js"></script>
@endsection
