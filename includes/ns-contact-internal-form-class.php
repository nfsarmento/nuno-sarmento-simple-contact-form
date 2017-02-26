<?php defined('ABSPATH') or die();
/* ------------------------------------------
// Internal Form Class  ---------------------
--------------------------------------------- */

/**
 * List page handler
 *
 * This function renders our custom table
 * Notice how we display message about successfull deletion
 * Actualy this is very easy, and you can add as many features
 * as you want.
 *
 * Look into /wp-admin/includes/class-wp-*-list-table.php for examples
 */
function ns_form_page_handler()
{
    global $wpdb;

    $table = new NS_Contact_Form_Table_List();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'nuno-sarmento-simple-contact-form'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Contact Form Entries', 'nuno-sarmento-simple-contact-form')?>
      <a class="add-new-h2"href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ns_simple_contact_form');?>"><?php _e('Add new', 'nuno-sarmento-simple-contact-form')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="ns-contact-form--table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}

/**
 * Form page handler checks is there some data posted and tries to save it
 * Also it renders basic wrapper in which we are callin meta box render
 */
function ns_contact_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ns_contact_form'; // do not forget about tables prefix
    $message = '';
    $notice = '';
    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => '',
    );

    if (isset($_POST['nonce'])) {
        // here we are verifying does this request is post back and have correct nonce
        if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
            // combine our default item with request params
            $item = shortcode_atts($default, $_REQUEST);
            // validate data, and if all ok save item to database
            // if id is zero insert otherwise update
            $item_valid = ns_cf_custom_table_validate_form($item);
            if ($item_valid === true) {
                if ($item['id'] == 0) {
                    $result = $wpdb->insert($table_name, $item);
                    $item['id'] = $wpdb->insert_id;
                    if ($result) {
                        $message = __('Item was successfully saved', 'nuno-sarmento-simple-contact-form');
                    } else {
                        $notice = __('There was an error while saving item', 'nuno-sarmento-simple-contact-form');
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    if ($result) {
                        $message = __('Item was successfully updated', 'nuno-sarmento-simple-contact-form');
                    } else {
                        $notice = __('There was an error while updating item', 'nuno-sarmento-simple-contact-form');
                    }
                }
            } else {
                // if $item_valid not true it contains error message(s)
                $notice = $item_valid;
            }
        }

   }

    else {
        // if this is not post back we load item to edit or give new one to create
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'nuno-sarmento-simple-contact-form');
            }
        }
    }

    // here we adding our custom meta box
    add_meta_box('ns_simple_contact_form_meta_box', 'Contact Form Entries', 'ns_simple_contact_form_meta_box_handler', 'entries', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Contac Form (Edit)', 'custom_table_example')?> <a class="add-new-h2"
      href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ns_simple_contact_form');?>"><?php _e('back to list', 'nuno-sarmento-simple-contact-form')?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php /* And here we call our custom meta box */ ?>
                    <?php do_meta_boxes('entries', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'nuno-sarmento-simple-contact-form')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
function ns_simple_contact_form_meta_box_handler($item)
{
    ?>

<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="name"><?php _e('Name', 'nuno-sarmento-simple-contact-form')?></label>
        </th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                   size="50" class="code" placeholder="<?php _e('Your name', 'nuno-sarmento-simple-contact-form')?>" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="email"><?php _e('E-Mail', 'nuno-sarmento-simple-contact-form')?></label>
        </th>
        <td>
            <input id="email" name="email" type="email" style="width: 95%" value="<?php echo esc_attr($item['email'])?>"
                   size="50" class="code" placeholder="<?php _e('Your E-Mail', 'nuno-sarmento-simple-contact-form')?>" required>
        </td>
    </tr>

    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="subject"><?php _e('Subject', 'nuno-sarmento-simple-contact-form')?></label>
        </th>
        <td>
            <input id="subject" name="subject" type="subject" style="width: 95%" value="<?php echo esc_attr($item['subject'])?>"
                   size="50" class="code" placeholder="<?php _e('Your subject', 'nuno-sarmento-simple-contact-form')?>" required>
        </td>
    </tr>

    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="message"><?php _e('Message', 'nuno-sarmento-simple-contact-form')?></label>
        </th>
        <td>

            <input id="message" name="message" type="message" style="width: 95%" value="<?php echo esc_attr($item['message'])?>"
                   size="150" rows="6" class="code" placeholder="<?php _e('Your message', 'nuno-sarmento-simple-contact-form')?>" required>
        </td>
    </tr>

    </tbody>
</table>
<?php
}

/**
 * Simple function that validates data and retrieve bool on success
 * and error message(s) on error
 *
 * @param $item
 * @return bool|string
 */
function ns_cf_custom_table_validate_form($item)
{
    $messages = array();

    if (empty($item['name'])) $messages[] = __('Name is required', 'nuno-sarmento-simple-contact-form');
    if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'nuno-sarmento-simple-contact-form');
    //if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'nuno-sarmento-simple-contact-form');
    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    if (empty($item['subject'])) $messages[] = __('Subject is required', 'nuno-sarmento-simple-contact-form');
    if (empty($item['message'])) $messages[] = __('Message is required', 'nuno-sarmento-simple-contact-form');

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}
