@extends('base')

@section('links')
  <title>Documentation - CodeCharacter</title>
  <link rel="stylesheet" href="{{asset('stylesheets/docs.css')}}">
@endsection

@section('main')

<div class="row">
    <div class="twelve columns">
<h1><a id="Instructions_0"></a>Instructions:</h1>
<h2><a id="Setup_2"></a>Setup:</h2>
<ol>
<li>Register for Pragyan</li>
<li>Login for the event with your Pragyan ID and password</li>
<li>Form a team if you wish. You can send invites to anyone else who has logged in at least once to the Code Character website.</li>
<li>Download the simulator to get started with locally developing and testing your very own AI.</li>
<li>Extract the simulator and from the extracted root, run <code>./codecharacter-renderer</code>.</li>
</ol>
<h2><a id="Renderer_controls_10"></a>Renderer controls:</h2>
<ol>
<li>Choose the AI difficulty you wish to play against.</li>
<li>In the top right corner, there are buttons.
<ul>
<li>‘X’ is to exit the game and return to the main menu.</li>
<li>There’s a play/pause toggle button.</li>
<li>There’s a restart button to restart the current level.</li>
<li>There’s a Line Of Sight toggle to toggle between the LOS of you, the enemy and fully revealed.</li>
<li>‘+’ and ‘-’ or scroll with mouse to zoom in/out.</li>
<li>Move your mouse or use the arrow keys to pan around the map.</li>
</ul>
</li>
<li><code>Ctrl + C</code> opens the console, <code>Ctrl + F</code> makes it full screen. You can print stuff to the console for debugging. Only the last 75 items you printed are available, the rest are deleted as more items are printed.</li>
<li>The score is available at the bottom right corner of the screen.</li>
</ol>
<h2><a id="Making_your_own_AI_23"></a>Making your own AI:</h2>
<ol>
<li><code>cd</code> from the project root to <code>resources/app/src/player1</code>.</li>
<li>A sample AI is already present, with some helper functions.</li>
<li>Your header files go in /include, and your cpp files go in /src</li>
<li>Once you’re done, run <code>./install.sh</code> from the player1 folder root.</li>
<li>Tada, you’ve built your very own AI! You can test it out with the renderer by running <code>./codecharacter-renderer</code> from the simulator root.</li>
</ol>
    </div>
</div>

  <script src="{{asset('javascripts/docs.js')}}"></script>
@endsection
