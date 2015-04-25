<?php

  include 'db.class.php';
  include 'functions.php';

  $message = test_connection( $_POST );

  return $message;

?>