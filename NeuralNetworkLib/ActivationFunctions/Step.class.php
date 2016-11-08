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
   * Returns 0 if x is less than 0 and 1 if x is higher than 0
   *
   */
  public static function calculate($x) {
    if($x < 0) {
      return 0;
    }
    return 1;
  }

}
