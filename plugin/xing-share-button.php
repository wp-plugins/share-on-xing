<?php

class XING_Share_Button {

  const DEFAULT_COUNTER = 'no_count';

  private $configuration = array();

  public function __construct( $options ) {
    $options = self::getCounterAndShapeFromLayout( $options );
    $this->configuration = self::fixInvalidConfiguration( $options );
  }

  public function asHTML() {
    $html = '<div ';

    if ( isset( $this->configuration['shape'] ) )
      $html .= 'data-button-shape="'. $this->configuration['shape'] .'" ';

    if ( isset( $this->configuration['counter'] ) )
      $html .= 'data-counter="' . $this->configuration['counter'] . '" ';

    if ( isset( $this->configuration['lang'] ) )
      $html .= 'data-lang="'. $this->configuration['lang'] .'" ';

    if ( isset( $this->configuration['url'] ) )
      $html .= 'data-url="'. $this->configuration['url'] .'" ';

    $html .= 'data-type="XING/Share"></div>';

    return $html;
  }

  private function getCounterAndShapeFromLayout( $attributes = array() ) {
    if ( isset( $attributes['layout'] ) ) {
      $counter_shape = explode('-', $attributes['layout']);

      if ( count( $counter_shape ) === 2 ) {
        $attributes['shape'] = ( $counter_shape[0] === 'default' ) ? null : $counter_shape[0];
        $attributes['counter'] = $counter_shape[1];
      } else {
        $attributes['shape'] = $attributes['layout'];
      }

      unset( $attributes['layout'] );
    }

    return $attributes;
  }

  private function fixInvalidConfiguration( $attributes = array() ) {
    if ( isset( $attributes['shape'] ) ) {

      if ( ! (($attributes['shape'] === 'square') || ($attributes['shape'] === 'small_square')) ) {
        unset( $attributes['shape'] );
        $attributes['counter'] = self::DEFAULT_COUNTER;
      }

      if ( $attributes['shape'] === 'square' ) {
        if ( $attributes['counter'] !== 'right' ) {
          $attributes['counter'] = self::DEFAULT_COUNTER;
        }
      } else if ( $attributes['shape'] === 'small_square' ) {
        $attributes['counter'] = self::DEFAULT_COUNTER;
      }

    } else {

      if ( isset( $attributes['counter'] ) ) {
        if ( ! ($attributes['counter'] === 'top') ) {
          $attributes['counter'] = 'right';
        }
      } else {
        $attributes['counter'] = self::DEFAULT_COUNTER;
      }

    }

    if ( isset( $attributes['lang']) ) {
      if ( ! ($attributes['lang'] === 'de') ) {
        unset( $attributes['lang'] );
      }
    }

    return $attributes;
  }

}
?>
