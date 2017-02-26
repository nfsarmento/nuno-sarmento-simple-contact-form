<?php

/**
 * Class form options
 *
 */

class NSSimpleContactForm {


  private $ns_simple_contact_form_options;
  private $options_about;
  private $options_report;

	public function __construct() {
    add_action('admin_enqueue_scripts', array($this, 'ns_scf_enqueue_scripts'));
		add_action( 'admin_menu', array( $this, 'ns_simple_contact_form_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ns_simple_contact_form_page_init' ) );
	}

  public function ns_scf_enqueue_scripts() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');
    wp_enqueue_script('ns-scf-scripts', NUNO_SARMENTO_SIM_CONTACT_URI . 'assets/js/custom.js', array( 'wp-color-picker' ), false, true );

  }


	public function ns_simple_contact_form_add_plugin_page() {


    add_submenu_page(
      'ns_simple_contact_form',
			__('Form Options', 'nuno-sarmento-simple-contact-form'),
      __('Form Options', 'nuno-sarmento-simple-contact-form'),
      'activate_plugins',
			'ns_simple_contact_form_options',
			array( $this, 'ns_simple_contact_form_create_admin_page' ) // function
		);

	}

	public function ns_simple_contact_form_create_admin_page() {

		$this->ns_simple_contact_form_options = get_option( 'ns_simple_contact_form_option_name' );
    $this->options_about = get_option( 'ns_scf_about' );
		$this->options_report = get_option( 'ns_scf_report' );
		$about_Screen = ( isset( $_GET['action'] ) && 'about' == $_GET['action'] ) ? true : false;
    $report_Screen = ( isset( $_GET['action'] ) && 'report' == $_GET['action'] ) ? true : false;
    ?>

    <style media="screen">
    .header__ns_nsss:after { content: " "; display: block; height: 29px; width: 15%; position: absolute;
      top: 3%; right: 25px; background-image: url(//ps.w.org/nuno-sarmento-social-icons/assets/icon-128x128.png?rev=1588574); background-size:128px 128px; height: 128px; width: 128px;
    }
    .header__ns_nsss{ background: white; height: 150px; width: 100%; float: left;}
    .header__ns_nsss h2 {padding: 35px;font-size: 27px;}
    @media only screen and (max-width: 480px) {
      .header__ns_nsss:after { content: " "; display: block; height: 29px; width: 15%; position: absolute;
        top: 6%; right: 25px; background-image: url(//ps.w.org/nuno-sarmento-social-icons/assets/icon-128x128.png?rev=1588574); background-size:50px 50px; height: 50px; width: 50px;
      }
    }
    </style>

		<div class="wrap">


      <div class="header__ns_nsss">
  			<h2><?php echo NUNO_SARMENTO_SIM_CONTACT_NAME; ?></h2>
  		</div>
  			<h2 class="nav-tab-wrapper">
  				<a href="<?php echo admin_url( 'admin.php?page=ns_simple_contact_form_options' ); ?>" class="nav-tab<?php if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'about' != $_GET['action']  && 'report' != $_GET['action'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Settings' ); ?></a>
  				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'about' ), admin_url( 'admin.php?page=ns_simple_contact_form_options' ) ) ); ?>" class="nav-tab<?php if ( $about_Screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Other Plugins' ); ?></a>
  				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'report' ), admin_url( 'admin.php?page=ns_simple_contact_form_options' ) ) ); ?>" class="nav-tab<?php if ( $report_Screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'System Report' ); ?></a>
  			</h2>

			<?php settings_errors(); ?>

      <form method="post" action="options.php">
 			 <?php
 				 if($about_Screen) {
 					settings_fields( 'ns_scf_about' );
 					do_settings_sections( 'ns-scf-setting-about' );

 				} elseif($report_Screen) {
 					settings_fields( 'ns_scf_report' );
 					do_settings_sections( 'ns-scf-setting-report' );

 				}else {
          settings_fields( 'ns_simple_contact_form_option_group' );
 					do_settings_sections( 'ns-simple-contact-form-admin' );
 					submit_button();
 				}
 			?>
 		</form>

		</div>
	<?php }


	public function ns_simple_contact_form_page_init() {
		register_setting(
			'ns_simple_contact_form_option_group', // option_group
			'ns_simple_contact_form_option_name', // option_name
			array( $this, 'ns_simple_contact_form_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'ns_simple_contact_form_setting_section', // id
			'Settings', // title
			array( $this, 'ns_simple_contact_form_section_info' ), // callback
			'ns-simple-contact-form-admin' // page
		);

		add_settings_field(
			'google_captcha_public_key_0', // id
			'Google Captcha Public Key', // title
			array( $this, 'google_captcha_public_key_0_callback' ), // callback
			'ns-simple-contact-form-admin', // page
			'ns_simple_contact_form_setting_section' // section
		);

		add_settings_field(
			'google_captcha_private_key_1', // id
			'Google Captcha Private Key', // title
			array( $this, 'google_captcha_private_key_1_callback' ), // callback
			'ns-simple-contact-form-admin', // page
			'ns_simple_contact_form_setting_section' // section
		);

		add_settings_field(
			'facebook_url_2', // id
			'Facebook URL <br></br>(no http needed)', // title
			array( $this, 'facebook_url_2_callback' ), // callback
			'ns-simple-contact-form-admin', // page
			'ns_simple_contact_form_setting_section' // section
		);

		add_settings_field(
			'google_url_3', // id
			'Google URL <br></br>(no http needed)', // title
			array( $this, 'google_url_3_callback' ), // callback
			'ns-simple-contact-form-admin', // page
			'ns_simple_contact_form_setting_section' // section
		);

		add_settings_field(
			'twitter_url_4', // id
			'Twitter URL <br></br>(no http needed)', // title
			array( $this, 'twitter_url_4_callback' ), // callback
			'ns-simple-contact-form-admin', // page
			'ns_simple_contact_form_setting_section' // section
		);

    add_settings_field(
			'disable_social_icons_0', // id
			'Disable Social Icons', // title
			array( $this, 'disable_social_icons_0_callback' ), // callback
			'ns-simple-contact-form-admin', // page
			'ns_simple_contact_form_setting_section' // section
		);

    // Color picker background
    add_settings_field(
      'ns_form_bg_colorpicker', // id
      __( 'Change form background', 'nuno-sarmento-simple-contact-form' ), // title
      array( $this, 'ns_form_colorpicker_bg_callback' ), // callback
			'ns-simple-contact-form-admin', // page
			'ns_simple_contact_form_setting_section' // section
    );

    // Color picker submit button background
    add_settings_field(
      'ns_form_button_colorpicker', // id
      __( 'Submit Button colour', 'nuno-sarmento-simple-contact-form' ), // title
      array( $this, 'ns_form_colorpicker_button_callback' ), // callback
      'ns-simple-contact-form-admin', // page
      'ns_simple_contact_form_setting_section' // section
    );

    // Color picker submit button text colour
    add_settings_field(
      'ns_form_button_text_colorpicker', // id
      __( 'Submit button text colour', 'nuno-sarmento-simple-contact-form' ), // title
      array( $this, 'ns_form_colorpicker_button_text_callback' ), // callback
      'ns-simple-contact-form-admin', // page
      'ns_simple_contact_form_setting_section' // section
    );


    // About Page register
		register_setting(
				'ns_scf_about', // Option group
				'ns_scf_about', // Option name
				array( $this, 'ns_simple_contact_form_about_callback' ) // Sanitize
		);

		add_settings_section(
				'nuno-sarmento-scf-admin', // ID
				'', // Title
				array( $this, 'ns_simple_contact_form_about_callback' ), // Callback
				'ns-scf-setting-about' // Page
		);

		// Sytem Report register
		register_setting(
				'ns_scf_report', // Option group
				'ns_scf_report', // Option name
				array( $this, 'ns_simple_contact_form_report_callback' ) // Sanitize
		);

		add_settings_section(
				'nuno-sarmento-scf-admin', // ID
				'', // Title
				array( $this, 'ns_simple_contact_form_report_callback' ), // Callback
				'ns-scf-setting-report' // Page
		);



	}

	public function ns_simple_contact_form_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['google_captcha_public_key_0'] ) ) {
			$sanitary_values['google_captcha_public_key_0'] = sanitize_text_field( $input['google_captcha_public_key_0'] );
		}

		if ( isset( $input['google_captcha_private_key_1'] ) ) {
			$sanitary_values['google_captcha_private_key_1'] = sanitize_text_field( $input['google_captcha_private_key_1'] );
		}

		if ( isset( $input['facebook_url_2'] ) ) {
			$sanitary_values['facebook_url_2'] = sanitize_text_field( $input['facebook_url_2'] );
		}

		if ( isset( $input['google_url_3'] ) ) {
			$sanitary_values['google_url_3'] = sanitize_text_field( $input['google_url_3'] );
		}

		if ( isset( $input['twitter_url_4'] ) ) {
			$sanitary_values['twitter_url_4'] = sanitize_text_field( $input['twitter_url_4'] );
		}

    if ( isset( $input['disable_social_icons_0'] ) ) {
			$sanitary_values['disable_social_icons_0'] = $input['disable_social_icons_0'];
		}

    if ( isset( $input['ns_form_bg_colorpicker'] ) ) {
      $sanitary_values['ns_form_bg_colorpicker'] = $input['ns_form_bg_colorpicker'];
    }

    if ( isset( $input['ns_form_button_colorpicker'] ) ) {
      $sanitary_values['ns_form_button_colorpicker'] = $input['ns_form_button_colorpicker'];
    }

    if ( isset( $input['ns_form_button_text_colorpicker'] ) ) {
      $sanitary_values['ns_form_button_text_colorpicker'] = $input['ns_form_button_text_colorpicker'];
    }

		return $sanitary_values;
	}

	public function ns_simple_contact_form_section_info() {

	}


	public function google_captcha_public_key_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="ns_simple_contact_form_option_name[google_captcha_public_key_0]" id="google_captcha_public_key_0" value="%s">',
			isset( $this->ns_simple_contact_form_options['google_captcha_public_key_0'] ) ? esc_attr( $this->ns_simple_contact_form_options['google_captcha_public_key_0']) : ''
		);
	}

	public function google_captcha_private_key_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="ns_simple_contact_form_option_name[google_captcha_private_key_1]" id="google_captcha_private_key_1" value="%s">',
			isset( $this->ns_simple_contact_form_options['google_captcha_private_key_1'] ) ? esc_attr( $this->ns_simple_contact_form_options['google_captcha_private_key_1']) : ''
		);

    echo '<br></br><a href="http://www.google.com/recaptcha/admin" target="_blank"> Need one? please click here </a>';
	}

	public function facebook_url_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="ns_simple_contact_form_option_name[facebook_url_2]" id="facebook_url_2" value="%s">',
			isset( $this->ns_simple_contact_form_options['facebook_url_2'] ) ? esc_attr( $this->ns_simple_contact_form_options['facebook_url_2']) : ''
		);
	}

	public function google_url_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="ns_simple_contact_form_option_name[google_url_3]" id="google_url_3" value="%s">',
			isset( $this->ns_simple_contact_form_options['google_url_3'] ) ? esc_attr( $this->ns_simple_contact_form_options['google_url_3']) : ''
		);
	}

	public function twitter_url_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="ns_simple_contact_form_option_name[twitter_url_4]" id="twitter_url_4" value="%s">',
			isset( $this->ns_simple_contact_form_options['twitter_url_4'] ) ? esc_attr( $this->ns_simple_contact_form_options['twitter_url_4']) : ''
		);
	}

  public function disable_social_icons_0_callback() {
		?> <select name="ns_simple_contact_form_option_name[disable_social_icons_0]" id="disable_social_icons_0">
			<?php $selected = (isset( $this->ns_simple_contact_form_options['disable_social_icons_0'] ) && $this->ns_simple_contact_form_options['disable_social_icons_0'] === 'none') ? 'selected' : '' ; ?>
			<option value="none" <?php echo $selected; ?>> Disable</option>
			<?php $selected = (isset( $this->ns_simple_contact_form_options['disable_social_icons_0'] ) && $this->ns_simple_contact_form_options['disable_social_icons_0'] === 'block') ? 'selected' : '' ; ?>
			<option value="block" <?php echo $selected; ?>> Enable</option>
		</select> <?php
	}

  public function ns_form_colorpicker_bg_callback() {
    $val = ( isset( $this->ns_simple_contact_form_options['ns_form_bg_colorpicker'] ) ) ? $this->ns_simple_contact_form_options['ns_form_bg_colorpicker'] : '';
    echo '<input type="text" name="ns_simple_contact_form_option_name[ns_form_bg_colorpicker]" value="' . $val . '" class="nsscfbg_colorpicker" >';
  }

  public function ns_form_colorpicker_button_callback() {
    $val = ( isset( $this->ns_simple_contact_form_options['ns_form_button_colorpicker'] ) ) ? $this->ns_simple_contact_form_options['ns_form_button_colorpicker'] : '';
    echo '<input type="text" name="ns_simple_contact_form_option_name[ns_form_button_colorpicker]" value="' . $val . '" class="nsscfbg_colorpicker" >';
  }

  public function ns_form_colorpicker_button_text_callback() {
    $val = ( isset( $this->ns_simple_contact_form_options['ns_form_button_text_colorpicker'] ) ) ? $this->ns_simple_contact_form_options['ns_form_button_text_colorpicker'] : '';
    echo '<input type="text" name="ns_simple_contact_form_option_name[ns_form_button_text_colorpicker]" value="' . $val . '" class="nsscfbg_colorpicker" >';
  }


  /**
   * helper function for number conversions
   *
   * @access public
   * @param mixed $v
   * @return void
   */

  public function num_convt( $v ) {
  	$l   = substr( $v, -1 );
  	$ret = substr( $v, 0, -1 );

  	switch ( strtoupper( $l ) ) {
  		case 'P': // fall-through
  		case 'T': // fall-through
  		case 'G': // fall-through
  		case 'M': // fall-through
  		case 'K': // fall-through
  			$ret *= 1024;
  			break;
  		default:
  			break;
  	}

  	return $ret;
  }

  public function ns_simple_contact_form_about_callback() {

  			?>
  			<h1>'Nuno Sarmento' Plugins Colection</h1>

  				<div class="wrap">

  							<p class="clear"></p>

  							<div class="plugin-group">

  							<div class="plugin-card">

  								 <div class="plugin-card-top">

  										 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-slick-slider/" class="plugin-icon" target="_blank">
  										 	 <style type="text/css">#plugin-icon-nuno-sarmento-slick-slider { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-slick-slider/assets/icon-128x128.png?rev=1588561); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-slick-slider { background-image: url(//ps.w.org/nuno-sarmento-slick-slider/assets/icon-256x256.png?rev=1588561); } }</style>
  											 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-slick-slider" style="float:left; margin: 3px 6px 6px 0px;"></div>
  										 </a>

  										 <div class="name column-name" style="float: right;">
  										    <h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-slick-slider/" target="_blank">Nuno Sarmento Slick Slider</a></h4>
  									 	 </div>

  								</div>

  								<div class="plugin-card-bottom">
  									<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
  								</div>

  							</div>

  							<div class="plugin-card">

  								 <div class="plugin-card-top">

  										 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-custom-css-js/" class="plugin-icon" target="_blank">
  										 	 <style type="text/css">#plugin-icon-nuno-sarmento-custom-css-js { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-custom-css-js/assets/icon-128x128.png?rev=1588566); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-custom-css-js { background-image: url(//ps.w.org/nuno-sarmento-custom-css-js/assets/icon-256x256.png?rev=1588566); } }</style>
  											 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-custom-css-js" style="float:left; margin: 3px 6px 6px 0px;"></div>
  										 </a>

  										 <div class="name column-name" style="float: right;">
  										 		<h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-custom-css-js/" target="_blank">Nuno Sarmento Custom CSS - JS</a></h4>
  									 	 </div>

  								</div>

  								<div class="plugin-card-bottom">
  									<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
  								</div>

  							</div>

  							<div class="plugin-card">

  								 <div class="plugin-card-top">

  										 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-social-icons/" class="plugin-icon" target="_blank">
  											 <style type="text/css">#plugin-icon-nuno-sarmento-social-icons { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-social-icons/assets/icon-128x128.png?rev=1588574); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-social-icons { background-image: url(//ps.w.org/nuno-sarmento-social-icons/assets/icon-256x256.png?rev=1588574); } }</style>
  											 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-social-icons" style="float:left; margin: 3px 6px 6px 0px;"></div>
  										 </a>

  										 <div class="name column-name" style="float: right;">
  										 		<h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-social-icons/" target="_blank">Nuno Sarmento Social Icons</a></h4>
  									 	 </div>

  								</div>

  								<div class="plugin-card-bottom">
  									<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
  								</div>

  							</div>

  							<div class="plugin-card">

  								 <div class="plugin-card-top">

  									 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-page-builder/" class="plugin-icon" target="_blank">
  									 	  <style type="text/css">#plugin-icon-nuno-sarmento-page-builder { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-page-builder/assets/icon-128x128.png?rev=1588552); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-page-builder { background-image: url(//ps.w.org/nuno-sarmento-page-builder/assets/icon-256x256.png?rev=1588552); } }</style>
  									 	  <div class="plugin-icon" id="plugin-icon-nuno-sarmento-page-builder" style="float:left; margin: 3px 6px 6px 0px;"></div>
  								 	 </a>

  									 <div class="name column-name" style="float: right;">
  									 <h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-page-builder/" target="_blank">Nuno Sarmento Page Builder</a></h4>

  								 </div>

  								</div>

  								<div class="plugin-card-bottom">
  									<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
  								</div>

  							</div>

  							<div class="plugin-card">

  								 <div class="plugin-card-top">

  										 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-popup/" class="plugin-icon" target="_blank">
  											 <style type="text/css">#plugin-icon-nuno-sarmento-popup { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-popup/assets/icon-128x128.png?rev=1593940); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-popup { background-image: url(//ps.w.org/nuno-sarmento-popup/assets/icon-256x256.png?rev=1593940); } }</style>
  											 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-popup" style="float:left; margin: 3px 6px 6px 0px;"></div>
  										 </a>

  										 <div class="name column-name" style="float: right;">
  										    <h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-popup/" target="_blank" >Nuno Sarmento PopUp</a></h4>
  									   </div>

  								</div>

  								<div class="plugin-card-bottom">
  									<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
  								</div>

  						 </div>


  						 <div class="plugin-card">

  						 	 <div class="plugin-card-top">

  						 		 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-api-to-post/" class="plugin-icon">
  									 <style type="text/css">#plugin-icon-nuno-sarmento-api-to-post { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-api-to-post/assets/icon-128x128.png?rev=1594469); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-api-to-post { background-image: url(//ps.w.org/nuno-sarmento-api-to-post/assets/icon-256x256.png?rev=1594469); } }</style>
  									 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-api-to-post" style="float:left; margin: 3px 6px 6px 0px;"></div>
  							 	 </a>

  						 		 <div class="name column-name">
  						 			 <h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-api-to-post/">Nuno Sarmento API To Post</a></h4>
  						 		 </div>

  						 	</div>

  							<div class="plugin-card-bottom">
  								<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
  							</div>

  						 </div>

  					</div>

  			  </div>

  		<?php

  	}


  	public function ns_simple_contact_form_report_callback() {

  	?>
  		<div class="wrap nuno-sarmento-system-wrap">
  			<div class="icon32" id="icon-tools"><br></div>
  			<h2><?php _e( 'Server Details', 'nuno-sarmento-popup' ) ?></h2>
  			<p><?php echo $this->ns_popup_report_data(); ?></p>
  		</div>
  		<style media="screen">
  			div.nuno-sarmento-system-wrap h2 {margin: 0 0 1em;}
  			div.nuno-sarmento-system-wrap p {margin: 0 0 1em;}
  			div.nuno-sarmento-system-wrap p input.snapshot-highlight {margin: 0 0 0 10px;}
  			div.nuno-sarmento-system-wrap textarea#nuno-sarmento-system-textarea {
  			background: #ebebeb;display: block;font-family: Menlo,Monaco,monospace;height: 600px;overflow: auto;white-space: pre;width: 1500px;max-width: 95%;color: #000;padding: 10px 0 10px 10px;}
  		</style>
  	 <?php

  	}

  	public function ns_popup_report_data() {

  		// call WP database
  		global $wpdb;

  		// check for browser class add on
  		if ( ! class_exists( 'Browser' ) ) {
  			require_once NUNO_SARMENTO_SIM_CONTACT_FORM_PATH . 'includes/ns-contact-form-browser.php';
  		}

  		// do WP version check and get data accordingly
  		$browser = new Browser();
  		if ( get_bloginfo( 'version' ) < '3.4' ) :
  			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
  			$theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
  		else:
  			$theme_data = wp_get_theme();
  			$theme      = $theme_data->Name . ' ' . $theme_data->Version;
  		endif;

  		// data checks for later
  		$frontpage	= get_option( 'page_on_front' );
  		$frontpost	= get_option( 'page_for_posts' );
  		$mu_plugins = get_mu_plugins();
  		$plugins	= get_plugins();
  		$active		= get_option( 'active_plugins', array() );

  		// multisite details
  		$nt_plugins	= is_multisite() ? wp_get_active_network_plugins() : array();
  		$nt_active	= is_multisite() ? get_site_option( 'active_sitewide_plugins', array() ) : array();
  		$ms_sites	= is_multisite() ? get_blog_list() : null;

  		// yes / no specifics
  		$ismulti	= is_multisite() ? __( 'Yes', 'nuno-sarmento-system-report' ) : __( 'No', 'nuno-sarmento-system-report' );
  		$safemode	= ini_get( 'safe_mode' ) ? __( 'Yes', 'nuno-sarmento-system-report' ) : __( 'No', 'nuno-sarmento-system-report' );
  		$wpdebug	= defined( 'WP_DEBUG' ) ? WP_DEBUG ? __( 'Enabled', 'nuno-sarmento-system-report' ) : __( 'Disabled', 'nuno-sarmento-system-report' ) : __( 'Not Set', 'nuno-sarmento-system-report' );
  		$tbprefx	= strlen( $wpdb->prefix ) < 16 ? __( 'Acceptable', 'nuno-sarmento-system-report' ) : __( 'Too Long', 'nuno-sarmento-system-report' );
  		$fr_page	= $frontpage ? get_the_title( $frontpage ).' (ID# '.$frontpage.')'.'' : __( 'n/a', 'nuno-sarmento-system-report' );
  		$fr_post	= $frontpage ? get_the_title( $frontpost ).' (ID# '.$frontpost.')'.'' : __( 'n/a', 'nuno-sarmento-system-report' );
  		$errdisp	= ini_get( 'display_errors' ) != false ? __( 'On', 'nuno-sarmento-system-report' ) : __( 'Off', 'nuno-sarmento-system-report' );

  		$jquchk		= wp_script_is( 'jquery', 'registered' ) ? $GLOBALS['wp_scripts']->registered['jquery']->ver : __( 'n/a', 'nuno-sarmento-system-report' );

  		$sessenb	= isset( $_SESSION ) ? __( 'Enabled', 'nuno-sarmento-system-report' ) : __( 'Disabled', 'nuno-sarmento-system-report' );
  		$usecck		= ini_get( 'session.use_cookies' ) ? __( 'On', 'nuno-sarmento-system-report' ) : __( 'Off', 'nuno-sarmento-system-report' );
  		$useocck	= ini_get( 'session.use_only_cookies' ) ? __( 'On', 'nuno-sarmento-system-report' ) : __( 'Off', 'nuno-sarmento-system-report' );
  		$hasfsock	= function_exists( 'fsockopen' ) ? __( 'Supports fsockopen.', 'nuno-sarmento-system-report' ) : __( 'Not support fsockopen.', 'nuno-sarmento-system-report' );
  		$hascurl	= function_exists( 'curl_init' ) ? __( 'Supports cURL.', 'nuno-sarmento-system-report' ) : __( 'Not support cURL.', 'nuno-sarmento-system-report' );
  		$hassoap	= class_exists( 'SoapClient' ) ? __( 'SOAP Client enabled.', 'nuno-sarmento-system-report' ) : __( 'Does not have the SOAP Client enabled.', 'nuno-sarmento-system-report' );
  		$hassuho	= extension_loaded( 'suhosin' ) ? __( 'Server has SUHOSIN installed.', 'nuno-sarmento-system-report' ) : __( 'Does not have SUHOSIN installed.', 'nuno-sarmento-system-report' );
  		$openssl	= extension_loaded('openssl') ? __( 'OpenSSL installed.', 'nuno-sarmento-system-report' ) : __( 'Does not have OpenSSL installed.', 'nuno-sarmento-system-report' );

  		// start generating report
  		$report	= '';
  		$report	.= '<textarea readonly="readonly" id="nuno-sarmento-system-textarea" name="nuno-sarmento-system-textarea">';
  		$report	.= '--- Begin System Info ---'."\n";
  		// add filter for adding to report opening
  		$report	.= apply_filters( 'snapshot_report_before', '' );

  		$report	.= "\n\t".'-- SERVER DATA --'."\n";
  		$report	.= 'jQuery Version'."\t\t\t\t".$jquchk."\n";
  		$report	.= 'PHP Version:'."\t\t\t\t".PHP_VERSION."\n";
  		$report	.= 'MySQL Version:'."\t\t\t\t".$wpdb->db_version()."\n";
  		$report	.= 'Server Software:'."\t\t\t".$_SERVER['SERVER_SOFTWARE']."\n";

  		$report	.= "\n\t".'-- PHP CONFIGURATION --'."\n";
  		$report	.= 'Safe Mode:'."\t\t\t\t".$safemode."\n";
  		$report	.= 'Memory Limit:'."\t\t\t\t".ini_get( 'memory_limit' )."\n";
  		$report	.= 'Upload Max:'."\t\t\t\t".ini_get( 'upload_max_filesize' )."\n";
  		$report	.= 'Post Max:'."\t\t\t\t".ini_get( 'post_max_size' )."\n";
  		$report	.= 'Time Limit:'."\t\t\t\t".ini_get( 'max_execution_time' )."\n";
  		$report	.= 'Max Input Vars:'."\t\t\t\t".ini_get( 'max_input_vars' )."\n";
  		$report	.= 'Display Errors:'."\t\t\t\t".$errdisp."\n";
  		$report	.= 'Sessions:'."\t\t\t\t".$sessenb."\n";
  		$report	.= 'Session Name:'."\t\t\t\t".esc_html( ini_get( 'session.name' ) )."\n";
  		$report	.= 'Cookie Path:'."\t\t\t\t".esc_html( ini_get( 'session.cookie_path' ) )."\n";
  		$report	.= 'Save Path:'."\t\t\t\t".esc_html( ini_get( 'session.save_path' ) )."\n";
  		$report	.= 'Use Cookies:'."\t\t\t\t".$usecck."\n";
  		$report	.= 'Use Only Cookies:'."\t\t\t".$useocck."\n";
  		$report	.= 'FSOCKOPEN:'."\t\t\t\t".$hasfsock."\n";
  		$report	.= 'cURL:'."\t\t\t\t\t".$hascurl."\n";
  		$report	.= 'SOAP Client:'."\t\t\t\t".$hassoap."\n";
  		$report	.= 'SUHOSIN:'."\t\t\t\t".$hassuho."\n";
  		$report	.= 'OpenSSL:'."\t\t\t\t".$openssl."\n";

  		$report	.= "\n\t".'-- WORDPRESS DATA --'."\n";
  		$report	.= 'Multisite:'."\t\t\t\t".$ismulti."\n";
  		$report	.= 'SITE_URL:'."\t\t\t\t".site_url()."\n";
  		$report	.= 'HOME_URL:'."\t\t\t\t".home_url()."\n";
  		$report	.= 'WP Version:'."\t\t\t\t".get_bloginfo( 'version' )."\n";
  		$report	.= 'Permalink:'."\t\t\t\t".get_option( 'permalink_structure' )."\n";
  		$report	.= 'Cur Theme:'."\t\t\t\t".$theme."\n";
  		$report	.= 'Post Types:'."\t\t\t\t".implode( ', ', get_post_types( '', 'names' ) )."\n";
  		$report	.= 'Post Stati:'."\t\t\t\t".implode( ', ', get_post_stati() )."\n";
  		$report	.= 'User Count:'."\t\t\t\t".count( get_users() )."\n";

  		$report	.= "\n\t".'-- WORDPRESS CONFIG --'."\n";
  		$report	.= 'WP_DEBUG:'."\t\t\t\t".$wpdebug."\n";
  		$report	.= 'WP Memory Limit:'."\t\t\t".$this->num_convt( WP_MEMORY_LIMIT )/( 1024 ).'MB'."\n";
  		$report	.= 'Table Prefix:'."\t\t\t\t".$wpdb->base_prefix."\n";
  		$report	.= 'Prefix Length:'."\t\t\t\t".$tbprefx.' ('.strlen( $wpdb->prefix ).' characters)'."\n";
  		$report	.= 'Show On Front:'."\t\t\t\t".get_option( 'show_on_front' )."\n";
  		$report	.= 'Page On Front:'."\t\t\t\t".$fr_page."\n";
  		$report	.= 'Page For Posts:'."\t\t\t\t".$fr_post."\n";

  		if ( is_multisite() ) :
  			$report	.= "\n\t".'-- MULTISITE INFORMATION --'."\n";
  			$report	.= 'Total Sites:'."\t\t\t\t".get_blog_count()."\n";
  			$report	.= 'Base Site:'."\t\t\t\t".$ms_sites[0]['domain']."\n";
  			$report	.= 'All Sites:'."\n";
  			foreach ( $ms_sites as $site ) :
  				if ( $site['path'] != '/' )
  					$report	.= "\t\t".'- '. $site['domain'].$site['path']."\n";

  			endforeach;
  			$report	.= "\n";
  		endif;

  		$report	.= "\n\t".'-- BROWSER DATA --'."\n";
  		$report	.= 'Platform:'."\t\t\t\t".$browser->getPlatform()."\n";
  		$report	.= 'Browser Name'."\t\t\t\t". $browser->getBrowser() ."\n";
  		$report	.= 'Browser Version:'."\t\t\t".$browser->getVersion()."\n";
  		$report	.= 'Browser User Agent:'."\t\t\t".$browser->getUserAgent()."\n";

  		$report	.= "\n\t".'-- PLUGIN INFORMATION --'."\n";
  		if ( $plugins && $mu_plugins ) :
  			$report	.= 'Total Plugins:'."\t\t\t\t".( count( $plugins ) + count( $mu_plugins ) + count( $nt_plugins ) )."\n";
  		endif;

  		// output must-use plugins
  		if ( $mu_plugins ) :
  			$report	.= 'Must-Use Plugins: ('.count( $mu_plugins ).')'. "\n";
  			foreach ( $mu_plugins as $mu_path => $mu_plugin ) :
  				$report	.= "\t".'- '.$mu_plugin['Name'] . ' ' . $mu_plugin['Version'] ."\n";
  			endforeach;
  			$report	.= "\n";
  		endif;

  		// if multisite, grab active network as well
  		if ( is_multisite() ) :
  			// active network
  			$report	.= 'Network Active Plugins: ('.count( $nt_plugins ).')'. "\n";

  			foreach ( $nt_plugins as $plugin_path ) :
  				if ( array_key_exists( $plugin_base, $nt_plugins ) )
  					continue;

  				$plugin = get_plugin_data( $plugin_path );

  				$report	.= "\t".'- '.$plugin['Name'] . ' ' . $plugin['Version'] ."\n";
  			endforeach;
  			$report	.= "\n";

  		endif;

  		// output active plugins
  		if ( $plugins ) :
  			$report	.= 'Active Plugins: ('.count( $active ).')'. "\n";
  			foreach ( $plugins as $plugin_path => $plugin ) :
  				if ( ! in_array( $plugin_path, $active ) )
  					continue;
  				$report	.= "\t".'- '.$plugin['Name'] . ' ' . $plugin['Version'] ."\n";
  			endforeach;
  			$report	.= "\n";
  		endif;

  		// output inactive plugins
  		if ( $plugins ) :
  			$report	.= 'Inactive Plugins: ('.( count( $plugins ) - count( $active ) ).')'. "\n";
  			foreach ( $plugins as $plugin_path => $plugin ) :
  				if ( in_array( $plugin_path, $active ) )
  					continue;
  				$report	.= "\t".'- '.$plugin['Name'] . ' ' . $plugin['Version'] ."\n";
  			endforeach;
  			$report	.= "\n";
  		endif;

  		// add filter for end of report
  		$report	.= apply_filters( 'snapshot_report_after', '' );

  		// end it all
  		$report	.= "\n".'--- End System Info ---';
  		$report	.= '</textarea>';

  		return $report;
  	}





}
if ( is_admin() )
	$ns_simple_contact_form = new NSSimpleContactForm();

/*
 * Retrieve this value with:
 * $ns_simple_contact_form_options = get_option( 'ns_simple_contact_form_option_name' ); // Array of All Options
 * $google_captcha_public_key_0 = $ns_simple_contact_form_options['google_captcha_public_key_0']; // Google Captcha Public Key
 * $google_captcha_private_key_1 = $ns_simple_contact_form_options['google_captcha_private_key_1']; // Google Captcha Private Key
 * $facebook_url_2 = $ns_simple_contact_form_options['facebook_url_2']; // Facebook URL
 * $google_url_3 = $ns_simple_contact_form_options['google_url_3']; // Google URL
 * $twitter_url_4 = $ns_simple_contact_form_options['twitter_url_4']; // Twitter URL
 */
