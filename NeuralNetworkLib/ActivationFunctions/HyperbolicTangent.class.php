<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Hyperbolic Tangent
 *
 */
class HyperbolicTangent extends ActivationFunctionBase {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Hyperbolic tangent activation function
   *
   * Returns a value between -1 and 1
   *
   */
  public static function calculate($x) {
    return tanh($x);
    // (1.0 / (1.0 + exp(- $value)));
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Hyperbolic tangent activation function derivative
   *
   */
  public static function derivative($x) {
    return 1.0 - pow($x, 2);
  }

}
