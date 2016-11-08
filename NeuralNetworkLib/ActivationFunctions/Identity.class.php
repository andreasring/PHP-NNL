<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Identity
 *
 */
class Identity extends ActivationFunctionBase {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Identity activation function
   *
   * Just returns the input
   *
   */
  public static function calculate($x) {
    return $x;
  }

}
