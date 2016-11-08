<?php
namespace NeuralNetworkLib\Components\Weight;

/**
 * Weight base class
 *
 */
class WeightBase {

  /**
   * Weight ID
   */
  public $ID;

  /**
   * The weight's weight
   */
  public $weight;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Weight connstruct
   *
   */
  public function __construct($weight = 0.0) {
    // Auto incrementing ID
    static $weightID      = 0;
    $this->ID             = $weightID++;

    // Set data
    $this->weight = $weight;
  }

}
