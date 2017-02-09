<!DOCTYPE html>

<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width" />

    <link rel="stylesheet" href="{{asset('stylesheets/foundation.min.css')}}">
    <link rel="stylesheet" href="{{asset('stylesheets/app.css')}}">
    <link rel="stylesheet" href="{{asset('stylesheets/base.css')}}">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/vue/dist/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>
    <script src="{{asset('javascripts/modernizr.foundation.js')}}"></script>

    <!-- This stuff has to move to the backend -->
    <script id="constants">
      var PRAGYAN_BASE_URL = "{{env('PRAGYAN_BASE_URL')}}";
      var SITE_BASE_URL = "{{env('SITE_BASE_URL')}}";
      var USER_DATA = {
        teamName  : "{{Session::get('team_name')}}",
        userName  : "{{Session::get('user_fullname')}}",
        userEmail : "{{Session::get('user_email')}}",
      };
    </script>
    @yield('links')
</head>
@yield('pre-main')
<body>
 <header>
	<nav>
	  <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/rules">Readme</a></li>
        <li><a href="https://jhurricane96.github.io/code_character_player_docs/_build/html/index.html" target="_blank">Docs</a></li>
        <li><a href="/leaderboard">Scores</a></li>
        @if (Session::get('user_email'))
        <li><a href="/notifications">Alerts</a></li>
        <li><a href="/submit">Submit</a></li>
        <li><a href="/teams">Team</a></li>
        <li><a href="#" onclick="logout()">Logout</a></li>
        @else
        <li><a href="/login">Login</a></li>
        @endif
	  </ul>
	</nav>
	</header> 
  <div class="row">
    @if ( Session::get('user_fullname') != "" )
    <div class="top-tag" id="event-tag" style="float: left">
      Welcome {{Session::get('user_fullname')}}  
    </div>
    @endif
    @if ( Session::get('team_name') != "" )
    <div class="top-tag" style="float: right">
      Team {{Session::get('team_name')}}  
    </div>
    @endif
  </div>

  @yield('main')
  
  <!-- Footer -->
  
  <footer class="row">
    <div class="twelve columns">
      <hr />
      <div class="row">
        <div class="six columns">
          <p>&copy; Copyright <a href="http://pragyan.org">Pragyan</a> 2017. Made with ♥ by <a href="http://deltaforce.club">Delta Force</a></p>
        </div>
        <div class="six columns">
          <p style="text-align:right"><a href="https://www.digitalocean.com/"><img style="height:35px" src="{{asset('images/digitalOcean.png')}}"></a></p>
        </div>
      </div>
    </div> 
  </footer>
  
  <script src="{{asset('javascripts/foundation.min.js')}}"></script>
  <script src="{{asset('javascripts/base.js')}}"></script>
  <script src="{{asset('javascripts/login.js')}}"></script>
  <script src="{{asset('javascripts/app.js')}}"></script>
</body>
</html>

