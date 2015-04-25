<?php

    /**
    *
    * Build input fields
    *
    * @return string
    * @param $field (array) -> Input fields and attributes
    * @param $prefix (string) -> Input fields id prefix
    *
    **/
    function input_fields( $field, $error = NULL, $prefix = 'install_' ) {
      if ( ! isset( $field['type'] ) ) {
        return FALSE;
      }

      $formInput = '';
      $attributes = '';
      if ( isset( $field['prelabel'] ) ) {
        $formInput .= $field['prelabel'];
      }
      if ( isset( $field['label'] ) || !empty( $field['label'] ) ) {
        $label = '<label for="' . $prefix . $field['name'] . '">' . $field['label'];
        if ( isset( $field['required'] ) && $field['required'] ):
          $label .= ' <i>*</i>';
        endif;
        $label .= '</label>';
        $formInput .= $label;
      }

      if ( isset( $field['prepend'] ) ) {
        $formInput .= $field['prepend'];
      }

      $value = ( isset( $field['value'] ) ) ? htmlentities( $field['value'], ENT_QUOTES, 'UTF-8' ) : '';
      if ( isset( $field['attributes'] ) ) {
        foreach ( $field['attributes'] as $attrKey => $attrValue ):
          $valueKey = htmlentities( $attrKey, ENT_QUOTES, 'UTF-8' );
          $getValue = htmlentities( $attrValue, ENT_QUOTES, 'UTF-8' );
          $attributes .= $valueKey . '="' . $getValue . '" ';
        endforeach;
      }

      if ( $field['type'] == 'text' ):

        $formInput .= '<input type="text" id="' . $prefix . $field['name'] . '" name="' . $field['name'] . '" value="' . $value . '" ' . $attributes . ' />';

      elseif ( $field['type'] == 'hidden' ):

        $formInput .= '<input type="hidden" id="' . $prefix . $field['name'] . '" name="' . $field['name'] . '" value="' . $value . '" ' . $attributes . ' />';

      elseif ( $field['type'] == 'button' ):

        $formInput .= '<input type="button" id="' . $prefix . $field['name'] . '" name="' . $field['name'] . '" value="' . $value . '" ' . $attributes . ' />';


      elseif ( $field['type'] == 'password' ):

        $formInput .= '<input type="password" id="' . $prefix . $field['name'] . '" name="' . $field['name'] . '" value="' . $value . '" ' . $attributes . ' />';

      elseif ( $field['type'] == 'textarea' ) :

        $formInput .= '<textarea id="' . $prefix . $field['name'] . '" name="' . $field['name'] . '" ' . $attributes . '>' . $value . '</textarea>';

      elseif ( $field['type'] == 'checkbox' ):

        if ( isset( $field['items'] ) ) {
          $formInput .= '<ul class="items">';
          $i = 0;
          foreach ( $field['items'] as $boxKey => $boxValue ):
            $checked = ( isset($field['value']) && @in_array($key, $field['value'] ) ) ? 'checked="checked"' : '';
            $valueKey = htmlentities( $optionKey, ENT_QUOTES, 'UTF-8' );
            $formInput .= '<li><label>';
            $formInput .= '<input type="checkbox" id="' . $prefix . $field['name'] . '_' . $i++ . '" name="' . $field['name'] . '[]" class="checkbox" value="' . $valueKey . '" ' . $checked . ' ' . $attributes . ' />' . $boxValue;
            $formInput .= '</label></li>';
          endforeach;
          $formInput .= '</ul>';
        } else {
            $formInput .= '';
            $formInput .= '<input type="checkbox" id="' . $prefix . $field['name'] . '" name="' . $field['name'] . '" class="checkbox" value="' . $value . '" ' . $attributes . ' />';
            $formInput .= '';
        }

      elseif ( $field['type'] == 'select' ):

        $formInput .= '<select id="' . $prefix . $field['name'] . '" name="' . $field['name'] . '" ' . $attributes . '>';
          foreach ( $field['items'] as $optionKey => $optionValue ):
            $select = ( isset( $field['value'] ) && $field['value'] == $optionKey ) ? 'selected="selected"' : '';
            $valueKey = htmlentities( $optionKey, ENT_QUOTES, 'UTF-8' );
            $getValue = htmlentities( $optionValue, ENT_QUOTES, 'UTF-8' );
            $formInput .= '<option value="' . $valueKey . '">' . $getValue . '</option>';
          endforeach;
        $formInput .= '</select>';

      endif;

      if ( isset( $field['append'] ) ) {
        $formInput .= $field['append'];
      }

      if ( isset( $field['title'] ) ) {
        $formInput .= '<span id="title"> ' . $field['title'] . '</span>';
      }

      if ( isset( $field['tips'] ) ) {
        $formInput .= '<em id="tips">' . $field['tips'] . '</em>';
      }

      return $formInput;

    }

    /**
    *
    * PHP Configuration check
    *
    * @return string
    * @param $field (array) -> Configuration array
    *
    **/
    function php_config( $field ) {
      $phpConfig = '';
      $fid = 0;

      if ( isset( $field['title'] ) || !empty( $field['title'] ) ) {
        $phpConfig .= '<h3>' . $field['title'] . '</h3>';
      }

      $phpConfig .= '<div class="grid">';

      if ( isset( $field['header'] ) || !empty( $field['header'] ) ) {
          $phpConfig .= '<div class="title_row">';
          $hid = 0;
          foreach ( $field['header'] as $header ) {
            $firstClass = ( $hid == 0 ) ? 'class="first"' : '';
            $hid++;
            $phpConfig .= '<span ' . $firstClass . '>' . $header . '</span>';
          }
          $phpConfig .= '</div>';
      }

      $sid = 0;
      foreach ( $field['items'] as $key => $value ):
        $firstClass = ( $sid == 0 ) ? 'class="first"' : '';
        $rowClass = ( $sid == 0 ) ? 'first ' : '';
        $colorClass = ( ( $sid++ ) % 2 ) ? 'even' : 'odd';
        $phpConfig .= '<div class="' . $rowClass . $colorClass . '">';
        $phpConfig .= '<span class="first">' . $key . '</span>';
        $default = $value['default'];
        $phpConfig .= '<span><a class="good">' . $default = $value['default'] .'</a></span>';
        $phpConfig .= '<span>';
        if ( isset( $value['compare'] ) && $value['compare'] ):
          if ( ctype_alnum( $value['default'] ) ) {
            $compare = strnatcmp( $value['function'], $value['default'] );
            $class = ( $compare >= 0 ) ? 'good' : ( strnatcmp( $value['function'], $value['min'] ) >= 0 ) ? 'ok' : 'bad';
          } else {
            $class = ( $value['function'] >= $value['default'] ) ? 'good' : ( $value['function'] >= $value['min'] ) ? 'ok' : 'bad';
          }

          $actualValue = $value['function'];
        else:
          if ( $value['default'] == 'On' || $value['default'] == 'Off' ) {
            $actualValue = ( $value['function'] == TRUE ) ? 'On' : 'Off';
          } else {
            $actualValue = ( $value['function'] == TRUE ) ? 'Yes' : 'No';
          }
          if ( $actualValue == $value['default'] ) {
            $class = 'good';
          } else {
            $class = ( isset( $value['priority'] ) && ( $value['priority'] == '1' ) ) ? 'bad' : 'ok';
          }
        endif;
        $phpConfig .= '<a class="' . $class . '">' . $actualValue .'</a></span>';
        $phpConfig .= '<div class="clear"></div>';
        $phpConfig .= '</div>';

      endforeach;
      $phpConfig .= '</div>';
      return $phpConfig;
    }

    /**
    *
    * Get license agreement file
    *
    * @return string
    * @return string|bool -> False if failed to read file.
    * @param $file (string) -> File/directory
    *
    **/
    function license_agreement( $file = 'license.txt' ) {
      global $config;
      $openFile = @fopen( $file, 'r' );
      if ( $openFile ) {
        $content = @fread( $openFile, @filesize( $file ) );
      } else {
        return FALSE;
      }

      @fclose( $openFile );
      return $content;
    }

    /**
    *
    * Test database connection
    *
    * @return bool -> False if connection failed.
    * @param $param (array) -> Database connection credentials.
    *
    **/
    function test_connection( $param ) {
      $dbName = ( isset( $param['name'] ) ) ? $param['name'] : '';
      $dbFullname = ( isset( $param['prefix'] ) && !empty( $param['prefix'] ) ) ? $param['prefix'] . $dbName : $dbName;
      $config = array(
        'db_type' => ( isset( $param['type'] ) ) ? $param['type'] : '',
        'db_host' => ( isset( $param['host'] ) ) ? $param['host'] : '',
        'db_name' => $dbFullname,
        'db_user' => ( isset( $param['username'] ) ) ? $param['username'] : '',
        'db_pass' => ( isset( $param['password'] ) ) ? $param['password'] : ''
      );

      $db = new Database( $config );

      if ( ! $db->connect() ) {
        $message = 'Connection error! No database available. Click Next to enable the installer create "' . $dbFullname . '" with the details provided.';
      } else {
        $message = 'Database connection success.';
      }
      echo $message;

    }

    /**
    *
    * Get installed languages
    *
    * @return array
    *
    **/
    function get_languages() {
      $lang = new Language();
      $langList = $lang->get_available_list();
      $langArray = array();

      foreach ( $langList as $key ) {
        $langArray[$key] = $lang->details( 'title', $key );
      }
      return $langArray;
    }

?>