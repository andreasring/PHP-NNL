<?php
namespace NeuralNetworkLib\CostFunctions;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Cost function: Absolute error
 *
 */
class AbsoluteError extends CostFunctionBase {

  /**
   * Calculates the absolute error between two datasets
   *
   */
  public static function calculate($correctData, $networkData) {
    // Initialize error sum
    $error = 0.0;

    // Loop through the datasets and take the difference
    foreach($networkData as $index => $output) {
      // Square difference
      $error += $correctData[$index] - $output;
    }

    // Take the average (mean) error
    $error = $error / count($networkData);

    // Return the root mean squared error
    return $error;
  }

}

?>
