<?php
/**
 * Plugin Name: Call Twitter X
 * Plugin URI: https://ivanca.tumblr.com/
 * Description: Wordpress plugin to change all instances of "Twitter" for "X", "Tweets" for "posts at X" and "Tweet" for "post at X"
 * Version: 0.1
 * Author: Ivan castellanos
 * Author URI: https://ivanca.tumblr.com/
 * License: MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */

add_action('admin_menu', 'word_replacement_admin_menu');
function word_replacement_admin_menu() {
    add_options_page('Call Twitter X Settings', 'Call Twitter X', 'manage_options', 'twitter-replacement-settings', 'word_replacement_settings_page');
}

add_action('admin_init', 'word_replacement_settings_init');
function word_replacement_settings_init() {
    register_setting('twitter-replacement-settings', 'company_name');
    register_setting('twitter-replacement-settings', 'new_company_name');
    register_setting('twitter-replacement-settings', 'word_for_posts');
    register_setting('twitter-replacement-settings', 'new_word_for_posts');
    register_setting('twitter-replacement-settings', 'word_for_plural_posts');
    register_setting('twitter-replacement-settings', 'new_word_for_plural_posts');
    register_setting('twitter-replacement-settings', 'add_formerly');
}

function word_replacement_settings_page() {
?>
    <div class="wrap">
        <h2>Call Twitter X Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('twitter-replacement-settings'); ?>
            <?php do_settings_sections('twitter-replacement-settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row" style="min-width: 250px;">Replace "Twitter" for:</th>
                    <td><input type="text" name="new_company_name" value="<?php echo esc_attr(get_option('new_company_name', 'X')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Replace "Tweets" (plural) for:</th>
                    <td><input type="text" name="new_word_for_plural_posts" value="<?php echo esc_attr(get_option('new_word_for_plural_posts', 'posts at X')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Replace "Tweet" for:</th>
                    <td><input type="text" name="new_word_for_posts" value="<?php echo esc_attr(get_option('new_word_for_posts', 'post at X')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Add "(formerly Twitter)" after each:</th>
                    <td>
                      <input type="hidden" name="add_formerly" value="0">
                      <input type="checkbox" name="add_formerly" value="1" <?php checked(1, get_option('add_formerly', 0), true); ?> /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

add_filter('the_content', 'replace_word_in_content');
add_filter('the_title', 'replace_word_in_content');
function replace_word_in_content($content) {
    $company_name = get_option('company_name', 'Twitter');
    $new_company_name = get_option('new_company_name', 'X');
    $word_for_posts = get_option('word_for_posts', 'Tweet');
    $new_word_for_posts = get_option('new_word_for_posts', 'post at X');
    $word_for_plural = get_option('word_for_plural_posts', 'Tweets');
    $new_word_for_plural = get_option('new_word_for_plural_posts', 'posts at X');

    $add_formerly = get_option('add_formerly', 0);
    if ($add_formerly) {
        $new_company_name .= " (formerly Twitter)";
        $new_word_for_posts .= " (formerly Twitter)";
        $new_word_for_plural .= " (formerly Twitter)";
    }

    $return = str_ireplace($company_name, $new_company_name, $content);
    $return = str_ireplace($word_for_plural, $new_word_for_plural, $return);
    $return = str_ireplace($word_for_posts, $new_word_for_posts, $return);
    return $return;
}
