<?php defined('ABSPATH') or die();
/* ------------------------------------------
// External Form Class  ---------------------
--------------------------------------------- */

class NS_CONTACT_EXTERNAL_FORM {

    public function __construct()
    {
      if (!isset ($_SESSION)) session_start();
      $_SESSION['ns-scf-captcha'] = isset($_SESSION['ns-scf-captcha']) ? $_SESSION['ns-scf-captcha'] : rand(10, 999);
      add_shortcode('ns_contact_form', array($this, 'shortcode'));
      add_action('wp_enqueue_scripts', array($this, 'ns_scf_frontend_enqueue_scripts'));
    }

    public function ns_scf_frontend_enqueue_scripts() {
      wp_register_style( 'font-awesome',  NUNO_SARMENTO_SIM_CONTACT_URI . 'assets/css/font-awesome.min.css');
      wp_enqueue_style( 'font-awesome' );
    }

    public function reCaptcha($recaptcha){

      // Pull admin page options

      $ns_simple_contact_form_options = get_option( 'ns_simple_contact_form_option_name' ); // Array of All Options
      $google_captcha_private_key_1 = $ns_simple_contact_form_options['google_captcha_private_key_1']; // Google Captcha Private Key

      // Google Captcha

      $secret = $google_captcha_private_key_1;
      $ip = $_SERVER['REMOTE_ADDR'];
      $postvars = array("secret"=>$secret, "response"=>$recaptcha, "remoteip"=>$ip);
      $url = "https://www.google.com/recaptcha/api/siteverify";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
      $data = curl_exec($ch);
      curl_close($ch);
      return json_decode($data, true);
    }

