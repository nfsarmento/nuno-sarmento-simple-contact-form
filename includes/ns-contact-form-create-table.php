<?php defined('ABSPATH') or die();
/* ------------------------------------------
// Create DB Table      ---------------------
--------------------------------------------- */


global $ns_cf_custom_table_example_db_version;
$ns_cf_custom_table_example_db_version = '1.0'; // version changed from 1.0 to 1.1

function ns_cf_custom_table_example_install()
{
    global $wpdb;
    global $ns_cf_custom_table_example_db_version;

    $table_name = $wpdb->prefix . 'ns_contact_form'; // do not forget about tables prefix

    $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      name tinytext NOT NULL,
      email VARCHAR(200) NOT NULL,
      subject VARCHAR(200) NOT NULL,
      phone VARCHAR(200) NOT NULL,
      message VARCHAR(200) NOT NULL,
      created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    );";

    // we do not execute sql directly
    // we are calling dbDelta which cant migrate database
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // save current database version for later use (on upgrade)
    add_option('ns_cf_custom_table_example_db_version', $ns_cf_custom_table_example_db_version);

    /**
     * [OPTIONAL] Example of updating to 1.1 version
     *
     * If you develop new version of plugin
     * just increment $ns_cf_custom_table_example_db_version variable
     * and add following block of code
     *
     * must be repeated for each new version
     * in version 1.1 we change email field
     * to contain 200 chars rather 100 in version 1.0
     * and again we are not executing sql
     * we are using dbDelta to migrate table changes
     */
    $installed_ver = get_option('ns_cf_custom_table_example_db_version');
    if ($installed_ver != $ns_cf_custom_table_example_db_version) {
        $sql = "CREATE TABLE " . $table_name . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          name tinytext NOT NULL,
          email VARCHAR(100) NOT NULL,
          subject VARCHAR(200) NOT NULL,
          phone VARCHAR(100) NOT NULL,
          message VARCHAR(200) NOT NULL,
          created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('ns_cf_custom_table_example_db_version', $ns_cf_custom_table_example_db_version);
    }
}

register_activation_hook(__FILE__, 'ns_cf_custom_table_example_install');

/**
 * Trick to update plugin database, see docs
 */
function custom_table_example_update_db_check()
{
    global $ns_cf_custom_table_example_db_version;
    if (get_site_option('ns_cf_custom_table_example_db_version') != $ns_cf_custom_table_example_db_version) {
        ns_cf_custom_table_example_install();
    }
}

add_action('plugins_loaded', 'custom_table_example_update_db_check');
