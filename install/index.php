<?php

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL | E_NOTICE );

  @session_start();

  $appDirectory = str_replace( '\\', '/', realpath( dirname( __FILE__ ) ) ) . '/';
  $appURL = str_replace( '\\', '/', 'http://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['PHP_SELF'] ) ) . '/';

  define ( 'APP_DIRECTORY', $appDirectory );
  define ( 'APP_URL', $appURL );

  include APP_DIRECTORY . 'inc/settings.php';

?>