<?php
namespace NeuralNetworkLib\TrainingAlgorithms;

/**
 * Training algorithm class: Random
 *
 * Simply changes the weights randomly and keeps the top x configurations
 *
 */
class Random {

  /**
   * The netwotk to train
   */
  private $network;

  /**
   * Training config
   */
  private $config;

  /**
   * Best outcomes of the training
   */
  public $bestOutcomes;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Attempt to train the network
   *
   */
  public function __construct($network, $config = []) {
    $this->network = $network;
    $this->config = $this->readConfig($config);

    $this->run();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Set default configurations and override with those who are passed in
   *
   */
  private function readConfig($overrideConfig) {
    $config = [
      'outputDir' => '/',
      'bestOutcomesNum' => 5,
      'trainingRounds' => 1000
    ];

    array_merge($config, $overrideConfig);

    return $config;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run the training algorithm
   *
   */
  private function run() {
    $bestOutcomes     = [];
    $outputDir        = $this->config['outputDir'];
    $bestOutcomesNum  = $this->config['bestOutcomesNum'];
    $trainingRounds   = $this->config['trainingRounds'];

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
          //$innerError += exp($expectedOutput - $networkOutput);
          $innerError += exp($expectedOutput - $networkOutput);
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
        $synapse->setWeight($network->generateRandomWeight());
      }

    }

    var_dump($bestOutcomes);

    echo('Setting network to the best one saved.<br>');
    $network->load('top_'.$bestOutcomesNum.'_net_0.net');
  }

}
