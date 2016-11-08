<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Gaussian
 *
 */
class Gaussian extends ActivationFunctionBase {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Gaussian activation function
   *
   */
  public static function calculate($x) {
    return exp(pow(-$x, 2));
  }

}
