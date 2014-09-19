<?php

class XING_Share_Shortcodes {

  public static function init()
  {
    add_shortcode( 'xing_share', array( 'XING_Share_Shortcodes', 'xing_share_button' ) );
  }

  public static function xing_share_button( $attributes )
  {
    $shortcode_attributes = shortcode_atts( array(
      'url' => '',
      'layout' => '',
      'lang' => '',
    ), $attributes, 'xing_share_button' );

    if ( ! function_exists( 'xing_get_share_button' ) )
      require_once( dirname(__FILE__) . '/functions.php' );

    return xing_get_share_button( $shortcode_attributes );
  }

}

?>
