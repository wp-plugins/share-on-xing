<?php

function xing_get_share_button( $options = array() ) {
  if ( ! class_exists( 'XING_Share_Button' ) ) {
    require_once( dirname(__FILE__) . '/xing-share-button.php' );
  }

  $share_button = new XING_Share_Button( $options );

  if ( ! $share_button )
    return '';

  $html = $share_button->asHTML();

  if ($html)
    return "\n" . $html . "\n";

  return '';
}

function xing_the_content_share_button( $content ) {
  global $post;

  // Share Buttons should not be the only content in the post
  if ( ! $content )
    return $content;

  $options = get_option( 'xing_share' );

  if ( ! is_array( $options ) )
    $options = array();

  if ( ! isset( $options['display_on'] ) )
    return $content;

  $button_options['layout'] = $options['layout'];

  $button_options['url'] = get_permalink( $post->ID );

  $button_options['lang'] = $options['language'];

  $wrappedButton = xing_wrap_button_with_container( $options['label'], xing_get_share_button( $button_options ) );

  if ( $options['position'] === 'before' ) {
    return $wrappedButton . $content;
  } else if ( $options['position'] === 'after' ) {
    return $content . $wrappedButton;
  } else if ( $options['position'] === 'both' ) {
    return $wrappedButton . $content . $wrappedButton;
  }

  // don't break the filter
  return $content;
}

function xing_wrap_button_with_container( $label = null, $button ) {
  $html = '<div class="xing-share-bar">';
  if ( isset($label) )
    $html .= $label;
  $html .= $button . '</div>';

  return $html;
}

?>
