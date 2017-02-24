@extends('base')

@section('links')
  <title>Submit - Code Character</title>
  <link rel="stylesheet" href="stylesheets/submit.css">
  <style>
    code {
        background-color: transparent;
        color: lightgreen;
    }
    label {
        color: #CCCCCC;
    }
  </style>
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center">Submit Code</h2>
      <h5>
        Last Submission Status : <code id="submission-status"></code>
      </h5>
        <br />
        <br />
        <p><strong>Please read the following instructions carefully : </strong></p>
      <p>
        Compress your code into <code>.zip</code> format and upload it below. Make a zip file that contains only the <code>player1</code> folder. If your zip file contains anything else, or does not contain the player1 folder, your code will NOT execute. Here is a sample submission : </p>
      <p><a href="myawesomesubmission.zip">myawesomesubmission.zip</a></p>
      <p>
       Please check your team's previous submission status above before submitting. If your status is <code>WAITING</code> or <code>RUNNING</code>, a new file <strong>cannot</strong> be submitted. Since a game of Code Character takes about 5 minutes, please allow anywhere from 5 min to 30 minutes for your code to be queued and executed on the server.
      </p>
      <p>
        Once the game is complete, you will receive an alert with the execution status. If your code executed properly, you will get the final score, and if you defeated the AI and also got a new high score for a particular level, your score will be updated on the leaderboard.
      </p>
      <p>
        You can contest for both the current level that you're on as well as the last level you cleared, to get a higher score. Your score will only be updated if you score higher than your previous attempt or defeat the AI on your current level. Your score is the difference between your score and the AI's score.
      </p>
      <p><strong>SUBMISSION LIMIT: 10 SUBMISSIONS PER DAY</strong></p>
      <hr />
    </div>
  </div>

  <div class="row">
    <div class="twelve columns">
      <h4 class="text-center">Upload</h4>
      <form enctype="multipart/form-data" id="code-submit-form" method="POST" action="/api/submit_code">
        <label>Select Level <br />
        <select name='level' id='level' style="width:50%">
            <option value="{{$level->currentLevel}}">{{$level->currentLevel}}</option>
            @if ( $level->currentLevel != 1  )
            <option value="{{$level->currentLevel-1}}">{{$level->currentLevel-1}}</option>
            @endif
        </select>
        </label>
        <label>Upload Zip File
					<br />
          <input type="hidden" name="MAX_FILE_SIZE" value="314572800">
          <button> 
            <input type="file" name="file" class="show-for-sr"/>
          </button>
        </label>
        <input type="hidden" id="teamName" name="teamName" value="{{Session::get('team_name')}}" />
				<button class="button" type="submit">Submit</button>
      </form>
      <hr />
    </div>
  </div>
  <script src="javascripts/submit.js">
@endsection
