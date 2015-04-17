<?php

class XING_Follow_Widget extends WP_Widget {

  const DEFAULT_LANGUAGE = "en";

  private $plugin_directory;

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {
    $this->plugin_directory = dirname(__FILE__) . '/';

    parent::__construct(
       'xing-follow', // Base ID
      __( 'Follow on XING', 'xing' ), // Name
      array(
        'classname' => 'widget_xing_follow',
        'description' => __( 'Lets visitors follow your XING News or Company pages.', 'xing' ) ) // Args
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

    if ( ! class_exists( 'XING_Follow_Button' ) )
      require_once( $this->plugin_directory . 'xing_follow_button.php' );

    $follow_button = new XING_Follow_Button( $instance );

    $follow_button_html = $follow_button->asHTML();

    if ( ! ( is_string( $follow_button_html ) && $follow_button_html ) )
      return;

    echo $before_widget;

    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

    if ( $title )
      echo $before_title . $title . $after_title;

    echo $follow_button_html;

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
    ) );

    $this->display_title( $instance['title'] );
    $this->display_language( $instance['lang'] );
    $this->display_layout( $instance['counter'] );
    $this->display_url( $instance['url'] );

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

    if ( ! empty( $new_instance['counter'] ) )
      $instance['counter'] = strip_tags( $new_instance['counter'] );

    return $instance;
  }

  public function display_title( $existing_value = '' ) {
    echo '<p><label>' . esc_html( __( 'Title', 'xing' ) ) . ': ';
    echo '<input type="text" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" class="widefat"';
    if ( $existing_value )
      echo ' value="' . esc_attr( $existing_value ) . '"';
    echo ' placeholder="Follow us on XING" /></label></p>';
  }

  public function display_url( $existing_value = '' ) {
    echo '<p><label>URL to follow: <input type="url" id="' . $this->get_field_id( 'url' ) . '" name="' . $this->get_field_name( 'url' ) . '" class="widefat"';
    if ( $existing_value )
      echo ' value="' . esc_url( $existing_value, array( 'http', 'https' ) ) . '"';
    echo ' placeholder="https://www.xing.com/company/xing" /></label></p>';

    echo '<p class="description">' . esc_html( __( 'Must be a valid XING News or Company page.', 'xing' ) ) . '</p>';
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

  public function display_layout( $existing_value = null ) { ?>
    <p>
      <label><?php
        printf(
          '<input name="%s" type="checkbox" value="right" %s>',
          $this->get_field_name( 'counter' ),
          (isset($existing_value)) ? 'checked="checked"' : ''
        ); ?>
        Show followers counter
      </label>
    </p> <?php
  }

}
