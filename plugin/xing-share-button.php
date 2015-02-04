<?php

class XING_Share_Button {

  const DEFAULT_COUNTER = 'no_count';

  private $configuration = array();

  public function __construct( $options ) {
    $options = self::backwardsCompatible( $options );

    $this->configuration = self::getConfigFrom( $options );
  }

  public function asHTML() {
    $html = '<div data-platform="wordpress" ';

    if ( isset( $this->configuration['shape'] ) )
      $html .= 'data-shape="'. $this->configuration['shape'] .'" ';

    if ( isset( $this->configuration['counter'] ) )
      $html .= 'data-counter="' . $this->configuration['counter'] . '" ';

    if ( isset( $this->configuration['label'] ) )
      $html .= 'data-label="' . $this->configuration['label'] . '" ';

    if ( isset( $this->configuration['lang'] ) )
      $html .= 'data-lang="'. $this->configuration['lang'] .'" ';

    if ( isset( $this->configuration['url'] ) )
      $html .= 'data-url="'. $this->configuration['url'] .'" ';

    $html .= 'data-type="xing/share"></div>';

    return $html;
  }

  private function getConfigFrom( $options = array() ) {
    if ( isset( $options['layout'] ) ) {

      $config = explode('-', $options['layout']);

      if ($config[0] === "share")
        $options['label'] = $config[0];

      else if ($config[0] === "square")
        $options['shape'] = $config[0];

      if (isset($config[1]))
        $options['counter'] = $config[1];

      unset( $options['layout'] );
    }

    return $options;
  }

  private function backwardsCompatible($options = array()) {
    if ( isset( $options['layout'] ) ) {

      switch($options['layout']) {
        case "default-top":
          $options['layout'] = "xing-top";
          break;
        case "default-right":
          $options['layout'] = "xing-right";
          break;
        case "default":
          $options['layout'] = "xing";
          break;
        case "small_square":
          $options['layout'] = "square";
          break;
      }

    }

    if ( isset( $options['lang']) ) {
      if ( ! ($options['lang'] === 'de') ) {
        unset( $options['lang'] );
      }
    }

    return $options;
  }

}
?>
