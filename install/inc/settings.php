<?php

  include APP_DIRECTORY . 'inc/config.php';
  include APP_DIRECTORY . 'inc/language.class.php';
  include APP_DIRECTORY . 'inc/functions.php';
  include APP_DIRECTORY . 'inc/db.class.php';
  include APP_DIRECTORY . 'inc/install.class.php';
  include APP_DIRECTORY . 'inc/stages.php';

  $GLOBALS['install'] = new Jencube_installer();

  $install->template = APP_DIRECTORY . 'templates/default/';

?>