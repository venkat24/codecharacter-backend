@extends('base')

@section('links')
  <title>Submit - Code Character</title>
  <link rel="stylesheet" href="stylesheets/submit.css">
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center">Submit Code</h2>
      <p>
        Last Submission Status : <span id="submission-status"></span>
      </p>
      <p>
        Compress your code into .zip format and upload it below.    
      </p>
      <p>
       Please check your previous submission status before submitting. If your status is WAITING or RUNNING, uploading new code will stop execution of your previously uploaded code and push you to the back of the queue.
      </p>
      <p>
        Ensure that you are uploading the following files and only the follwing files :
        <ul>
            <li>File1</li>
            <li>File2</li>
            <li>File3</li>
        </ul>
      </p>
      <hr />
    </div>
  </div>
  
  <div class="row">
    <div class="twelve columns">
      <h4 class="text-center">Upload</h4>
      <form enctype="multipart/form-data" id="code-submit-form" method="POST" action="/api/submit_code">
        <label>Upload Zip File
					<br />
          <input type="hidden" name="MAX_FILE_SIZE" value="314572800">
        	<input type="file" name="file" class="show-for-sr"/>
        </label>
        <input type="hidden" id="teamName" name="teamName" value="{{Session::get('team_name')}}" />
				<button class="button" type="submit">Submit</button>
      </form>
      <hr />
    </div>
  </div>
  <script src="javascripts/submit.js">
@endsection
