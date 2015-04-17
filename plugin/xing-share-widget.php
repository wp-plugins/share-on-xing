<?php

class XING_Share_Widget extends WP_Widget {

  const DEFAULT_LAYOUT = "share-right";

  const DEFAULT_LANGUAGE = "en";

  private $plugin_directory;

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {
    $this->plugin_directory = dirname(__FILE__) . '/';

    parent::__construct(
       'xing-share', // Base ID
      __( 'Share on XING', 'xing' ), // Name
      array(
        'classname' => 'widget_xing_share',
        'description' => __( 'Lets visitors share your content on their XING feed.', 'xing' ) ) // Args
    );
  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {
    extract( $args );

    if ( ! class_exists( 'XING_Share_Button' ) )
      require_once( $this->plugin_directory . 'xing_share_button.php' );

    $share_button = new XING_Share_Button( $instance );

    if ( ! $share_button )
      return;

    $share_button_html = $share_button->asHTML();
    if ( ! ( is_string( $share_button_html ) && $share_button_html ) )
      return;

    echo $before_widget;

    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

    if ( $title )
      echo $before_title . $title . $after_title;

    echo $share_button_html;

    echo $after_widget;
  }

  /**
   * Outputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    $instance = wp_parse_args( (array) $instance, array(
      'title' => '',
      'url' => '',
      'layout' => 'no_count-default',
      'follow-url' => ''
    ) );

    $this->display_title( $instance['title'] );
    $this->display_layout( $instance['layout'] );
    $this->display_language( $instance['lang'] );
    $this->display_url( $instance['url'] );
    $this->display_follow_url( $instance['follow-url'] );

  }

  /**
   * Processing widget options on save
   *
   * @param array $new_instance The new options
   * @param array $old_instance The previous options
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $new_instance = (array) $new_instance;

    if ( ! empty( $new_instance['title'] ) )
      $instance['title'] = strip_tags( $new_instance['title'] );

    if ( ! empty( $new_instance['url'] ) )
      $instance['url'] = strip_tags( $new_instance['url'] );

    if ( ! empty( $new_instance['lang'] ) )
      $instance['lang'] = strip_tags( $new_instance['lang'] );

    if ( ! empty( $new_instance['layout'] ) )
      $instance['layout'] = strip_tags( $new_instance['layout'] );

    if ( ! empty( $new_instance['follow-url'] ) )
      $instance['follow-url'] = strip_tags( $new_instance['follow-url'] );

    return $instance;
  }

  public function display_title( $existing_value = '' ) {
    echo '<p><label>' . esc_html( __( 'Title', 'xing' ) ) . ': ';
    echo '<input type="text" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" class="widefat"';
    if ( $existing_value )
      echo ' value="' . esc_attr( $existing_value ) . '"';
    echo ' /></label></p>';
  }

  public function display_url( $existing_value = '' ) {
    echo '<p><label>URL to share: <input type="url" id="' . $this->get_field_id( 'url' ) . '" name="' . $this->get_field_name( 'url' ) . '" class="widefat"';
    if ( $existing_value )
      echo ' value="' . esc_url( $existing_value, array( 'http', 'https' ) ) . '"';
    echo ' /></label></p>';

    echo '<p class="description">' . esc_html( __( 'Default: Current\'s page URL', 'xing' ) ) . '</p>';
  }

  public function display_language( $existing_value = DEFAULT_LANGUAGE ) {
    if ( ! class_exists( 'XING_Share_Settings' ) )
      require_once( $this->plugin_directory . 'settings.php' );

      $language_options = XING_Share_Settings::$language_options; ?>

      <p>
          <label>Language:</label>
          <select name="<?php echo $this->get_field_name( 'lang' ); ?>" class="widefat"> <?php
          foreach ( $language_options as $language => $label ) {
            printf(
              '<option value="%s" %s>%s</option>',
              $language,
              ( $existing_value == $language ) ? 'selected="selected"' : '',
              $label
            );
          } ?>
          </select>
          </p> <?php
  }

  public function display_layout( $existing_value = DEFAULT_LAYOUT ) {
    if ( ! class_exists( 'XING_Share_Settings' ) )
      require_once( $this->plugin_directory . 'settings.php' );

    $layout_options = XING_Share_Settings::$layout_options; ?>

    <p>
      <label>Button layout:</label>
      <select name="<?php echo $this->get_field_name( 'layout' ); ?>" class="widefat"> <?php
      foreach ( $layout_options as $layout => $description ) {
        printf(
          '<option value="%s" %s>%s</option>',
          $layout,
          ( $existing_value == $layout ) ? 'selected="selected"' : '',
          $description
        );
      } ?>
      </select>
    </p> <?php
  }

  public function display_follow_url( $existing_value = '' ) {
    echo '<p><label>URL to follow: <input type="url" id="' . $this->get_field_id( 'follow-url' ) . '" name="' . $this->get_field_name( 'follow-url' ) . '" class="widefat"';
    if ( $existing_value )
      echo ' value="' . esc_url( $existing_value, array( 'http', 'https' ) ) . '"';

    echo ' /></label></p>';

    echo '<p class="description">' . esc_html( __( 'Optional. URL for the Follow button displayed on the success page after sharing. Must be a valid XING News or Company page URL.', 'xing' ) ) . '</p>';
  }
}
