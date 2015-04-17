<?php
/**
 * Plugin Name: XING for WordPress
 * Plugin URI: https://dev.xing.com/plugins/
 * Description: A plugin that allows you to easily integrate the XING Share button and the Follow button on any WordPress based website.
 * Version: 1.2.4
 * Author: Gaston Salgueiro
 * Author URI: https://www.xing.com/profile/Gaston_SalgueiroIglesias
 * License: GPLv2
 * License URI: license.txt
 */

class XING_Share_Loader {

  private $plugin_directory;

  public function __construct() {

    $this->plugin_directory = dirname(__FILE__) . '/';

    add_action( 'upgrader_process_complete', array( &$this, 'xing_plugin_upgrader' ), 10, 2 );

    // load shortcodes
    if ( ! class_exists( 'XING_Share_Shortcodes' ) )
      require_once( $this->plugin_directory . 'plugin/shortcodes.php' );

    XING_Share_Shortcodes::init();

    if ( ! class_exists( 'XING_Share_Widget' ) )
      require_once( $this->plugin_directory . 'plugin/xing-share-widget.php' );

    if ( ! class_exists( 'XING_Follow_Widget' ) )
      require_once( $this->plugin_directory . 'plugin/xing-follow-widget.php' );

    add_action( 'widgets_init', array( &$this, 'register_xing_widgets' ) );

    if (is_admin()) {
      if ( ! class_exists( 'XING_Share_Settings' ) ) {
        require_once( $this->plugin_directory . 'settings.php' );
        XING_Share_Settings::init();
      }
    } else {
      add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_static_files' ) );
      add_action( 'wp', array( &$this, 'xing_share_public_init' ) );
    }

  }

  public function register_xing_widgets() {
    register_widget( 'XING_Share_Widget' );
    register_widget( 'XING_Follow_Widget' );
  }

  public function enqueue_static_files() {
    wp_register_script( 'xing-share-script', 'https://www.xing-share.com/plugins/share.js', array(), null, true );
    wp_register_script( 'xing-follow-script', 'https://www.xing-share.com/plugins/follow.js', array(), null, true );

    add_filter('script_loader_src', array( &$this, 'async_script_loader_src' ), 1, 2);

    wp_register_style( 'xing-share-css', plugins_url( 'static/css/styles.css', __FILE__ ) );
    wp_enqueue_style( 'xing-share-css' );
  }

  public function enqueue_js() {
    wp_enqueue_script( 'xing-share-script' );
    wp_enqueue_script( 'xing-follow-script' );
  }

  public static function async_script_loader_src($src, $handle) {
    global $wp_scripts;

    if ($handle !== 'xing-share-script' && $handle !== 'xing-follow-script')
      return;

    $html = '<script>;(function (d, s, h) {var x = d.createElement(s),h = d.getElementsByTagName(h)[0];x.src = "' . $src . '";h.appendChild(x);})(document, "script", "head");</script>' . "\n";

    if ( isset( $wp_scripts ) && $wp_scripts->do_concat )
      $wp_scripts->print_html .= $html;
    else
      echo $html;

    // empty out the src response to avoid extra <script>
    return '';
  }

  public function xing_share_public_init() {
    add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_js' ) );

    $priority = apply_filters( 'xing_content_filter_priority', 20 );

    $options = get_option('xing_share');
    $xing_share_is_active = false;

    if (
      ( ( is_home() || is_front_page() ) && isset( $options['display_on']['home']) ) ||
      ( is_single() && isset ( $options['display_on']['post']) ) ||
      ( is_page() && isset ( $options['display_on']['page']) )
    ) {
      $xing_share_is_active = true;
    }

    if ( $xing_share_is_active ) {
      require_once( $this->plugin_directory . 'plugin/functions.php' );
      add_filter( 'the_content', 'xing_the_content_share_button', $priority );
    }
  }

}

function xing_share_loader_init() {
  $xing_share_loader = new XING_Share_Loader();
}

add_action( 'init', 'xing_share_loader_init', 0 );

?>
