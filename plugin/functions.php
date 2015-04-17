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

function xing_get_follow_button( $options = array() ) {
  if ( ! class_exists( 'XING_Follow_Button' ) ) {
    require_once( dirname(__FILE__) . '/xing-follow-button.php' );
  }

  $follow_button = new XING_Follow_Button( $options );

  if ( !$follow_button )
    return null;

  $html = $follow_button->asHTML();

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

  $share_button_options['layout'] = $options['layout'];

  $share_button_options['url'] = get_permalink( $post->ID );

  $share_button_options['lang'] = $options['language'];

  $share_button_options['follow-url'] = ( $options['is_valid_follow_url'] ) ? $options['follow_url'] : null;

  if ( $options['follow_enabled'] === 'true' && $options['is_valid_follow_url'] ) {
    $follow_button_options['url'] = $options['follow_url'];

    $follow_button_options['lang'] = $options['language'];

    if ( !empty( $options['follow_counter'] ) ) {
      $follow_button_options['counter'] = 'right';
    }
  }

  $wrappedButtons = xing_wrap_button_with_container( $options['label'], xing_get_share_button( $share_button_options ), xing_get_follow_button( $follow_button_options ) );

  if ( $options['position'] === 'before' ) {
    return $wrappedButtons . $content;
  } else if ( $options['position'] === 'after' ) {
    return $content . $wrappedButtons;
  } else if ( $options['position'] === 'both' ) {
    return $wrappedButtons . $content . $wrappedButtons;
  }

  // don't break the filter
  return $content;
}

function xing_wrap_button_with_container( $label = null, $share_button, $follow_button = null ) {
  $html = '<div class="xing-share-bar xing-social-plugins">';
  if ( isset($label) )
    $html .= $label;
  $html .= $share_button . $follow_button . '</div>';

  return $html;
}

?>
