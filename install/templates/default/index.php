<?php

  $this->pageInfo['error'] = NULL;
  if ( isset( $_POST['submit'] ) ) {

    $validate = ( isset( $this->page['validate'] ) ) ? $this->page['validate'] : NULL;

    if ( $this->process_form( $_POST, $validate, $this->pageInfo['current_stage'] ) ) {
      $nextPage = $this->pageInfo['current_stage'];
      $this->pageInfo['error'] = NULL;
      header( "Location: " . APP_URI . $nextPage );

    } else {

      $this->pageInfo['error'] = $this->errors();

    }

  }

?>
<!DOCTYPE html>
<html>

	<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale = 1.0" />

    <title><?php echo $this->config['title']; ?> - <?php echo $this->pageInfo['stage_title']; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.png" />

    <!-- Favicon apple retina display -->
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />

    <link type="text/css" rel="stylesheet" href="templates/<?php echo $this->config['template']; ?>/reset.css" />
    <link type="text/css" rel="stylesheet" href="templates/<?php echo $this->config['template']; ?>/stylesheet.css" />

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script src="js/modernizr.js"></script>
    <script src="js/placeholder-fix.js"></script>

	</head>

	<body>

    <div id="page-wrap">

      <div id="header-wrap">

        <h1><?php echo $this->config['app_name']; ?> <?php if ( !empty( $this->config['version'] ) ): echo ' v' . $this->config['version']; endif; ?> installation process</h1>
        <span><?php echo $this->config['description']; ?></span>

      </div>
      <!-- END header-wrap -->

      <div id="content-wrap">

        <div id="sidebar">

          <ul>
            <?php $this->stage_nav(); ?>
          </ul>

          <div id="sidebar-footer" style="padding-top: <?php $currStage = $this->pageInfo['current_stage']; if ( $currStage == '6' ) { echo '600px'; } else if ( $currStage == '3' ) { echo '420px'; } else if ( $currStage == '8' ) { echo '250px'; } else if ( $currStage == '2' ) { echo '160px'; } else { echo '20px'; } ?>">

            <h2>Jencube</h2>
            <p>Installation Wizard 1.0.14</p>

          </div>
          <!-- END sidebar-footer -->

        </div>
        <!-- END sidebar -->

        <div id="content">

          <h3><?php echo ( ( $this->config['show_stages'] ) ? 'Stage ' . $this->pageInfo['current_stage'] . ' out of ' . $this->pageInfo['total_stages'] . ' - ' : '' ) . $this->pageInfo['stage_title']; ?> </h3>

          <?php if ( isset( $this->pageInfo['error'] ) && $this->pageInfo['error'] ): ?>
            <div class="error">
              <?php echo $this->pageInfo['error']; ?>
            </div>
          <?php endif; ?>

          <?php

            if ( $this->id == 0 ) {
              $url = APP_URI;
            } else {
              $url = APP_URI . ( $this->pageInfo['current_stage'] - 1 );
            }

          ?>
          <form id="installation-wizard" action="<?php echo $url; ?>" method="POST" name="installation-wizard">

            <?php if ( isset( $this->page['fields'] ) && is_array( $this->page['fields'] ) ) { ?>

              <?php foreach ( $this->page['fields'] as $field ) { ?>

                <?php if ( isset( $field['type'] ) && $field['type'] == 'info' ): ?>
                  <p><?php echo $field['value']; ?></p>
                <?php elseif ( isset( $field['type'] ) && $field['type'] == 'header' ): ?>
                  <h2><?php echo $field['value']; ?></h2>
                <?php elseif ( isset( $field['type'] ) && $field['type'] == 'php-config' ): ?>

                  <?php if ( $this->pageInfo['current_stage'] == 3 ): ?>
                    <div class="info"><a class="good">Good</a> <a class="ok">Ok</a> <a class="bad">Bad</a></div>
                  <?php endif; ?>

                  <?php echo php_config( $field ); ?>

                <?php elseif ( isset( $field['type'] ) && $field['type'] == 'file-permissions' ): ?>

                  <?php echo php_config( $field ); ?>

                <?php elseif ( isset( $field['type'] ) && $field['type'] == 'php-modules' ): ?>

                  <?php echo php_config( $field ); ?>

                <?php else: ?>

                  <?php echo input_fields( $field ); ?>

                <?php endif; ?>

              <?php } ?>

            <?php } ?>

            <div id="form-footer">

              <?php if ( $this->config['show_back_btn'] && $this->pageInfo['current_stage'] > 1 && $this->pageInfo['current_stage'] != $this->pageInfo['total_stages'] ): ?>
                  <input type="button" id="back" name="back" value=" Back " />
                <?php endif; ?>

                <?php if ( $this->pageInfo['current_stage'] < $this->pageInfo['total_stages'] ): ?>

                  <?php if ( $this->pageInfo['current_stage'] == 7 ): ?>
                    <input type="submit" name="submit" value=" Install " />
                  <?php elseif ( $this->pageInfo['current_stage'] == 8 ): ?>
                    <input type="submit" name="submit" value=" Create " />
                  <?php else: ?>
                    <input type="submit" id="next" name="submit" value=" Next " />
                  <?php endif; ?>

                  <?php if ( $this->config['show_cancel_btn'] && ( $this->pageInfo['total_stages'] != $this->pageInfo['current_stage'] ) ): ?>
                  <?php endif; ?>

                <?php endif; ?>

                <?php if ( $this->pageInfo['total_stages'] == $this->pageInfo['current_stage'] ): ?>
                  <input type="button" id="finish" name="submit" value=" Finish " />
                <?php endif; ?>

            </div>
            <!-- END form-footer -->

          </form>

          <script type="text/javascript">

            $(function(){

              <?php if ( $this->pageInfo['current_stage'] == 2 ): ?>

                if ( $( '#install_agree' ).is( ":checked" ) ) {
                  $( '#next' ).fadeIn( 'slow' );
                } else {
                  $( '#next' ).fadeOut( 'slow' );
                }

                $( '#install_agree' ).change( function(){
                  if ( $(this).is( ":checked" ) ) {
                    $( '#next' ).fadeIn( 'slow' );
                  } else {
                    $( '#next' ).fadeOut( 'slow' );
                  }

                });

              <?php elseif ( $this->pageInfo['current_stage'] == 6 ): ?>

                $( '#install_test_connection' ).click( function() {
                  var type = $('#install_db_type').val();
                  var prefix = $('#install_db_prefix').val();
                  var host = $('#install_db_hostname').val();
                  var username = $('#install_db_username').val();
                  var password = $('#install_db_password').val();
                  var name = $('#install_db_name').val();

                  if ( type.length == 0 || host.length == 0 || username.length == 0  || name.length == 0 ) {
                    alert( 'Please fill in the required fields.' );
                  } else {

                    $.ajax({
                          type: "POST",
                          url: "<?php echo APP_URL . '/inc/db.php'; ?>",
                          data: "type="+ type + "&prefix=" + prefix + "&host=" + host + "&username=" + username + "&password=" + password + "&name=" + name,
                          success: function( msg ) {
                            alert( msg );
                          }
                    });

                  }

                });

              <?php endif; ?>

              <?php if ( $this->config['show_back_btn'] && $this->pageInfo['current_stage'] > 1 && $this->pageInfo['current_stage'] != $this->pageInfo['total_stages'] ): ?>

                $( "#back" ).click( function() {
                  window.location.href = "<?php echo APP_URL . $this->pageInfo['previous_stage']; ?>";
                });

              <?php endif; ?>
              <?php if ( $this->config['show_cancel_btn'] && ( $this->pageInfo['total_stages'] != $this->pageInfo['current_stage'] ) ): ?>

                $( "#cancel" ).click( function() {
                  window.location.href = "<?php echo APP_URL; ?>";
                });

              <?php endif; ?>
              <?php if ( $this->pageInfo['total_stages'] == $this->pageInfo['current_stage'] ): ?>

                $( "#finish" ).click( function() {
                  window.location.href = "<?php echo rtrim( ( isset( $_SESSION['install']['virtual_path'] ) ) ? $_SESSION['install']['virtual_path'] : '', '/' ); ?>";
                });

              <?php endif; ?>

            });

          </script>

        </div>
        <!-- END content -->

        <div class="clearfix"></div>

      </div>
      <!-- END content-wrap -->

    </div>
    <!-- END page-wrap -->

    <div id="footer-wrap">

      <span><?php echo $this->config['copyright']; ?></span>

    </div>
    <!-- END footer-wrap -->

	</body>

</html>