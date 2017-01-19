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
      var SITE_BASE_URL = "{{url('/')}}";
      var PRAGYAN_BASE_URL = "api.pragyan.org";
      var EVENT_ID = 31;
      var EVENT_SECRET = "THIS IS THE EVENT SECRET"; 
    </script>
    @yield('links')
</head>
@yield('pre-main')
<body>
  
  <!-- Header and Nav -->
  
  <div class="row">
    <div class="three columns">
      <h1><img src="http://placehold.it/400x100&text=Logo" /></h1>
    </div>
    <div class="nine columns">
      <ul class="nav-bar right">
        <li><a href="/rules">Rules</a></li>
        <li><a href="/leaderboard">Leaderboard</a></li>
        <li><a href="/docs">Documentation</a></li>
        <li><a href="/notifications">Notifications</a></li>
        <li><a href="/teams">Your Team</a></li>
        <li><a href="/login">Login</a></li>
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
        <div class="six columns">
          <ul class="link-list right">
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
            <li><a href="#">Link 4</a></li>
          </ul>
        </div>
      </div>
    </div> 
  </footer>
  <!-- Included JS Files (Compressed) -->
  <script src="{{asset('javascripts/foundation.min.js')}}"></script>
  <script src="{{asset('javascripts/base.js')}}"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="{{asset('javascripts/app.js')}}"></script>
</body>
</html>

