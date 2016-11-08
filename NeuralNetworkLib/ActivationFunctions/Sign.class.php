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
   * Returns -1 is x is less than 0
   * Returns 0 if x is equal to 0
   * Returns 1 if x is bigger than 0
   *
   */
  public static function calculate($x) {
    if($x < 0) {
      return -1;
    }
    if($x == 0) {
      return 0;
    }
    return 1;
  }

}
