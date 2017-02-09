@extends('base')

@section('links')
  <title>Team - CodeCharacter</title>
  <link rel="stylesheet" href="stylesheets/teams.css">
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center">Team Selection</h2>
      <hr />
    </div>
  </div>
  
  <div class="row">
    <div class="eight columns">
      <p>Pick your team to play Code Character! You can have a maxiumum of <strong>three</strong> people in your team. </p>
      <h4>Note :</h4>
      <ul>
          <li>Your teammates must first register for Pragyan</li>
          <li>You cannot invite members part of another team</li>
          <li>If you create this team, you will be the team leader</li>
          <li>Invites can be accepted on the <strong>Alerts</strong> page</li>
          <li>If you wish to join another team, you must first delete your team</li>
          <li>Deleting your team will <strong>clear all records and submissions</strong></li>
          <li>You cannot submit code without forming a team</li>
          <li>Only the leader can add or remove members</li>
          <li>You may make someone else the leader</li>
          <li>You may leave the team if you are not the leader</li>
    </div>
    <div class="four columns">
      <img src="images/king.png" />
    </div>
  </div>

  <br />
  
  <div>
    <h4 class="text-center">Add/Remove Team Members</h4>
    <div class="row">
      <div id="team-info" class="large-12 columns">
        <input type="text" placeholder="Team Name" v-model="newTeamName" id="team-name"/>
        <button class="button expanded" v-on:click="create" id="create-button">@{{ buttonText }} Team</button> <br />
        <team-member v-if="teamMemberSeen" v-for="item in teamMembers" v-bind:member="item"></team-member> <br />
        <button class="button small" v-if="buttonSeen" v-on:click="add">Add Member</button>
      </div>
    </div>
  </div>

<script src="javascripts/teams.js"></script>
@endsection
