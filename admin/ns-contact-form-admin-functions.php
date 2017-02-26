<?php defined('ABSPATH') or die();

/* -------------------------
// Admin menu  -------------
--------------------------- */

function ns_contact_form_admin_menu()
{
    add_menu_page(
      __('NS Contact Form', 'nuno-sarmento-simple-contact-form'),
      __('NS Contact Form', 'nuno-sarmento-simple-contact-form'),
      'activate_plugins',
      'ns_simple_contact_form',
      'ns_form_page_handler',
      'dashicons-feedback'
    );

    add_submenu_page(
      'ns_simple_contact_form',
      __('Form Entries', 'nuno-sarmento-simple-contact-form'),
      __('Form Entries', 'nuno-sarmento-simple-contact-form'),
      'activate_plugins',
      'ns_simple_contact_form',
      'ns_form_page_handler'
    );

    add_submenu_page(
      'ns_simple_contact_form',
       __('Add new', 'nuno-sarmento-simple-contact-form'),
      __('Add new entry', 'nuno-sarmento-simple-contact-form'),
      'activate_plugins',
      'ns_simple_contact_form_add',
      'ns_contact_form_page_handler'
    );

    add_submenu_page(
      'ns_simple_contact_form',
      __('Export', 'nuno-sarmento-simple-contact-form'),
      __('Export Forms', 'nuno-sarmento-simple-contact-form'),
      'activate_plugins',
      'ns_simple_contact_form_export',
      'ns_contact_form_csv_push'
    );


}

add_action('admin_menu', 'ns_contact_form_admin_menu');


/* -------------------------
// CSV Exporter-------------
// This function exports our custom table to csv files nicely in columns
// To "trigger" this send a request to http://sitename.com/wp-admin/admin-ajax.php?action=csv_pull
// $ajax_url = admin_url('admin-ajax.php?action=csv_pull');
--------------------------- */
function ns_contact_form_csv_push(){

   $ns_dashboard_css ='
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
     .header__ns_nsss h2 {padding: 30px;font-size: 30px;line-height: 34px;}
   }
   .sub_header__ns_nsss { float: left; width: 100%; margin-bottom: 20px; position: relative; }
   </style>

   <div class="wrap">
     <div class="header__ns_nsss">
       <h2>NS - Simple Contact Form</h2>
     </div>
     <div class="sub_header__ns_nsss">
     <h2>Export Contact Forms</h2>
      <p><a href="admin-ajax.php?action=csv_pull">Click here to export the contact forms</a></p>
     </div>
   </div>

   ';

   echo $ns_dashboard_css;
 }


function ns_contact_form_csv_pull() {

  global $wpdb;

  $table = 'ns_contact_form';// table name
  $file = 'ns_contact_form_csv'; // csv file name

  $results = $wpdb->get_results("SELECT * FROM $wpdb->prefix$table",ARRAY_A );

  if(count($results) > 0){

      foreach($results as $result){
          $result = array_values($result);
          $result = implode(", ", $result);
          $csv_output .= $result."\n";
      }
  }
  $filename = $file."_".date("Y-m-d_H-i",time());
  header("Content-type: application/vnd.ms-excel");
  header("Content-disposition: csv" . date("Y-m-d") . ".csv");
  header( "Content-disposition: filename=".$filename.".csv");
  print $csv_output;
  exit;
}
add_action('wp_ajax_csv_pull','ns_contact_form_csv_pull');


/* ------------------------------------------
// Print out Form Syling --------------------
--------------------------------------------- */
function nuno_sarmento_scf_print_css() {
  $ns_simple_contact_form_options = get_option( 'ns_simple_contact_form_option_name' ); // Array of All Options
	$ns_form_bg_colorpicker = $ns_simple_contact_form_options['ns_form_bg_colorpicker']; //  Background Color
  $ns_form_button_colorpicker = $ns_simple_contact_form_options['ns_form_button_colorpicker']; //  Background Color
  $ns_form_button_text_colorpicker = $ns_simple_contact_form_options['ns_form_button_text_colorpicker']; //  Background Color



print '<style>
.ns-form-content {
  background-color: '.$ns_form_bg_colorpicker.';
  -moz-box-shadow: 0 0 10px '.$ns_form_bg_colorpicker.';
  -webkit-box-shadow: 0 0 10px '.$ns_form_bg_colorpicker.';
}

.ns-form-content #nsscf-form input#nsscf-form_send{
  background: '.$ns_form_button_colorpicker.';
   color: '.$ns_form_button_text_colorpicker.';
}

</style>';
}

add_action('wp_head', 'nuno_sarmento_scf_print_css', 100);

/* ------------------------------------------
// Function to get the user IP -------------
--------------------------------------------- */

function nuno_sarmento_simple_contact_form_get_the_ip() {
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
		return $_SERVER["HTTP_CLIENT_IP"];
	}
	else {
		return $_SERVER["REMOTE_ADDR"];
	}
}

/* ------------------------------------------
// TinyMce Button for shortcode -------------
--------------------------------------------- */

add_action( 'admin_init', 'ns_cform_tinymce_button' );
function ns_cform_tinymce_button() {
   if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
        add_filter( 'mce_buttons', 'ns_cform_register_tinymce_button' );
        add_filter( 'mce_external_plugins', 'ns_cform_add_tinymce_button' );
   }
}

function ns_cform_register_tinymce_button( $buttons ) {
   array_push( $buttons, "ns_cform_button" );
   return $buttons;
}

function ns_cform_add_tinymce_button( $plugin_array ) {
   $plugin_array['ns_cform_button_script'] = NUNO_SARMENTO_SIM_CONTACT_URI. 'assets/js/ns-cform-btt.js';
   return $plugin_array;
}
