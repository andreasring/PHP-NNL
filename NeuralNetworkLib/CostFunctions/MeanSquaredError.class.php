<?php
namespace NeuralNetworkLib\CostFunctions;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Cost function: Mean Squared Error
 *
 */
class MeanSquaredError extends CostFunctionBase {

  /**
   * Calculates the Mean Squared error between two datasets
   *
   */
  public static function calculate($correctData, $networkData) {
    // Initialize error sum
    $error = 0.0;

    // Loop through the datasets and take the difference
    foreach($networkData as $index => $output) {
      // Square difference
      $error += pow($correctData[$index] - $output, 2);
    }

    // Take the average (mean) error
    $error = $error / count($networkData);

    // Return the root mean squared error
    return $error;
  }

}

?>
