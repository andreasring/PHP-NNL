<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Rectifier (ReLU)
 *
 */
class Rectifier extends ActivationFunctionBase {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Rectifier activation function
   *
   * Returns 0 if x is less than 0
   * Else it returns x
   *
   */
  public static function calculate($x) {
    if($x < 0) {
      return 0;
    }
    return $x;
  }

}
