<!DOCTYPE html>

<head>
  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width" />

  <title>Welcome to Foundation | Banded</title>

  <link rel="stylesheet" href="stylesheets/foundation.min.css">
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="stylesheets/home.css">

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script src="javascripts/modernizr.foundation.js"></script>

</head>
<body>
  
  <!-- Header and Nav -->
  
  <div class="row">
    <div class="three columns">
      <h1><img src="http://placehold.it/400x100&text=Logo" /></h1>
    </div>
    <div class="nine columns">
      <ul class="nav-bar right">
        <li><a href="#">Teams</a></li>
        <li><a href="#">Rules</a></li>
        <li><a href="#">Documentation</a></li>
        <li id="login-button"><a href="#">Login</a></li>
      </ul>
    </div>
  </div>

  @yield('main')
  
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
</body>
</html>

