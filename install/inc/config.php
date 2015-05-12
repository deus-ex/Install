<?php

  // $config = json_decode( json_encode( $config ), FALSE );
  function generate_random_string( $length = 10 ) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen( $characters );
      $randomString = '';
      for ( $i = 0; $i < $length; $i++ ) {
          $randomString .= $characters[rand( 0, $charactersLength - 1 )];
      }
      return $randomString;
  }

  $config = array(
    'app_name' => 'Jencube App',
    'app_website' => ' https://github.com/deus-ex/Install',
    'title' => 'Jencube App Installation Wizard',
    'version' => '1.10.14',
    'description' => 'This wizard will guide you through the installation process',

    'company' => 'Jencube Limited',
    'website' => 'www.jencube.com',

    'copyright' => '<a href="http://www.jencube.com" target="_blank">Jencube Limited</a> &copy; ' . date( 'Y' ),

    'show_stages' => TRUE,
    'show_back_btn' => TRUE,
    'show_cancel_btn' => TRUE,

    // Default
    'template' => 'default',
    'language' => 'en-us',

    'config_dir' => 'inc/',
    'config_file' => '',
    'cache_dir' => 'cache/',

    'db_type' => 'mysql',
    'db_charset' => 'utf8',
    'db_collation' => 'utf8_general_ci',
    'en_key' => generate_random_string(),

  );

  // $config = json_decode( json_encode( $config ), FALSE );

?>