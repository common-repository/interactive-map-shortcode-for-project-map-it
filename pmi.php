<?php
/**
 * @package PMI
 */
/*
Plugin Name: Interactive Map Shortcode for Project Map It
Plugin URI: http://wordpress.org/plugins/interactive-map-shortcode-for-project-map-it/
Description: Project Map It is the easiest way to showcase projects, locations, or store locations on a beautiful and interactive map. <a target="_blank" href="https://projectmap.it/?r=wpp">Project Map it</a> is a feature-packed turn-key mapping solution which includes customer review sncing reviews from Google, Facebook, and Zillow, and much more.
Version: 1.0.0
Author: PMI Engineering
Author URI: https://projectmap.it
License: GPLv2 or later
Text Domain: pmi
*/

function pmi_register_settings() {
   add_option( 'pmi_username', '');
   register_setting( 'pmi_options_group', 'pmi_username', 'pmi_callback' );
}

add_action( 'admin_init', 'pmi_register_settings' );

function pmi_register_options_page() {
  add_options_page('Project Map It', 'Map Settings', 'manage_options', 'pmi', 'pmi_options_page');
}

add_action('admin_menu', 'pmi_register_options_page');

function pmi_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h2>Project Map It</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'pmi_options_group' ); ?>
  <h3>Create a Map</h3>
  <ol>
    <li><p>Setup your map on <a href="https://projectmap.it/register" target="_blank">Project Map It</a>.</p></li>
    <li><p>Add your username in the form below. You can find the username on your public Hub Page URL (<code>https://projectmap.it/[YOUR-USERNAME]</code>)</p></li>
    <li><p>Use the <code>[map]</code> shortcode anywhere you want the map to show in a post or page.</p></li>
  </ol>
  <table>
    <tr valign="top">
    <th scope="row"><label for="pmi_username">Project Map It Username</label></th>
    <td><input type="text" placeholder="my-username" id="pmi_username" name="pmi_username" value="<?php echo get_option('pmi_username'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}


function pmi_map_func( $atts ) {
    $a = shortcode_atts(array(), $atts );
    $username = get_option('pmi_username');

    if (!$username) {
      return '<div style="text-align:center">You must <a href="/wp-admin/options-general.php?page=pmi">configure your Project Map It username</a> to embed a map.</div>';
    }

    return '<script>
(function(w) {
  w.__PMISRC__ = "https://projectmap.it/' . $username . '/map";
  var s = document.createElement(\'script\');
  s.async = true;
  s.src = "https://projectmap.it/static/js/embed.js";
  document.head.appendChild(s);
})(window);
</script>
<div id="__pmiembed"></div>';
}

add_shortcode('map', 'pmi_map_func');
