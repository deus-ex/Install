<?php

  $mainUrl = rtrim( preg_replace( '#/install/$#', '', APP_URI ), '/' ) . '/';
  $mainDir = rtrim( preg_replace( '#/install/$#', '', APP_DIRECTORY ), '/' ) . '/';

	$stage = array(

		// Stage 1 - Select Language
		array(

			// Stage name
			'name' => 'Select your language',

      // fields to validate
      'validate' => array(
        'language' => array(
          'empty' => 'Please select a language.',
        ),
      ),

			// Items to be displayed
			'fields' => array(

				// Page instructions
        array(
          'type' => 'info',
          'value' => 'This wizard will guide you through the setup and configuration of ' . $config['app_name'] . ' With just a few clicks, you\'ll be on your way.',
        ),

        // Page instruction
				array(
					'type' => 'info',
					'value' => 'To begin, please select your preferred language and click on "Next" to continue',
				),

				// Language selection drop down box
				// will automatically scan for available languages
        // in the language folder and display them
				array(
					'type' => 'select',
					'label' => 'Language',
          'name' => 'language',
          'required' => TRUE,
					'items' => get_languages()
				),
			),
		),

		// Stage 2 - License Verification
		array(

			// stage name
			'name' => 'License verification',

      // fields to validate
      'validate' => array(
        'agree' => array(
          'empty' => 'Please you have to agreement to the term of agreement.'
        )

      ),

			// Items to be displayed
			'fields' => array(

				// Page instructions
				array(
					'type' => 'info',
					'value' => 'Welcome to the "' . $config['app_name'] . '" installation wizard.
					This automatic wizard will help you get the system up and running in just a couple of minutes.
					Please agree to the terms and conditions and then type in your purchased license number for the software.',
				),

        // Textarea
        array(
          'label' => '',
          'name' => 'license_agreement',
          'type' => 'textarea',
          'value' => license_agreement(),
          'attributes' => array(
            'style' => 'width: 100%; height: 200px;',
            'disabled' => 'disabled'
          ),
        ),

        // Checkbox
        array(
          'name' => 'agree',
          'type' => 'checkbox',
          'value' => '1',
          'title' => 'I agree to the above License Agreement',
          'attributes' => array(
            'checked' => ( isset( $_POST['agree'] ) ) ? 'checked' : '',
            )

        ),

        // hidden fields
        array(
          'type' => 'hidden',
          'name' => 'product_name',
          'value' => $config['app_name'],
        ),
        array(
          'type' => 'hidden',
          'name' => 'product_version',
          'value' => $config['version'],
        ),
			),
		),

		// Stage 3 - Verify server requirements
		array(

			// Stage name
			'name' => 'Server requirements',

			// Items to be displayed
			'fields' => array(

				// Page Instructions
				array(
					'type' => 'info',
					'value' => 'Before proceeding with the full installation, we will carry out some tests on your server configuration to ensure that you are able to install and run our software.
					Please ensure you read through the results thoroughly and do not proceed until all the required tests are passed.',
				),

				// Page Instructions
				array(
					'type' => 'info',
					'value' => 'These settings are recommended for PHP in order to ensure full compatibility with ' . $config['app_name'] . '. However, ' . $config['app_name'] . '! will still operate if your settings do not quite match the recommended configuration.',
				),

				// Check PHP configuration
        array(
          'type' => 'php-config',
          'title' => 'Required PHP settings',
          'header' => array(
              'Directive',
              'Recommended',
              'Your Server'
            ),

          // Item lists
          // Note: You can add more items
          'items' => array(
            'PHP Version' => array(
              'default' => '4.4.1',
              'min' => '4.0',
              'function' => phpversion(),
              'compare' => TRUE
            ), // PHP version must be at least 4.0
            'PHP Short Open Tag' => array(
              'default' => 'Off',
              'function' => ini_get( 'short_open_tag' ),
            ), // Display the value for "short_open_tag" setting
            'PHP Register Globals' => array(
              'default' => 'Off',
              'function' => ini_get( 'register_globals' ),
            ), // "register_globals" must be disabled
            'PHP Safe mode' => array(
              'default' => 'Off',
              'function' => ini_get( 'safe_mode' ),
            ), // "safe_mode" must be disabled
            'Max filesize for uploads' => array(
              'default' => '4M',
              'min' => '3M',
              'function' => ini_get( 'upload_max_filesize' ),
              'compare' => TRUE
            ), // "upload_max_filesize" must be at least 2mb
          ),
        ),

        // Check file permission on config.php
        // Note: You can add more if you want
        array(
          'type' => 'file-permissions',
          'title' => 'Folders and files Permissions',
          'header' => array(
              'Directive',
              'Recommended',
              'Your Server'
          ),
          'items' => array(
            'Config file Writable' => array(
              'default' => 'Yes',
              'priority' => '1',
              'function' => is_writable( $mainDir . $config['config_dir'] . 'config.php' ),

            ),
          ),
        ),

        // Check php modules that your application requires
        // Note: You can add more if you want
        array(
          'type' => 'php-modules',
          'title' => 'Required PHP modules',
          'header' => array(
              'Directive',
              'Recommended',
              'Your Server'
          ),
          'items' => array(
            'MySQL' => array(
              'default' => 'Yes',
              'function' => function_exists( 'mysql_connect' ),
            ),
            'MySQLi' => array(
              'default' => 'Yes',
              'function' => function_exists( 'mysqli_connect' ),
            ),
            'PostgreSQL' => array(
              'default' => 'Yes',
              'function' => function_exists( 'pg_connect' ),
            ),
          ),
        ),
      ),
		),

		// Stage 4 - Installation path
		array(

			// stage name
			'name' => 'Installation paths',

      // fields to validate
      'validate' => array(
        'virtual_path' => array(
          'empty' => 'Please enter virtual path for ' . $config['app_name'] . '.'
        ),
        'system_path' => array(
          'empty' => 'Please enter physical path for ' . $config['app_name'] . '.'
        )
      ),

			// Items to be displayed
			'fields' => array(

				// Page Instructions
				array(
					'type' => 'info',
					'value' => 'We\'ve detected the following server paths where ' . $config['app_name'] . ' datas are stored. Most probably you will not have to create them.',
				),

				// Text box
				array(
					'type' => 'text',
					'label' => 'Virtual Path (URL)',
					'name' => 'virtual_path',
					'value' => $mainUrl, // set default value
          'tips' => 'Virtual path to your main ' . $config['app_name'] . ' directory WITH trailing slash.',
          'attributes' => array(
              'style' => 'width: 70%',
              'readonly' => 'readonly'
            ),
          'required' => TRUE,
				),

				// Text box
				array(
					'type' => 'text',
					'label' => 'Physical path',
					'name' => 'system_path',
					'value' => $mainDir,
          'tips' => 'Physical path to your main ' . $config['app_name'] . ' directory WITH trailing slash.',
          'attributes' => array(
              'style' => 'width: 70%',
              'readonly' => 'readonly'
            ),
          'required' => TRUE,
				),
			),
		),

    // Stage 5 - Cache management
    array(

      // Stage name
      'name' => 'Cache Management',

      // fields to validate
      'validate' => array(
        'db_cache' => array(
          'empty' => 'Please select an option for ' . $config['app_name'] . ' database cache.'
        ),
        'cache_path' => array(
          'empty' => 'Please enter cache path for ' . $config['app_name'] . '.'
        )
      ),

      // Items to be displayed
      'fields' => array(

        // Page Instructions
        array(
          'type' => 'info',
          'value' => 'We have automatically predefined the paths required by the system. Please make sure everything is correct before you continue on to the next step.',
        ),

        // Select element
        array(
          'type' => 'select',
          'label' => 'Database Cache',
          'name' => 'db_cache',
          'items' => array(
            '1' => 'On',
            '0' => 'Off',
          ),
        ),

        // Text box
        array(
          'type' => 'text',
          'label' => 'Cache Path',
          'name' => 'cache_path',
          'value' => $mainDir . $config['cache_dir'], // set default value
          'attributes' => array(
              'style' => 'width: 70%',
              'readonly' => 'readonly'
            ),
          'validate' => array(
            array('rule' => 'required'), // make it "required"
          ),
        ),
      ),
    ),

		// Stage 6 - Set up database
		array(

			// Stage name
			'name' => 'Set up database',

      // fields to validate
      'validate' => array(
        'db_type' => array(
          'empty' => 'Please select a database type.'
        ),
        'db_engine' => array(
          'empty' => 'Please select a database engine.'
        ),
        'db_prefix' => array(
          'empty' => 'Please enter a database prefix.'
        ),
        'db_hostname' => array(
          'empty' => 'Please enter database hostname.'
        ),
        'db_username' => array(
          'empty' => 'Please enter database username.'
        ),
        // 'db_password' => array(
        //   'empty' => 'Please enter database password.'
        // ),
        'db_name' => array(
          'empty' => 'Please enter database name.'
        )
      ),

			// Items to be displayed
			'fields' => array(

				// Page instructions
        array(
          'type' => 'info',
          'value' => 'Now you need to configure the database where most ' . $config['app_name'] . ' data will be stored. This database must already have been created and a username and password created to access it.',
        ),

        // Page instructions
        array(
          'type' => 'info',
          'value' => '<strong>Note:</strong> The installer will try to create the database automatically if not exists.',
        ),

        // Database type
        array(
          'type' => 'select',
          'label' => 'Database Type',
          'name' => 'db_type',
          'required' => TRUE,
          'tips' => 'The type of database your ' . $config['app_name'] . ' data will be store in. If you are unsure, leave it on MySQL.',
          'items' => array(
            'mysql' => 'MySQL',
            'mysqli' => 'MySQLi',
            'mssql' => 'MsSQL',
            'postgre' => 'PostgreSQL',
          ),
        ),

        // Database storage engine
        array(
          'type' => 'select',
          'label' => 'Database Storage Engine',
          'name' => 'db_engine',
          'required' => TRUE,
          'tips' => 'The type of storage engine to handle your database tables. For more information <a href="http://dev.mysql.com/doc/refman/5.0/en/storage-engines.html" target="_blank">visit</a>. If you are unsure, leave it on MyISAM.',
          'items' => array(
            'MyISAM' => 'MyISAM',
            'InnoDB' => 'InnoDB'
          ),
        ),

        // Database prefix
        array(
          'label' => 'Database Prefix',
          'name' => 'db_prefix',
          'type' => 'text',
          'value' =>( isset( $_POST['db_prefix'] ) ) ? $_POST['db_prefix'] : 'db_',
          'required' => TRUE,
          'tips' => 'This prefix will be added to database name to avoid name conflict in the database. If you are unsure, just use the default.',
          'attributes' => array(
              'style' => 'width: 15%'
          ),
        ),

				// Database hostname
				array(
					'label' => 'Database hostname',
					'name' => 'db_hostname',
					'type' => 'text',
          'value' =>( isset( $_POST['db_hostname'] ) ) ? $_POST['db_hostname'] : '127.0.0.1',
          'required' => TRUE,
          'tips' => 'The hostname of the server hosting the database. If you are unsure, 99.8% of times is localhost or 127.0.0.1.',
				),

				// Database username
				array(
					'label' => 'Database username',
					'name' => 'db_username',
					'type' => 'text',
          'value' =>( isset( $_POST['db_username'] ) ) ? $_POST['db_username'] : 'root',
          'required' => TRUE,
          'tips' => 'The username of the user with permissions to the database',
				),

				// Database password
				array(
					'label' => 'Database password',
					'name' => 'db_password',
					'type' => 'text',
          'value' =>( isset( $_POST['db_password'] ) ) ? $_POST['db_password'] : '',
          // 'required' => TRUE,
          'tips' => 'The password of the user with permission to the database',
				),

				// Database name
				array(
					'label' => 'Database name',
					'name' => 'db_name',
					'type' => 'text',
          'value' =>( isset( $_POST['db_name'] ) ) ? $_POST['db_name'] : '',
          'required' => TRUE,
					'highlight_on_error' => false,
				),

        // Button input
        array(
          'name' => 'test_connection',
          'type' => 'button',
          'value' => 'Test Connection',
          'tips' => 'The name of database on the host. The installer will attempt to create the database if not exist',
        ),

        // Select element
        array(
          'type' => 'select',
          'label' => 'Database Charset',
          'name' => 'db_charset',
          'tips' => 'The email of the administrator\'s account',
          'items' => array(
            'utf8' => 'UTF-8',
            'latin2' => 'Latin2',
          ),
        ),

        // Text box
        array(
          'label' => 'Encryption Key',
          'name' => 'encrypt_key',
          'type' => 'text',
          'value' =>( isset( $_POST['encrypt_key'] ) ) ? $_POST['encrypt_key'] : $config['en_key'],
          'tips' => 'The encryption key plays a supplementary role to help secure important information. You don\'t need to change the default value.',
        ),
			),
		),

		// Stage 7 - Ready to install
		array(

			// Stage name
			'name' => 'Ready to install',

			// Items to be displayed
			'fields' => array(

				// Page instructions
				array(
					'type' => 'info',
					'value' => 'We are now ready to proceed with installation. At this step we will attempt to create all required tables and populate them with data. Should something go wrong, go back to the Database Settings step and make sure everything is correct.',
				),
			),

		),

		// Stage 8 - Administrator account
		array(

			// Stage name
			'name' => 'Administrator account',

      // fields to validate
      'validate' => array(
        'admin_email' => array(
          'empty' => 'Please enter administrator email address.',
          'email' => 'Administrator email address is invalid.',
        ),
        'admin_user' => array(
          'empty' => 'Please enter administrator username.',
        ),
        'admin_pass' => array(
          'empty' => 'Please enter administrator password.',
        ),
        'admin_pass2' => array(
          'empty' => 'Please enter administrator password (confirm).',
          'confirm' => array(
            'value' => ( isset( $_POST['admin_pass'] ) ) ? $_POST['admin_pass'] : '',
            'message' => 'Password do not match.',
          ),
        )
      ),

			// Items to be displayed
			'fields' => array(

				// Page instructions
				array(
					'type' => 'info',
					'value' => 'Database tables have been successfully created and populated with data!',
				),

        // Page instructions
				array(
					'type' => 'info',
					'value' => 'You may now set up an administrator account for yourself. This will allow you to manage the website through the control panel.',
				),

				// Text box
				array(
					'label' => 'Administrator Email',
					'name' => 'admin_email',
					'type' => 'text',
          'required' => TRUE,
          'value' =>( isset( $_POST['admin_email'] ) ) ? $_POST['admin_email'] : '',
          'tips' => 'The email of the administrator\'s account',
				),

        array(
          'label' => 'Administrator Username',
          'name' => 'admin_user',
          'type' => 'text',
          'required' => TRUE,
          'value' =>( isset( $_POST['admin_user'] ) ) ? $_POST['admin_user'] : 'administrator',
          'tips' => 'The email of the administrator\'s account',
        ),

				// Text box
				array(
					'label' => 'Administrator Password',
					'name' => 'admin_pass',
          'required' => TRUE,
					'type' => 'password',
          'value' =>( isset( $_POST['admin_pass'] ) ) ? $_POST['admin_pass'] : '',
          'tips' => 'The password of the administrator\'s account',
          'attributes' => array(
              'placeholder' => 'Password'
          ),
				),

				// Text box
				array(
					'label' => 'Administrator Password (confirm)',
					'name' => 'admin_pass2',
					'type' => 'password',
          'required' => TRUE,
          'value' =>( isset( $_POST['admin_pass2'] ) ) ? $_POST['admin_pass2'] : '',
          'tips' => 'Re-type the password of the administrator\'s account for confirmation',
          'attributes' => array(
              'placeholder' => 'Password'
          ),
				),
			),
		),

		// Stage 9 - Completed
		array(

			// Stage name
			'name' => 'Completed',

			// Items to be displayed
			'fields' => array(

				// Page instructions
				array(
					'type' => 'info',
					'value' => 'Setup has finished installing ' . $config['app_name'] . ' on your computer.<br />Administrator\'s account has been successfully created.',
				),
				array(
					'type' => 'info',
					'value' => $config['app_name'] . ' is accessible at <a href="' . rtrim( ( isset( $_SESSION['install']['virtual_path'] ) ) ? $_SESSION['install']['virtual_path'] : '', '/' ) . '" target="_blank" class="good rad3px">' . rtrim( ( isset( $_SESSION['install']['virtual_path'] ) ) ? $_SESSION['install']['virtual_path'] : '', '/' ) . '</a>' ),
				array(
					'type' => 'info',
					'value' => 'You may login using these details:',
				),
				array(
					'type' => 'info',
					'value' => 'Username: <a class="good rad3px">' . ( ( isset( $_SESSION['install']['user_name'] ) ) ? $_SESSION['install']['user_name'] : '' ) . '</a><br/><br/>
					Password: <a class="good rad3px">' . ( ( isset( $_SESSION['install']['user_password'] ) ) ? $_SESSION['install']['user_password'] : '' ) . '</a>',
				),
			),
		),

	);

?>
