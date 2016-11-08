<?php
namespace NeuralNetworkLib\TrainingAlgorithms;

use \NeuralNetworkLib\Helpers as Helpers;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Training algorithm class: Random
 *
 * Simply changes the weights randomly and keeps the top x configurations
 *
 */
class Random extends TrainingAlgorithmBase {

  /**
   * Defines default configuration for this algorithm
   */
  protected $configuration = [
    'outputDir' => '/',
    'bestOutcomesNum' => 5,
    'trainingRounds' => 1000
  ];

  /**
   * Best outcomes of the training
   */
  public $bestOutcomes;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run the training algorithm
   *
   */
  private function train() {
    $bestOutcomes     = [];
    $outputDir        = $this->configuration['outputDir'];
    $bestOutcomesNum  = $this->configuration['bestOutcomesNum'];
    $trainingRounds   = $this->configuration['trainingRounds'];

    $network          = $this->network;
    $trainingDatas    = $network->trainingData;

    for($i=0;$i<$trainingRounds;$i++) {

      $error = 0.0;
      foreach($trainingDatas as $trainingData) {
        $networkInputData = $trainingData[0];
        $expectedOutputs  = $trainingData[1];
        $networkOutputs   = $network->calculate($networkInputData);

        $innerError = 0.0;
        foreach($networkOutputs as $index => $networkOutput) {
          $expectedOutput = $expectedOutputs[$index];
          //$innerError += pow($expectedOutput - $networkOutput, 2);
          $innerError += pow($expectedOutput - $networkOutput, 2);
        }
        $innerError = $innerError / count($networkOutputs);
        //$innerError = sqrt($innerError);

        $error += $innerError;
      }
      $error = $error/count($trainingDatas);

      array_push($bestOutcomes, $error);
      sort($bestOutcomes);
      if(count($bestOutcomes) > $bestOutcomesNum) {
        array_pop($bestOutcomes);
      }

      $rank = array_search($error, $bestOutcomes);
      if($rank !== FALSE) {
        // We are one of the nest best! Save us!
        $network->save('top_'.$bestOutcomesNum.'_net_'.$rank.'.net');
      }


      // Randomly change all synapse weights
      $synapses = $network->allSynapses();
      foreach($synapses as $synapse) {
        $synapse->setWeight(Helpers\Misc::generateRandomWeight());
      }

    }

    var_dump($bestOutcomes);

    echo('Setting network to the best one saved.<br>');
    $network->load('top_'.$bestOutcomesNum.'_net_0.net');
  }

}
