<?php
class XING_Share_Settings {
  /**
  * Holds the values to be used in the fields callbacks
  */
  private static $options;

  private static $display_on_options = array(
    "home" => "Home",
    "post" => "Posts",
    "page" => "Pages"
  );

  private static $position_options = array(
    "both" => "Before and after the post",
    "before" => "Before the post",
    "after" => "After the post"
  );

  public static $layout_options = array(
    "share-right" => "\"Share\" label with counter on the right",
    "share-top" => "\"Share\" label with counter on top",
    "share" => "\"Share\" label with no counter",
    "xing-right" => "\"XING\" label with counter on the right",
    "xing-top" => "\"XING\" label with counter on top",
    "xing" => "\"XING\" label with no counter",
    "square-right" => "Square with counter on the right",
    "square-top" => "Square with counter on top",
    "square"  => "Square with no counter"
  );

  public static $language_options = array(
    "en" => "English",
    "de" => "Deutsch"
  );

  private static $defaults = array(
    'position' => 'before',
    'layout' => 'share-right',
    'language' => 'en'
  );

  /**
  * Start up
  */
  public function init()
  {
    add_action( 'admin_menu', array( XING_Share_Settings, 'add_plugin_page' ) );
    add_action( 'admin_init', array( XING_Share_Settings, 'page_init' ) );

    add_filter( 'plugin_action_links', array( XING_Share_Settings, 'add_settings_links'), 10, 2 );

    self::$options = get_option( 'xing_share' );
  }

  /**
  * Add options page
  */
  public function add_plugin_page()
  {
    global $xing_share_settings_page;

    // This page will be under "Settings"
    $xing_share_settings_page = add_menu_page(
      'Share on XING settings',
      'Share on XING',
      'manage_options',
      'xing-share-settings',
      array( XING_Share_Settings, 'create_admin_page' ),
      'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+DQo8c3ZnDQogICB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciDQogICB3aWR0aD0iMjAiDQogICBoZWlnaHQ9IjIwIj4NCiAgPGcNCiAgICAgdHJhbnNmb3JtPSJtYXRyaXgoMC4xOTYwMDMwNywwLDAsMC4xOTY1OTIzNSw0LC0xODkuODg2MzYpIg0KICAgICBzdHlsZT0iZmlsbDojZmZmZmZmIj4NCiAgICA8cGF0aA0KICAgICAgIGQ9Im0gNTIuMTI1LDk3Ni4wNjIxOCBjIC0xLjYsMCAtMi40LDEgLTIuOSwyLjEgMCwwIC0yMy43LDQyLjAwMDAyIC0yNC41LDQzLjQwMDAyIDAsMC4xIDE1LjYsMjguNyAxNS42LDI4LjcgMC41LDEgMS40LDIuMSAzLjEsMi4xIGggMTEgYyAwLjcsMCAxLjIsLTAuMiAxLjUsLTAuNyAwLjMsLTAuNSAwLjMsLTEuMSAwLC0xLjcgbCAtMTUuNSwtMjguMyBjIDAsMCAwLC0wLjEgMCwtMC4xIGwgMjQuNCwtNDMuMTAwMDIgYyAwLjMsLTAuNiAwLjMsLTEuMiAwLC0xLjcgLTAuMywtMC41IC0wLjgsLTAuNyAtMS41LC0wLjcgaCAtMTEuMiB6Ig0KICAgICAgIHN0eWxlPSJmaWxsOiNmZmZmZmYiIC8+DQogICAgPHBhdGgNCiAgICAgICBkPSJtIDUuOTI1LDk5MS4xNjIxOCBjIC0wLjcsMCAtMS4yLDAuMyAtMS41LDAuNyAtMC4zLDAuNSAtMC4zLDEuMSAwLDEuNyBsIDcuNSwxMi44MDAwMiBjIDAsMCAwLDAuMSAwLDAuMSBsIC0xMS43LDIwLjYgYyAtMC4zLDAuNiAtMC4zLDEuMiAwLDEuNyAwLjMsMC41IDAuOCwwLjcgMS41LDAuNyBoIDExIGMgMS42LDAgMi40LC0xLjEgMi45LC0yLjEgMCwwIDExLjUsLTIwLjIgMTEuOSwtMjEgMCwtMC4xIC03LjYsLTEzLjIwMDAyIC03LjYsLTEzLjIwMDAyIC0wLjUsLTEgLTEuNCwtMi4xIC0zLjEsLTIuMSBoIC0xMC45IHoiDQogICAgICAgc3R5bGU9ImZpbGw6I2ZmZmZmZiIgLz4NCiAgPC9nPg0KPC9zdmc+DQo='
    );

    add_action( 'admin_enqueue_scripts', array( XING_Share_Settings, 'xing_share_settings_static_files' ) );

    add_action('load-' . $xing_share_settings_page, array( XING_Share_Settings, 'display_legacy_configuration_detected_notice' ));
  }

  public function xing_share_settings_static_files($hook) {
    global $xing_share_settings_page;

    if ($hook == $xing_share_settings_page) {
      wp_register_script( 'xing-share-settings-javascripts', plugins_url( 'static/js/settings.js', __FILE__ ), ['jquery'] );
      wp_enqueue_script( 'xing-share-settings-javascripts' );

      wp_register_style( 'xing-share-settings-styles', plugins_url( 'static/css/settings.css', __FILE__ ) );
      wp_enqueue_style( 'xing-share-settings-styles' );
    }
  }

