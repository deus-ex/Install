<?php

  /**
  *
  *	This jencube installer class is licensed to Jencube Limited for
  * application installation and setup.
  *
  *	@version:       1.0.14
  *	@author:        Jay & Ama
  *	@license:       http://opensource.org/licenses/gpl-license.php
  *                 GNU General Public License (GPL)
  * @copyright:     Copyright (c) 2014 Jencube
  * @twitter:       @deusex0 & @One_Oracle
  *
  **/

  class Jencube_installer {

    /**
    *
    * Database connection link
    *
    * @access protected
    * @var integer|bool
    *
    **/
    protected $db;

    /**
    *
    * Installation Stage ID
    *
    * @access public
    * @var integer
    *
    **/
    public $id;

    /**
    *
    * Installation Stage list
    *
    * @access public
    * @var array
    *
    **/
    public $stage;

    /**
    *
    * Installation Configuration
    *
    * @access public
    * @var array
    *
    **/
    public $config;

    /**
    *
    * Page details
    *
    * @access public
    * @var array
    *
    **/
    public $page;

    /**
    *
    * Page inforamtion details
    *
    * @access public
    * @var array
    *
    **/
    public $pageInfo;


    /**
    *
    * Date format
    *
    * @access public
    * @var string
    *
    **/
    public $dateFormat = 'Y-m-d h:i:s';

    /**
    *
    * Configuration default filename
    *
    * @access protected
    * @var string
    *
    **/
    protected $configFileName = 'config.php';

    /**
    *
    * SQL filename
    *
    * @access public
    * @var string
    *
    **/
    public $SQLFileName = 'sql/data.sql';


    /**
    *
    * SQL for admin details filename
    *
    * @access public
    * @var string
    *
    **/
    public $adminSQL = 'sql/user.sql';

    /**
    *
    * Base url of the app
    *
    * @access public
    * @var string
    *
    **/
    public $basePath;

    /**
    *
    * Set config folder in the root directory
    *
    * @access private
    * @var string
    *
    **/
    private $includeDir = 'inc/';

    /**
    *
    * Installer template
    *
    * @access public
    * @var string
    *
    **/
    public $template = 'templates/default/';

    /**
    *
    * The last error during query
    *
    * @access protected
    * @var array
    *
    **/
    protected $errorMsg = array();

    /**
    *
    * Class constructor initialization to set the class
    * properties and connection to the database
    *
    * @access public
    *
    **/

    public function __construct() {
      global $config, $stage;
      $this->config = $config;
      $this->stage = $stage;
      $this->id = ( isset( $_GET['stage'] ) ) ? $_GET['stage'] : '0';
      $previous = ( $this->id == '0') ? '' : $this->id - 1;
      $this->lang = new Language();
      $this->pageInfo = array(
            'page_url' => APP_URL,
            'stage_title' => $this->stage[$this->id]['name'],
            'total_stages' => count( $this->stage ),
            'current_stage' => $this->id + 1,
            'previous_stage' => ( $previous == '0') ? '' : $previous
        );

      $this->page = $this->stage[$this->id];
      $this->basePath = rtrim( preg_replace( '#/install/$#', '', APP_DIRECTORY ), '/' ) . '/';

      if ( ! empty( $this->config['config_dir'] ) )
        $this->includeDir = $this->config['config_dir'];
      if ( ! empty( $this->config['config_file'] ) )
        $this->configFileName = $this->config['config_file'];

      if ( substr( $this->includeDir, -1 ) != '/' ) {
        $this->includeDir .= '/';
      }
      include $this->template . 'index.php';
    }

    /**
    *
    * Verify PHP Version
    *
    * @access public
    * @return bool -> False if failed to write to file
    * @param $version (float) -> Prefer version
    *
    **/
    public function php_version( $version = 5.0 ) {
      $PHPVersion = phpversion();
      if ( $PHPVersion < $version ) {
        $this->errorMsg[] = 'Your PHP version is ' . $PHPVersion . ' . Version ' . $version . ' or newer is required.';
        return FALSE;
      }
      return TRUE;
    }

   /**
    *
    * Verify PHP Version
    *
    * @access public
    * @return bool -> False if failed to write to file
    * @param $version (float) -> Prefer version
    * @param $db (string) -> Type of Database
    *
    **/
    public function db_version( $version = 5.0, $db = 'mysql' ) {
      $output = shell_exec( $db . ' -V' );
      preg_match( '@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version );
      $dbVersion = @$version[0]? $version[0] : -1;

      if ( $dbVersion < $version ) {

        if ( $dbVersion == -1 ) {
          $this->errorMsg[] = 'Unable to make connection to database';
          return FALSE;
        } else {
          $this->errorMsg[] = 'Your MySQL version is ' . $dbVersion . ' . Version ' . $version . ' or newer is required.';
          return FALSE;
        }

      }
      return TRUE;
    }

    /**
    *
    * Get installer stage list for navigation
    *
    * @access public
    * @return string
    *
    **/
    public function stage_nav() {
      $navList = '';
      foreach ( $this->stage as $key => $nav ) {
        $navList .= '<li ' . $this->current_stage( $key ) . '>' . $nav['name'] . '</li>';
      }
      echo $navList . '<li id="last"></li>';

    }

    /**
    *
    * Get the current page
    *
    * @access public
    * @return string
    * @param $id (string) -> Page id
    *
    **/
    public function current_stage( $id ) {
      $first = '';
      if ( $id == 0) {
        $first = ' first';
      }

      return ( $this->id == $id ) ? 'class="current' . $first . '"' : NULL;
    }

    public function check_mail_function() {
      if ( !function_exists( 'mail' ) ) {
         $this->errorMsg[] = 'PHP Mail function is not enabled!';
         return FALSE;
      }
      return TRUE;
    }

   /**
    *
    * Verify PHP GD functions
    *
    * @access public
    * @return bool -> False if failed to write to file
    *
    **/
    public function check_GD() {
      if ( !extension_loaded('gd') ) {
         $this->errorMsg[] = 'GD extension is not enabled!';
         return FALSE;
      }
      return TRUE;
    }

   /**
    *
    * Verify PHP safe mode status
    *
    * @access public
    * @return bool -> False if failed to write to file
    *
    **/
    public function safe_mode() {
      if ( ini_get( 'safe_mode' ) ) {
         $this->errorMsg[] = 'Please switch of PHP Safe Mode';
         return FALSE;
      }
      return TRUE;
    }

   /**
    *
    * Change application configuration folder and filename
    *
    * @access public
    * @param string $dir -> Application config dir name (example: include/)
    * @param string $file -> Application config file (example: config.inc.php)
    *
    **/
    public function set_app_config( $dir = NULL, $file = NULL ) {
      if ( ! empty( $dir ) ) {
        $this->includeDir = ( substr( $dir, -1 ) == '/' ) ? $dir : $dir . '/';
      }

      if ( ! empty( $file ) ) {
        $this->configFileName = $file;
      }
      return $this->includeDir . $this->configFileName;
    }

   /**
    *
    * Check database connection
    *
    * @access public
    * @return bool -> False if failed to write to file
    * @param $param (array) -> Database connection credentials
    *
    **/
    private function check_connection( $param ) {
      if ( ! is_array( $param ) )
        return FALSE;

        // $config = array(
        //   'db_type' => $param['db_type'],
        //   'db_host' => $param['db_host'],
        //   'db_name' => $param['db_name'],
        //   'db_user' => $param['db_username'],
        //   'db_pass' => $param['db_password']
        // );

        $this->db = new Database( $param );

        if ( ! $this->db ) {
          $this->errorMsg[] = $this->db->errors();
          return FALSE;
        }
        return TRUE;
    }

    /**
    *
    * Process form
    *
    * @access private
    * @return bool
    * @param $param (array) -> Submitted form fields.
    * @param $type (string) -> Type of installation.
    *
    **/
    private function install( $param, $type ) {
      if ( ! is_array( $param ) || empty( $type ) ) {
        $this->errorMsg[] = 'Invalid input/validation fields submission';
        return FALSE;
      }

      switch ( $type ) {
        case 'language':
          $fields = array(
            '{lang}' => $param['language']
            );
          break;
        case 'product':
          $fields = array(
            '{prod-name}' => $param['product_name'],
            '{prod-ver}' => $param['product_version']
            );
          break;
        case 'paths':
          $fields = array(
            '{vir-path}' => $param['virtual_path'],
            '{dir-path}' => $param['system_path']
            );
          break;
        case 'database':
          $dbName = $param['db_prefix'] . $param['db_name'];
          if ( $param['db_charset'] == 'utf8' ) {
            $collation = 'utf8_general_ci';
          } else if ( $param['db_charset'] == 'latin2' ) {
            $collation = 'latin2_general_ci';
          }
          $fields = array(
            '{db-pref}' => $param['db_prefix'],
            '{db-type}' => $param['db_type'],
            '{db-host}' => $param['db_hostname'],
            '{db-name}' => $dbName,
            '{db-user}' => $param['db_username'],
            '{db-pass}' => $param['db_password'],
            '{db-char}' => $param['db_charset'],
            '{db-coll}' => $collation,
            '{db-engn}' => $param['db_engine'],
            '{salt-key}' => $param['encrypt_key']
            );
          break;
        case 'cache':
          $cache = ( $param['db_cache'] == 1 ) ? 'TRUE' : 'FALSE';
          $fields = array(
            '{cache}' => $cache,
            '{cache-path}' => $param['cache_path']
            );
          break;
      }
      return $this->update_config( $fields );

    }

    /**
    *
    * Process form
    *
    * @access public
    * @return bool
    * @param $fields (array) -> Submitted form fields.
    * @param $validate (array) -> Fields to validate and type of validation.
    * @param $pageID (integer) -> Current Page ID.
    *
    **/
    public function process_form( $fields, $validate = NULL, $pageID ) {
      if ( ! is_array( $fields ) || ( ! is_null( $validate ) && ! is_array( $validate ) ) ) {
        $this->errorMsg[] = 'Invalid input/validation fields submission1';
        return FALSE;
      }

      if ( $this->validate_form( $fields, $validate ) ) {

        switch ( $pageID ) {
          case '1':
            return ( $this->install( $fields, 'language' ) ) ? TRUE : FALSE;
            break;
          case '2':
            return ( $this->install( $fields, 'product' ) ) ? TRUE : FALSE;
            break;
          case '3':
            return TRUE;
            break;
          case '4':
            if ( $this->install( $fields, 'paths' ) ) {
              $_SESSION['install'] = array(
                'virtual_path' => $fields['virtual_path'],
              );
              var_dump( $_SESSION['install']);
              return TRUE;
            }
            return FALSE;
            break;
          case '5':
            return ( $this->install( $fields, 'cache' ) ) ? TRUE : FALSE;
            break;
          case '6':
            if ( $this->install( $fields, 'database' ) ) {
              return ( $this->create_db() ) ? TRUE : FALSE;
            }
            break;
          case '7':
            $install = $this->create_tables();
            return ( $install ) ? TRUE : FALSE;
            break;
          case '8':
            $admin = $this->create_admin( $fields );
            if ( $admin ) {
              $_SESSION['install']['user_name'] = $fields['admin_user'];
              $_SESSION['install']['user_password'] = $fields['admin_pass'];
              $_SESSION['install']['user_email'] = $fields['admin_email'];
              return TRUE;
            }
            return FALSE;
            break;
        }

      }
      return FALSE;

    }

    /**
    *
    * Form Validation
    *
    * @access private
    * @return bool
    * @param $fields (array) -> Submitted form fields.
    * @param $validate (array) -> Fields to validate and type of validation.
    *
    **/
    public function validate_form( $fields, $validate = NULL ) {

      $notInUse = array( 'submit', 'next', 'product_name', 'product_version' );

      if ( ! is_null( $validate ) || is_array( $validate ) ) {

        foreach ( $fields as $inputKey => $inputValue ) {

          if ( ! in_array( $inputKey, array_keys( $notInUse) ) || in_array( $inputKey, array_keys($validate) ) ) {

            foreach ( $validate[$inputKey] as $validKey => $validValue ) {

              $result = $this->validate_input( $inputValue, $validKey, $validValue );

              if ( $result === FALSE ) {
                $this->errorMsg[] = ( is_array( $validValue ) ) ? $validValue['message'] : $validValue;
                return FALSE;
              }

            }
          }
        }

      }
      return TRUE;

    }

    /**
    *
    * Validate each form inputs
    *
    * @access public
    * @return bool
    * @param $value (string) -> Form input value.
    * @param $type (string) -> Type of validation.
    *
    **/
    private function validate_input( $value, $type, $extra = NULL ) {

      $regexes = array(
        'date' => "^[0-9]{1,2}[-/][0-9]{1,2}[-/][0-9]{4}\$",
        'amount' => "^[-]?[0-9]+\$",
        'number' => "^[-]?[0-9,]+\$",
        'alfanum' => "^[0-9a-zA-Z ,.-_\\s\?\!]+\$",
        'empty' => "[a-z0-9A-Z]+",
        'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
        'phone' => "^[0-9]{10,11}\$",
        'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
        'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
        'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
        '2digitopt' => "^\d+(\,\d{2})?\$",
        '2digitforce' => "^\d+\,\d\d\$",
        'anything' => "^[\d\D]{1,}\$"
      );

      if ( array_key_exists( $type, $regexes ) ) {
        $returnVal = filter_var( $value, FILTER_VALIDATE_REGEXP,
                      array(
                        "options" => array(
                          "regexp" => '!' . $regexes[$type] . '!i'
                        )
                      ) ) !== FALSE;
        return $returnVal;
      }

      $filter = FALSE;

      switch ( $type ) {
        case 'confirm':
            return ( $value != $extra['value'] ) ? FALSE : TRUE;
        break;
        case 'email':
            $value = substr( $value, 0, 254 );
            $filter = FILTER_VALIDATE_EMAIL;
        break;
        case 'int':
            $filter = FILTER_VALIDATE_INT;
        break;
        case 'boolean':
            $filter = FILTER_VALIDATE_BOOLEAN;
        break;
        case 'ip':
            $filter = FILTER_VALIDATE_IP;
        break;
        case 'url':
            $filter = FILTER_VALIDATE_URL;
        break;
      }
      return ( $filter === FALSE ) ? FALSE : ( filter_var( $value, $filter ) !== FALSE ) ? TRUE : FALSE;

    }

    /**
    *
    * Create config file
    *
    * @access private
    * @return bool
    * @param $param (array) -> Submitted form fields.
    * @param $configPath (string) -> Path where config file will be saved.
    *
    **/
    private function create_config( $param, $configPath = NULL ) {

      if ( is_null ( $configPath ) ) {

        $configPath = $this->basePath . $this->includeDir . $this->configFileName;
      }

      if ( ! $this->check_connection( $param ) ) {
        $this->errorMsg[] = 'Unable to connect to database. Please check the details entered';
        return FALSE;
      }

      $configFileContent = "<?php
        $config = array(
          'db_type' => '" . $param['dbtype'] . "',
          'db_host' => '" . $param['dbhost'] . "',
          'db_name' => '" . $param['dbname'] . "',
          'db_user' => '" . $param['dbusername'] . "',
          'db_pass' => '" . $param['dbpassword'] . "',
          'db_charset' => '" . $param['dbcharset']  . "',
          'db_collation' => '" . $param['dbcollation']  . "',
          'db_engine' => '" . $param['dbengine']  . "',

          'cache' => '" . $param['cache']  . "',
          'cache_path' => '" . $param['cachepath']  . "',

          'default_lang' => '" . $param['language']  . "',

          'product_ver' => '" . $param['productver']  . "',
          'product_name' => '" . $param['productname']  . "',
        );
      ?>";

      if ( file_exists( $configPath ) ) {

        if ( ! is_writable( $configPath ) ) {
          $this->errorMsg[] = "<p>Sorry, installer unable to write to <b>" . $configPath . "</b>.
You will have to edit the file yourself. Here is what you need to insert in that file:<br /><br />
<textarea rows='5' cols='50' onclick='this.select();'>" . $configFileContent . "</textarea></p>";
          return FALSE;
        }

      }

      $openFile = @fopen( $configPath, 'wb' );
      @fwrite( $openFile, $configFileContent );
      @fclose( $openFile );
      @chmod( $configPath, 0666 );
      return TRUE;
    }

    /**
    *
    * Update config file
    *
    * @access private
    * @return bool
    * @param $param (array) -> Submitted form fields.
    * @param $configPath (string) -> Path where config file is saved.
    *
    **/
    private function update_config( $param, $configPath = NULL ) {

      if ( is_null ( $configPath ) ) {
        $configPath = $this->basePath . $this->includeDir . $this->configFileName;
      }

      if ( ! $this->check_connection( $param ) ) {
        $this->errorMsg[] = 'Unable to connect to database. Please check the details entered';
        return FALSE;
      }

      if ( ! file_exists( $configPath ) ) {

        $this->errorMsg[] = "<p>Sorry, unable to find configuration file: <b>" . $configPath . "</b>.";
        return FALSE;

      } else {

        if ( ! is_writable( $configPath ) ) {
          $this->errorMsg[] = "<p>Sorry, installer unable to write to <b>" . $configPath . "</b>.</p>";
          return FALSE;
        }

      }

      $configContent = @file_get_contents( $configPath );

      if ( $configContent === FALSE ) {
        $this->errorMsg[] = 'Unable to get ' . $this->configFileName . ' file content';
        return FALSE;
      }

      $newConfigContent = $this->replace_content( array_keys( $param ), $param, $configContent);

      if ( ! file_put_contents( $configPath, $newConfigContent ) ) {
        $this->errorMsg[] = "<p>Sorry, installer unable to write to <b>" . $configPath . "</b>.";
        return FALSE;
      }
      return TRUE;

    }

    /**
    *
    * Create user and database name
    *
    * @access private
    * @return bool
    *
    **/
    private function create_db() {

      $configPath = $this->basePath . $this->includeDir . $this->configFileName;
      $dbCreated = FALSE;

      include $configPath;

      $rootConfig = array(
        'db_type' => 'mysql',
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_pass' => ''
      );

      if ( ! $this->check_connection( $rootConfig ) ) {
        $this->errorMsg[] = 'Unable to connect to database. Please check the details entered';
        return FALSE;
      }

      $dbHost = ( isset( $config['db_host'] ) ) ? $config['db_host'] : '';
      $dbUser = ( isset( $config['db_user'] ) ) ? $config['db_user'] : '';
      $dbPass = ( isset( $config['db_pass'] ) ) ? $config['db_pass'] : '';
      $dbName = ( isset( $config['db_name'] ) ) ? $config['db_name'] : '';

      $createUser = $this->db->query( "CREATE USER '" . $dbUser . "'@'" . $dbHost . "' IDENTIFIED BY '" . $dbPass . "'" );
      $grantAccess = $this->db->query( "GRANT ALL PRIVILEGES ON * . * TO '" . $dbUser . "'@'" . $dbHost . "' IDENTIFIED BY '" . $dbPass . "' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0" );

      if ( ! $createUser && ! $grantAccess ) {
        $this->errorMsg[] = 'Unable to create database user. Please check the details entered';
        return FALSE;
      }

      if ( ! $this->db->select_db( $config['db_name'] ) ) {
        $createDB = $this->db->query( "CREATE DATABASE IF NOT EXISTS `" . $dbName . "`" );
        $dbCreated = ( $createDB ) ? TRUE : FALSE;

      } else {
        $dbCreated = TRUE;

      }

      if ( $dbCreated ) {
        $grantDBAccess = $this->db->query( "GRANT ALL PRIVILEGES ON `" . $dbName . "` . * TO '" . $dbUser . "'@'" . $dbHost . "'" );

        if ( ! $grantDBAccess ) {
          $this->errorMsg[] = 'Unable to grant "' . $dbUser . '" access to "' . $dbName . '" database. Please check the details entered.';
          return FALSE;
        }
        return TRUE;
      } else {
        $this->errorMsg[] = 'Unable to create "' . $dbName . '" database. Please check the details entered';
        return FALSE;
      }

    }

    /**
    *
    * Create database tables
    *
    * @access private
    * @return bool
    * @param $SQLPath (string) -> SQL file path.
    *
    **/
    private function create_tables( $SQLPath = NULL ) {

      $configPath = $this->basePath . $this->includeDir . $this->configFileName;
      include $configPath;

      if ( is_null ( $SQLPath ) ) {
        $SQLPath = APP_DIRECTORY . $this->SQLFileName;
      }

      if ( ! $this->check_connection( $config ) ) {
        $this->errorMsg[] = 'Unable to connect to database. Please check the details entered';
        return FALSE;
      }

      $SQLContent = @file_get_contents( $SQLPath, true );

      if ( $SQLContent == FALSE ) {
        $this->errorMsg[] = $SQLPath . ' file does not exists.';
        return FALSE;
      }

      $replace = array(
        '{db-prefix}' => $config['db_prefix'],
        '{db-engine}' => $config['db_engine'],
        '{db-charset}' => $config['db_charset'],
        '{db-collation}' => $config['db_collation']
      );

      foreach ( $replace as $find => $value ) {
        $SQLContent = $this->replace_content( $find, $value, $SQLContent );
      }

      if ( $this->run_sql( $SQLContent ) ) {
        return TRUE;
      }
      return FALSE;

    }

    /**
    *
    * Create application admin
    *
    * @access private
    * @return bool
    * @param $param (array) -> Submitted form fields.
    * @param $SQLPath (string) -> SQL file path.
    *
    **/
    private function create_admin( $param, $SQLPath = NULL ) {

      $configPath = $this->basePath . $this->includeDir . $this->configFileName;
      include $configPath;

      if ( is_null ( $SQLPath ) ) {
        $SQLPath = APP_DIRECTORY . $this->adminSQL;
      }

      if ( ! $this->check_connection( $config ) ) {
        $this->errorMsg[] = 'Unable to connect to database. Please check the details entered';
        return FALSE;
      }

      $SQLContent = @file_get_contents( $SQLPath, true );

      if ( $SQLContent == FALSE ) {
        $this->errorMsg[] = $SQLPath . ' file does not exists.';
        return FALSE;
      }

      $encryptPassword = md5( $param['admin_pass'] );

      $replace = array(
        '{db-prefix}' => $config['db_prefix'],
        '{admin-user}' => $param['admin_user'],
        '{admin-pass}' => $encryptPassword,
        '{admin-email}' => $param['admin_email'],
        '{register-date}' => date( $this->dateFormat )
      );

      foreach ( $replace as $find => $value ) {
        $SQLContent = $this->replace_content( $find, $value, $SQLContent );
      }

      if ( $this->run_sql( $SQLContent ) ) {
        return TRUE;
      }
      return FALSE;

    }

    /**
    *
    * Run SQL content
    *
    * @access private
    * @return bool
    * @param $content (string) -> SQL content.
    *
    **/
    private function run_sql( $content ) {
      $SQL = explode( ';', $content );

      foreach ( $SQL as $queryString ) {

        if ( ! empty( $queryString ) ) {

          $result = $this->db->query( $queryString );

          if ( ! $result ) {
            $this->errorMsg[] = 'Query execution terminated.<br /><em>Database Error: ' . $this->db->errors() . '<br /> Unable to run: <br />' . $queryString;
            return FALSE;
          }

        }
      }
      return TRUE;
    }

    /**
    *
    * Language list
    *
    * @access public
    * @return bool|array
    *
    **/
    public function languages() {
      $langDir = 'lang/';
      $langList = array();
      if ( is_dir( $landDir ) ) {
        if ( $openDir = opendir( $landDir ) ) {
          while ( ( $file = readdir( $openDir ) ) !== FALSE ) {
            if ( $file === '.' || $file === '..' || $file === 'index.php' )
              continue;
              $langList[] = $file;
          }
          closedir( $openDir );
        }
      }
      return $langList;

    }

    /**
    *
    * String replace file content
    *
    * @access private
    * @return string
    * @param $search (string!array) -> The value to find.
    * @param $replace (string|array) -> The replacement value that replaces found $search values.
    * @param $content (string|array) -> The string or array being searched and replaced on.
    *
    **/
    private function replace_content( $search, $replace = '', $content ) {
      return str_replace( $search, $replace, $content );
    }

    /**
    *
    * Show install errors
    *
    * @access public
    * @return string|bool.
    *
    **/
    public function errors() {
      // if ( $this->suppressErrors )
      //   return FALSE;

      foreach ( $this->errorMsg as $key => $value )
        return $value;

    }

  }


?>