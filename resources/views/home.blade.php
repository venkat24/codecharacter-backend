@extends('base')

@section('links')
  <title>Home - CodeCharacter</title>
  <link rel="stylesheet" href="stylesheets/home.css">
@endsection

@section('main')

  <!-- First Band (Image) -->
    
  <div class="row" style="margin-top:10px;">
    <div class="twelve columns">
      <img src="images/splash.jpg" />
      <hr />
    </div>
  </div>
  
  
  <div class="row">
    <div class="twelve columns">
			<div class="container">
				<h1 class="text changing-heading" style="color:#00FF72" ></h1>
			</div>
    	<hr />
		</div>
  </div>
  <!-- Second Band (Image Left with Text) -->
  
  <div class="row">
    <div class="four columns">
      <img src="images/wizard.png" />
    </div>
    <div class="eight columns">
      <h4>Code Character</h4>
      <div class="row">
        <div class="six columns">
          <p>Welcome to Code Character!</p>
            <p>Set in a 2D world, each player is given an army that can be controlled with everyone’s favourite programming language, C++ :)</p>
            <p>The objective is simple enough. Implement a strategy that allows your army to capture the enemy’s flag more times than the enemy captures yours.</p>
            <p>Oh yeah, there’s a timer too.</p>
        </div>
        <div class="six columns">
          <p> The AIs could be as simple as a couple lines of code or as complex as rocket science. More than coding ability, you're going to need strategic logic and sheer determination. Good luck!</p>
          <em>
          <p style="text-align:center">"Unite these kingdoms. Conquer them with fire and sword. Bring them all under your rule.</p>
          <p style="text-align:center">Only where there is unity can there be peace."</p>
          </em>
          </strong>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="margin-top: 10px">
  <!-- Third Band (Image Right with Text) -->
  
    <div class="eight columns">
      <h4>Download the Simulator!</h4>
      
      <p>Before submitting your AI code, you can watch the game run on your desktop and make sure your army is fighting just the way you want. When you're ready, submit your code for a place on the Leaderboard!</p>
      
      <p>The Code Character Simulator is available on all popular platforms. Pick your poison!</p>
     <div style="text-align:center">
     @if (!Session::get('user_email'))
        <span class="os-span"><a href="/login"><img class="operatingSystemLogos" src="images/Linux.png"></a><div class="caption">Linux 64-bit</div></span>
        <span class="os-span"><a href="/login"><img class="operatingSystemLogos" src="images/Linux.png"></a><div class="caption">Linux 32-bit</div></span>
        <span class="os-span"><a href="/login"><img class="operatingSystemLogos" src="images/Windows.png"></a><div class="caption">Windows 64-bit</div></span>
     @else
        <span class="os-span"><a href="codechar_linux_x64.tar.gz"><img class="operatingSystemLogos" src="images/Linux.png"></a><div class="caption">Linux 64-bit</div></span>
        <span class="os-span"><a href="codechar_linux_ia32.tar.gz"><img class="operatingSystemLogos" src="images/Linux.png"></a><div class="caption">Linux 32-bit</div></span>
        <span class="os-span"><a href="codecharacter-renderer-win32-x64.zip"><img class="operatingSystemLogos" src="images/Windows.png"></a><div class="caption">Windows 64-bit</div></span>
     @endif
     </div>
          
    </div>
    <div class="four columns">
      <img src="images/swordsman.png" />
    </div>
  </div>
  <script src="javascripts/home.js"></script>
@endsection
