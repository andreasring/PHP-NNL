<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Step
 *
 */
class Step extends ActivationFunctionBase {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Step activation function
   *
   * Returns 0 if x is less than a threshold and 1 if x is higher than a threshold
   *
   */
  public static function calculate($x, $threshold = 0) {
    if($x < $threshold) {
      return 0;
    }
    return 1;
  }

}