  function add_settings_links( $links, $file ) {
    global $xing_share_settings_page;

    if ( $file === plugin_basename( dirname(__FILE__) . '/share-on-xing.php' ) ) {
      $links[] = '<a href="' . admin_url( 'options-general.php?page=xing-share-settings' ) . '">Settings</a>';
    }
    return $links;
  }

  /**
  * Options page callback
  */
  public function create_admin_page() { ?>
    <div class="wrap">
      <h2>Share on XING</h2>
      <p>Allow your visitors share your posts and pages on their XING newsfeed with one click.</p>
      <form method="post" action="options.php"><?php
        settings_fields( 'xing_share_options' );
        do_settings_sections( 'xing-share-settings' );
        submit_button(); ?>
      </form>
    </div><?php
  }

  public function sanitize( $input ) {
    return $input;
  }

  public function page_init() {

    register_setting(
      'xing_share_options', // Option group
      'xing_share', // Option name
      array( XING_Share_Settings, 'sanitize' )
    );

    add_settings_section(
      'xing_share_general', // ID
      null, // Title
      function() { return; }, // Callback
      'xing-share-settings' // Page
    );

    add_settings_field(
      'xing_share_display_on', // ID
      'Display on', // Title
      array( XING_Share_Settings, 'xing_share_display_on_callback' ), // Callback
      'xing-share-settings', // Page
      'xing_share_general' // Section
    );

    add_settings_field(
      'xing_share_position',
      'Position',
      array( XING_Share_Settings, 'xing_share_position_callback' ),
      'xing-share-settings',
      'xing_share_general'
    );

    add_settings_field(
      'xing_share_layout',
      'Button layout',
      array( XING_Share_Settings, 'xing_share_layout_callback' ),
      'xing-share-settings',
      'xing_share_general'
    );

    add_settings_field(
      'xing_share_language',
      'Language',
      array( XING_Share_Settings, 'xing_share_language_callback' ),
      'xing-share-settings',
      'xing_share_general'
    );

    add_settings_field(
      'xing_share_label',
      'Label',
      array( XING_Share_Settings, 'xing_share_label_callback' ),
      'xing-share-settings',
      'xing_share_general'
    );
  }

  public function xing_share_display_on_callback()
  { ?>
    <fieldset><?php
      foreach ( self::$display_on_options as $key => $label ) {
        printf(
          '<label><input type="checkbox" name="xing_share[display_on][%s]" value="%s" %s /> %s</label>',
          $key,
          true,
          (self::$options['display_on'][$key] == true) ? 'checked="checked"' : '',
          $label
        );
      } ?>
    </fieldset><?php
  }

  public function xing_share_position_callback()
  {
    if ( ! isset( self::$options['position'] ) )
      self::$options['position'] = self::$defaults['position']; ?>
    <select name="xing_share[position]"><?php
      foreach ( self::$position_options as $key => $label ) {
        printf(
          '<option value="%s" %s>%s</option>',
          $key,
          ( self::$options['position'] === $key ) ? 'selected="selected"' : '',
          $label
        );
      } ?>
    </select><?php
  }

  public function xing_share_layout_callback()
  {
    if ( ! isset( self::$options['layout'] ) )
      self::$options['layout'] = self::$defaults['layout']; ?>
    <fieldset>
        <ul class="xing-share-layout-options"><?php
          foreach ( self::$layout_options as $layout => $description ) {
            $selected = false;
            if ( self::$options['layout'] == $layout) {
              $selected = true;
            }
            printf(
              '<li class="xing-share-layout-option %s %s"><label><input type="radio" name="xing_share[layout]" value="%s" %s /> %s</label></li>',
              $layout,
              ($selected) ? 'selected' : '',
              $layout,
              ($selected) ? 'checked="checked"' : '',
              $layout
            );
          } ?>
        </ul>
    </fieldset><?php
  }

  public function xing_share_language_callback()
  {
    if ( ! isset( self::$options['language'] ) )
      self::$options['language'] = self::$defaults['language']; ?>
    <fieldset><?php
      foreach ( self::$language_options as $lang => $label ) {
        printf(
          '<label><input type="radio" class="xing-share-language-option" value="%s" %s name="xing_share[language]">%s</label>',
          $lang,
          ( self::$options['language'] == $lang ) ? 'checked="checked"' : '',
          $label
        );
      } ?>
    </fieldset><?php
  }

  public function xing_share_label_callback()
  {
    printf(
      '<input type="text" placeholder="Share this article" name="xing_share[label]" value="%s" />',
      self::$options['label']
    );
  }

  private function xing_share_legacy_configuration_detected() {
    return (self::$options['layout'] === 'default' || self::$options['layout'] === 'default-right' || self::$options['layout'] === 'default-top' || self::$options['layout'] === 'small_square');
  }

  public function display_legacy_configuration_detected_notice() {
    global $xing_share_settings_page;

    $screen = get_current_screen();

    if ( $screen->id == $xing_share_settings_page )
      add_action('admin_notices', array( XING_Share_Settings, 'xing_share_legacy_configuration_detected_notice' ));
  }

  public function xing_share_legacy_configuration_detected_notice() {
    if ( self::xing_share_legacy_configuration_detected() === true ) { ?>
      <div class="update-nag">
        <p><strong>Your Share on XING plugin configuration is legacy.</strong> <br>
          After v1.0.8 the <strong>Share on XING</strong> plugin supports new and updated button layouts. You should select the one that best supports your needs bellow.<br>
          The plugin is still displayed properly to your visitors until you do so.
        </p>
      </div><?php
    }
  }

}
