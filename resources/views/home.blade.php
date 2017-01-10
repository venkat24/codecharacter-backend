@extends('base')

@section('links')
  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width" />

  <title>Welcome to Foundation | Banded</title>

  <link rel="stylesheet" href="stylesheets/foundation.min.css">
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="stylesheets/home.css">

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script src="javascripts/modernizr.foundation.js"></script>
@endsection

@section('main')

  <!-- First Band (Image) -->
    
  <div class="row">
    <div class="twelve columns">
      <img src="http://placehold.it/1000x400&text=[img]" />
      <hr />
    </div>
  </div>
  
  
  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center">Code Character</h2>
      <hr />
    </div>
  </div>
  <!-- Second Band (Image Left with Text) -->
  
  <div class="row">
    <div class="four columns">
      <img src="http://placehold.it/400x300&text=[img]" />
    </div>
    <div class="eight columns">
      <h4>This is a content section.</h4>
      <div class="row">
        <div class="six columns">
          <p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit. Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine spare ribs reprehenderit culpa. Boudin aliqua adipisicing rump corned beef.</p>
        </div>
        <div class="six columns">
          <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
        </div>
      </div>
    </div>
  </div>
  
  
  <!-- Third Band (Image Right with Text) -->
  
  <div class="row">
    <div class="eight columns">
      <h4>This is a content section.</h4>
      
      <p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit. Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine spare ribs reprehenderit culpa. Boudin aliqua adipisicing rump corned beef.</p>
      
      <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
          
    </div>
    <div class="four columns">
      <img src="http://placehold.it/400x300&text=[img]" />
    </div>
  </div>

@endsection
