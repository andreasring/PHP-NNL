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
   * The weight's value
   */
  public $value = 0.0;

  /**
   * The network the weight is part of
   */
  protected $network;

  // --------------------------------------------------------------------------------------------------------
  /**
   * Weight connstruct
   *
   */
  public function __construct($network, $value = 0.0) {
    // Auto incrementing ID
    static $weightID      = 0;
    $this->ID             = $weightID++;

    // Set data
    $this->value = $value;

    // Add the weight to the network
    $network->addWeight($this);
  }

}
