<?php
namespace NeuralNetworkLib\ActivationFunctions;

/**
 * Activation function for neurons: Sigmoid
 *
 */
class Sigmoid {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Sigmoid activation function
   *
   * Returns a value between 0 and 1
   *
   */
  public static function calculate($x) {
    return 1 / (1 + exp(-$x));

    // Alternative syntax
    // return 1 / (1 + pow(M_E, -$x));
  }

}