    public function shortcode($ns_scf_atts){

      $ns_scf_atts = shortcode_atts( array(
        "email_to" => get_bloginfo('admin_email'),
        "label_name" => __('Name', 'nuno-sarmento-simple-contact-form'),
        "label_email" => __('Email', 'nuno-sarmento-simple-contact-form'),
        "label_subject" => __('Subject', 'nuno-sarmento-simple-contact-form'),
        "label_phone" => __('Phone', 'nuno-sarmento-simple-contact-form'),
        "label_message" => __('Message', 'nuno-sarmento-simple-contact-form'),
        //"label_captcha" => __('Enter number %s', 'nuno-sarmento-simple-contact-form'),
        "label_submit" => __('Submit', 'nuno-sarmento-simple-contact-form'),
        "error_name" => __('Please enter at least 2 characters', 'nuno-sarmento-simple-contact-form'),
        "error_subject" => __('Please enter at least 2 characters', 'nuno-sarmento-simple-contact-form'),
        "error_phone" => __('Please enter valid phone', 'nuno-sarmento-simple-contact-form'),
        "error_message" => __('Please enter at least 10 characters', 'nuno-sarmento-simple-contact-form'),
        "error_captcha" => __('Please enter the correct number', 'nuno-sarmento-simple-contact-form'),
        "error_email" => __('Please enter a valid email', 'nuno-sarmento-simple-contact-form'),
        "message_success" => __('Thank you! You will receive a response as soon as possible.', 'nuno-sarmento-simple-contact-form'),
        "hide_subject" => '',
        "hide_phone" => ''
      ), $ns_scf_atts);

      // Set variables
      $form_data = array(
        'form_name' => '',
        'form_email' => '',
        'form_subject' => '',
        //'form_captcha' => '',
        'form_firstname' => '',
        'form_lastname' => '',
        'form_phone' => '',
        'form_message' => ''
      );
      $error = false;
      $sent = false;
      $info = '';

      if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['nsscf-form_send']) && isset($_POST['g-recaptcha-response'])  ) {

        // Sanitize content
        $post_data = array(
          'form_name' => sanitize_text_field($_POST['nsscf-form_name']),
          'form_email' => sanitize_email($_POST['nsscf-form_email']),
          'form_subject' => sanitize_text_field($_POST['nsscf-form_subject']),
          'form_message' => wp_kses_post($_POST['nsscf-form_message']),
          //'form_captcha' => sanitize_text_field($_POST['nsscf-form_captcha']),
          'form_firstname' => sanitize_text_field($_POST['nsscf-form_firstname']),
          'form_lastname' => sanitize_text_field($_POST['nsscf-form_lastname']),
          'form_phone' => sanitize_text_field($_POST['nsscf-form_phone'])
        );

        // Validate name
        $value = $post_data['form_name'];
        if ( strlen($value)<2 ) {
          $error_class['form_name'] = true;
          $error = true;
        }
        $form_data['form_name'] = $value;

        // Validate email
        $value = $post_data['form_email'];
        if ( empty($value) ) {
          $error_class['form_email'] = true;
          $error = true;
        }
        $form_data['form_email'] = $value;

        // Validate subject
        if ($ns_scf_atts['hide_subject'] != "true") {
          $value = $post_data['form_subject'];
          if ( strlen($value)<2 ) {
            $error_class['form_subject'] = true;
            $error = true;
          }
          $form_data['form_subject'] = $value;
        }

        // Validate message
        $value = $post_data['form_message'];
        if ( strlen($value)<10 ) {
          $error_class['form_message'] = true;
          $error = true;
        }
        $form_data['form_message'] = $value;

        // // Validate captcha
        // $value = $post_data['form_captcha'];
        // if ( $value != $_SESSION['ns-scf-captcha'] ) {
        //   $error_class['form_captcha'] = true;
        //   $error = true;
        // }
        // $form_data['form_captcha'] = $value;

        // Validate first honeypot field
        $value = $post_data['form_firstname'];
        if ( strlen($value)>0 ) {
          $error = true;
        }
        $form_data['form_firstname'] = $value;

        // Validate second honeypot field
        $value = $post_data['form_lastname'];
        if ( strlen($value)>0 ) {
          $error = true;
        }
        $form_data['form_lastname'] = $value;


        // Validate phone
        if ($ns_scf_atts['hide_phone'] != "true") {
          $value = $post_data['form_phone'];
          if ( strlen($value)<1 ) {
            $error_class['form_phone'] = true;
            $error = true;
          }
          $form_data['form_phone'] = $value;
        }



        $recaptcha = $_POST['g-recaptcha-response'];
        $res = $this->reCaptcha($recaptcha);
        if(!$res['success']){
          $error = true;
          $errorGoogle = "Please tick the captcha box";
        }

        // Sending message to admin & saving to database
        if ($error == false) {

            do_action( 'nsscf-form_before_send_mail', $form_data );

            global $wpdb;
            $table_name = $wpdb->prefix . 'ns_contact_form';
            $wpdb->insert($table_name, array(
              'email' => $form_data['form_email'],
              'name' => $form_data['form_name'],
              'phone' => $form_data['form_phone'],
              'subject' => $form_data['form_subject'],
              'message' => $form_data['form_message']
            ) );

            $to = $ns_scf_atts['email_to'];

            if ($ns_scf_atts['hide_subject'] != "true") {
              $subject = "(".get_bloginfo('name').") " . $form_data['form_subject'];
            }elseif ($ns_scf_atts['hide_phone'] != "true") {
              $subject = "(".get_bloginfo('name').") " . $form_data['form_phone'];
            } else {
              $subject = get_bloginfo('name');
            }

            $message = $form_data['form_name'] . "\r\n\r\n" . $form_data['form_email'] . "\r\n\r\n" . $form_data['form_message'] . "\r\n\r\n" . $form_data['form_phone'] . "\r\n\r\n" . sprintf( esc_attr__( 'IP: %s', 'nuno-sarmento-simple-contact-form' ), nuno_sarmento_simple_contact_form_get_the_ip() );
            $headers = "Content-Type: text/plain; charset=UTF-8" . "\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit" . "\r\n";
            $headers .= "From: ".$form_data['form_name']." <".$form_data['form_email'].">" . "\r\n";
            $headers .= "Reply-To: <".$form_data['form_email'].">" . "\r\n";
            wp_mail($to, $subject, $message, $headers);
            $result = $ns_scf_atts['message_success'];
            $sent = true;

        }// Ending Sending message to admin & saving to database

      }

      // Display success message
      if(!empty($result)) {
        $info = '<div class="nsscf-form-text-center" style=" display: table; margin: 0 auto;"><p class="nsscf-form-info">'.esc_attr($result).'</p></div>';
      }

      // Hide or display subject field
      if ($ns_scf_atts['hide_subject'] == "true") {
        $hide = true;
      }

      // Hide or display subject field
      if ($ns_scf_atts['hide_phone'] == "true") {
        $hide = true;
      }

      // Pull admin page options
      $ns_simple_contact_form_options = get_option( 'ns_simple_contact_form_option_name' ); // Array of All Options
      $facebook_url_2 = $ns_simple_contact_form_options['facebook_url_2']; // Facebook URL
      $twitter_url_4 = $ns_simple_contact_form_options['twitter_url_4'];
      $google_url_3 = $ns_simple_contact_form_options['google_url_3']; // Google URL
      $google_captcha_public_key_0 = $ns_simple_contact_form_options['google_captcha_public_key_0']; // Google Captcha Public Key
      $disable_social_icons_0 = $ns_simple_contact_form_options['disable_social_icons_0']; // Disable Social Icons


      // Contact form
      $email_form = '

      <div class="ns-form-content">

        <form class="nsscf-form" id="nsscf-form" method="post">

        <p><label for="nsscf-form_name"><span class="'.(isset($error_class['form_name']) ? "error" : "hide").'" >'.esc_attr($ns_scf_atts['error_name']).'</span></label></p>
        <p><input type="text" placeholder="'.esc_attr($ns_scf_atts['label_name']).'" name="nsscf-form_name" id="nsscf-form_name" '.(isset($error_class['form_name']) ? ' class="error"' : '').' maxlength="50" value="'.esc_attr($form_data['form_name']).'" /></p>

        <p><label for="nsscf-form_email"><span class="'.(isset($error_class['form_email']) ? "error" : "hide").'" >'.esc_attr($ns_scf_atts['error_email']).'</span></label></p>
        <p><input type="text" placeholder="'.esc_attr($ns_scf_atts['label_email']).'" name="nsscf-form_email" id="nsscf-form_email" '.(isset($error_class['form_email']) ? ' class="error"' : '').' maxlength="50" value="'.esc_attr($form_data['form_email']).'" /></p>

        <p><label for="nsscf-form_subject" '.(isset($hide) ? ' class="hide"' : '').'><span class="'.(isset($error_class['form_subject']) ? "error" : "hide").'" >'.esc_attr($ns_scf_atts['error_subject']).'</span></label></p>
        <p><input type="text" placeholder="'.esc_attr($ns_scf_atts['label_subject']).'" name="nsscf-form_subject" id="nsscf-form_subject" '.(isset($hide) ? ' class="hide"' : ''). (isset($error_class['form_subject']) ? ' class="error"' : '').' maxlength="50" value="'.esc_attr($form_data['form_subject']).'" /></p>


        <p><label for="nsscf-form_phone" '.(isset($hide) ? ' class="hide"' : '').'><span class="'.(isset($error_class['form_phone']) ? "error" : "hide").'" >'.esc_attr($ns_scf_atts['error_phone']).'</span></label></p>
        <p><input type="text" placeholder="'.esc_attr($ns_scf_atts['label_phone']).'" name="nsscf-form_phone" id="nsscf-form_phone" '.(isset($hide) ? ' class="hide"' : ''). (isset($error_class['form_phone']) ? ' class="error"' : '').' maxlength="50" value="'.esc_attr($form_data['form_phone']).'" /></p>


        <p><input type="text" name="nsscf-form_firstname" id="nsscf-form_firstname" maxlength="50" value="'.esc_attr($form_data['form_firstname']).'" /></p>

        <p><input type="text" name="nsscf-form_lastname" id="nsscf-form_lastname" maxlength="50" value="'.esc_attr($form_data['form_lastname']).'" /></p>

        <p><label for="nsscf-form_message"><span class="'.(isset($error_class['form_message']) ? "error" : "hide").'" >'.esc_attr($ns_scf_atts['error_message']).'</span></label></p>
        <p><textarea name="nsscf-form_message" placeholder="'.esc_attr($ns_scf_atts['label_message']).'" id="nsscf-form_message" rows="6" '.(isset($error_class['form_message']) ? ' class="error"' : '').'>'.wp_kses_post($form_data['form_message']).'</textarea></p>


        <p><label><span class="error">'.sprintf (@$errorGoogle).'</span></label></p>
        <div class="g-recaptcha" data-sitekey="'.$google_captcha_public_key_0.'" data-theme="light" data-callback=""></div>
        </br>
        <p><input type="submit" value="'.esc_attr($ns_scf_atts['label_submit']).'" name="nsscf-form_send" id="nsscf-form_send" class="nsscf-form_send"/></p>

        </form>

        <div style="display:'.$disable_social_icons_0.'" class="ns-social-container">
          <a href="http://'.$facebook_url_2.'"><button class="ns-facebook"><i class="fa fa-facebook"></i>Facebook</button></a>
          <a href="http://'.$twitter_url_4.'"><button class="ns-twitter"><i class="fa fa-twitter"></i>Twitter</button></a>
          <a href="http://'.$google_url_3.'"><button class="ns-google"><i class="fa fa-google-plus"></i>Google</button></a>
        </div>

    </div>

      ';

      // randon numbers captcha in case you rather to use numbers instead of google captcha you just need to
      // copy the label and input and replace the google one. Also look to comment lines regarding the captcha please uncommeted.
      // <p><label for="nsscf-form_captcha">'.sprintf(esc_attr($ns_scf_atts['label_captcha']), $_SESSION['ns-scf-captcha']).': <span class="'.(isset($error_class['form_captcha']) ? "error" : "hide").'" >'.esc_attr($ns_scf_atts['error_captcha']).'</span></label></p>
      // <p><input type="text" name="nsscf-form_captcha" id="nsscf-form_captcha" '.(isset($error_class['form_captcha']) ? ' class="error"' : '').' maxlength="50" value="'.esc_attr($form_data['form_captcha']).'" /></p>

      // Send message and unset captcha variabele or display form with error message

      if(isset($sent) && $sent == true ) {
        unset($_SESSION['ns-scf-captcha']);
        return $info;
      } else {
        return $email_form;
      }

    }
}

$nsContactForm = new NS_CONTACT_EXTERNAL_FORM();
