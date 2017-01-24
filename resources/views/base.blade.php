<!DOCTYPE html>

<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width" />

    <link rel="stylesheet" href="{{asset('stylesheets/foundation.min.css')}}">
    <link rel="stylesheet" href="{{asset('stylesheets/app.css')}}">
    <link rel="stylesheet" href="{{asset('stylesheets/base.css')}}">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
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
  
  <!-- Header and Nav -->
  
  <div class="row">
    @if ( Session::get('user_fullname') != "" )
    <div style="float: right">
      Welcome {{Session::get('user_fullname')}}  
    </div>
    @endif
    @if ( Session::get('team_name') != "" )
    <br />
    <div style="float: right">
      You're part of team {{Session::get('team_name')}}  
    </div>
    @endif
    <div class="three columns">
      <h1>
      <a href="/">
        <img src="http://placehold.it/400x100&text=Logo" />
      </a>
      </h1>
    </div>
    <div class="nine columns">
      <ul class="nav-bar right">
        <li><a href="/rules">Rules</a></li>
        <li><a href="/leaderboard">Leaderboard</a></li>
        <li><a href="/docs">Documentation</a></li>
        @if (Session::get('user_email'))
        <li><a href="/notifications">Notifications</a></li>
        <li><a href="/submit">Submit</a></li>
        <li><a href="/teams">Your Team</a></li>
        <li><a href="#" onclick="logout()">Logout</a></li>
        @else
        <li><a href="/login">Login</a></li>
        @endif
      </ul>
    </div>
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
      </div>
    </div> 
  </footer>
  
  <script src="{{asset('javascripts/foundation.min.js')}}"></script>
  <script src="{{asset('javascripts/base.js')}}"></script>
  <script src="{{asset('javascripts/login.js')}}"></script>
  <script src="{{asset('javascripts/app.js')}}"></script>
</body>
</html>

