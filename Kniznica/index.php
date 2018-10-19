<?php

include('functions.php');

?>
<DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">

  <link rel="stylesheet" href="style.css">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

  <link rel="stylesheet" href="color_scheme.css">
  <link rel="stylesheet" href="style.css">

</head>
<body>

  <div class="jumbotron text-center w3-theme" style="margin-bottom:0">
    <h1>Media Block Player: Shared database</h1>
  </div>

  <div class='container'>
    <div class='jumbotrone w3-margin text-center'>
      <p>
        <h4>This is a shared database of knowledge compatible with our online training application that you can find: HERE</h4>
      </p>
    </div>
  </div>

<div class='container table-responsive'>
  <table class="table table-striped table-bordered">
    <?php
      get_media_files();
    ?>
</div>

<footer class='container-fluid w3-theme-l4 text-center w3-padding'>

  <small>&copy; Created by 'Prva skupina v zozname' as a school project, 2018</small>

</foooter>

</body>
</html>
