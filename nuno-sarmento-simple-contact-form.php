<?php
/*
Plugin Name: Nuno Sarmento Simple Contact Form
Description: Nuno Sarmento is a simple contact form. Use the shortcode [ns_contact_form] to display form on page or post, the shortcode can be added manually or calling it on visual editor toolbar by clicking the form icon. All entries can be visualized on the form entries admin page plus all forms entries are editable and it can be exported to csv file .
Version: 1.0.0
Author: Nuno Sarmento
Author URI: https://www.nuno-sarmento.com
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: nuno-sarmento-simple-contact-form
Domain Path: /languages
License:     GPL2


Nuno Sarmento Simple Contact Form is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Nuno Sarmento Simple Contact Form is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

 */
 /*
 Just add the shortcode [ns_contact_form] in a post or page, or use the function <?php nuno_sarmento_nsss_slider(); ?> in your theme templates.
 */

 defined('ABSPATH') or die('°_°’');

 /* ------------------------------------------
 // Constants ---------------------------
 --------------------------------------------- */
 /* Set constant path to the plugin directory. */
 if ( ! defined( 'NUNO_SARMENTO_SIM_CONTACT_FORM_PATH' ) ) {
 	define( 'NUNO_SARMENTO_SIM_CONTACT_FORM_PATH', plugin_dir_path( __FILE__ ) );
 }

 /* Set the constant path to the plugin directory URI. */

if ( ! defined( 'NUNO_SARMENTO_SIM_CONTACT_URI' ) ) {
	define( 'NUNO_SARMENTO_SIM_CONTACT_URI', plugin_dir_url( __FILE__ ) );
}

 /* Set plugin name. */
 if( ! defined( 'NUNO_SARMENTO_SIM_CONTACT_NAME' ) ) {
 	define( 'NUNO_SARMENTO_SIM_CONTACT_NAME', 'NS - Simple Contact Form' );
 }

 /* Set plugin version constant. */
 if( ! defined( 'NUNO_SARMENTO_SIM_CONTACT_VERSION' ) ) {
 	define( 'NUNO_SARMENTO_SIM_CONTACT_VERSION', '1.0.0' );
 }

 /* ------------------------------------------
 // i18n -------------------------------------
 --------------------------------------------- */
 load_plugin_textdomain( 'nuno-sarmento-simple-contact-form', false, basename( dirname( __FILE__ ) ) . '/languages' );

 /* ------------------------------------------
 // Create table        ---------------------
 --------------------------------------------- */

 if ( ! @include( 'ns-contact-form-create-table.php' ) ) {
  require_once( NUNO_SARMENTO_SIM_CONTACT_FORM_PATH . 'includes/ns-contact-form-create-table.php' );
 }

 /* ------------------------------------------
 // Admin Functions      ---------------------
 --------------------------------------------- */

 if ( ! @include( 'ns-contact-form-admin-functions.php' ) ) {
  require_once( NUNO_SARMENTO_SIM_CONTACT_FORM_PATH . 'admin/ns-contact-form-admin-functions.php' );
 }

 /* ------------------------------------------
 // Admin Functions      ---------------------
 --------------------------------------------- */

 if ( ! @include( 'ns-contact-form-menu-options.php' ) ) {
  require_once( NUNO_SARMENTO_SIM_CONTACT_FORM_PATH . 'includes/ns-contact-form-menu-options.php' );
 }

 /* ------------------------------------------
 // Wp Table List        ---------------------
 --------------------------------------------- */

if ( ! @include( 'ns-contact-form-wp-list-table.php' ) ) {
	require_once( NUNO_SARMENTO_SIM_CONTACT_FORM_PATH . 'admin/ns-contact-form-wp-list-table.php' );
}

 /* ------------------------------------------
 // External Form Class           ---------------------
 --------------------------------------------- */

if ( ! @include( 'ns-contact-external-form-class.php' ) ) {
	require_once( NUNO_SARMENTO_SIM_CONTACT_FORM_PATH . 'includes/ns-contact-external-form-class.php' );
}

/* ------------------------------------------
// Internal Form Class  ---------------------
--------------------------------------------- */

if ( ! @include( 'ns-contact-internal-form-class.php' ) ) {
 require_once( NUNO_SARMENTO_SIM_CONTACT_FORM_PATH . 'includes/ns-contact-internal-form-class.php' );
}



?>
