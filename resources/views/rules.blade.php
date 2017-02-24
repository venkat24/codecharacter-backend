@extends('base')

@section('links')
  <title>ReadMe - CodeCharacter</title>
  <link rel="stylesheet" href="stylesheets/docs.css">
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
<li>Requirements: <a href="https://cmake.org/download/" target="_blank">CMake</a>, <a href="https://www.gnu.org/software/make/" target="_blank">Make</a>, <a href="https://gcc.gnu.org/" target="_blank">g++</a> </li>
<li>Download the simulator to get started with locally developing and testing your very own AI.</li>
<li>Extract the simulator and from the extracted root, run <code>./codecharacter-renderer</code>.</li>
<li>You now have a working Code Character Simulator! Your AI is the one in red, at the top left.</li>
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
<li><code>Ctrl + C</code> opens the console, <code>Ctrl + F</code> makes it full screen and <code>Ctrl + L</code> clears the console. You can print stuff to the console for debugging. Only the last 75 items you printed are available, the rest are deleted as more items are printed.</li>
<li>The score is available at the bottom right corner of the screen.</li>
</ol>
<h2><a id="Making_your_own_AI_23"></a>Making your own AI: (Linux)</h2>
<ol>
<li><code>cd</code> from the project root to <code>resources/app/src/player1</code>.</li>
<li>A sample AI is already present, with some helper functions.</li>
<li>Your header files go in /include, and your cpp files go in /src</li>
<li>Your main code goes in <code>./src/player1.cpp</code>. Find <code>Player1::Update</code>. This is the function you need to add stuff to. It gets called every tick. There's already some code present to help you get started.</li>
<li>Once you’re done, run <code>./install.sh</code> from the player1 folder root.</li>
<li>Tada, you’ve built your very own AI! You can test it out with the renderer by running <code>./codecharacter-renderer</code> from the simulator root.</li>
</ol>

<h2><a id="Making_your_own_AI_33"></a>Making your own AI: (Windows)</h2>
<ol style="list-style-type: decimal">
<li><code>cd</code> from the project root to <code>resources\app</code><br />
</li>
<li>Run the CMake installer present there. Ensure that you select the option to add CMake to the path for at least your user.</li>
<li>Run the CodeBlocks installer also present there. Ensure that you do a full install. Install it to <code>C:\CodeBlocks</code>, otherwise it won't work.</li>
<li>Run install.bat also present there.</li>
<li><code>cd</code> to <code>src\player1</code></li>
<li>A sample AI is already present, with some helper functions.</li>
<li>Your header files go in <code>.\include</code>, and your cpp files go in <code>.\src</code>.</li>
<li>Your main code goes in <code>.\src\player1.cpp</code>. Find <code>Player1::Update</code>. This is the function you need to add stuff to. It gets called every tick. There's already some code present to help you get started.</li>
<li>Once you’re done, run build.bat from the player1 folder root.</li>
<li>Tada, you’ve built your very own AI! You can test it out with the renderer by running <code>codecharacter-renderer</code> from the simulator root.</li>
</ol>
    </div>
</div>

  <script src="javascripts/docs.js"></script>
@endsection
