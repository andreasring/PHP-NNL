<?php
namespace NeuralNetworkLib\Components;

/**
 * Weight class
 *
 */
class Weight {

  /**
   *
   */
  public $value;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Weight connstruct
   *
   */
  public function __construct($value = 0.0) {
    $this->value          = $value;
  }

}
