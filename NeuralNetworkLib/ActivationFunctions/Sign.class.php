<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Sign
 *
 */
class Sign extends ActivationFunctionBase {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Sign activation function
   *
   * Returns -1 is x is less than a threshold
   * Returns 1 if x is bigger than a threshold
   *
   */
  public static function calculate($x, $threshold = 0) {
    if($x < $threshold) {
      return -1;
    }
    return 1;
  }

}
