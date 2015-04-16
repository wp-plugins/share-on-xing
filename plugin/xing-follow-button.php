<?php

class XING_Follow_Button {

  private $configuration = array();

  public function __construct( $options ) {
    $this->configuration = $options;
  }

  public function asHTML() {

    if ( empty( $this->configuration['url'] ) )
      return null;

    $html = '<div data-platform="wordpress" ';

    if ( !empty( $this->configuration['counter'] ) )
      $html .= 'data-counter="' . $this->configuration['counter'] . '" ';

    if ( !empty($this->configuration['lang']) )
      $html .= 'data-lang="'. $this->configuration['lang'] .'" ';

    $html .= 'data-url="'. $this->configuration['url'] .'" ';

    $html .= 'data-type="xing/follow"></div>';

    return $html;
  }

}
?>
