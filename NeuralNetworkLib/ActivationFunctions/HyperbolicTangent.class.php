<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Hyperbolic Tangent
 *
 */
class HyperbolicTangent {

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

}
