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
  public static function train($network, $config) {
    $this->network = $network;
    $this->config = $config;

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
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run the training algorithm
   *
   */
  private function run() {
    $bestOutcomes = [];
    $bestOutcomesNum = $this->config['bestOutcomesNum'];
    $trainingRounds = $this->config['trainingRounds'];

    for($i=0;$i<$trainingRounds;$i++) {

      $error = 0;
      foreach($this->trainingData as $trainingData) {
        $networkInputData = $trainingData[0];
        $expectedOutput   = $trainingData[1];
        $networkOutput    = $this->calculate($networkInputData);

        // THIS NEEDS TO BE GENERALIZED: ATM JUST CHECKING ERROR FOR THE FIRST OUTPUT/INPUT
        //$innerError = abs($expectedOutput[0] - $networkOutput[0]);
        $innerError = abs($expectedOutput[0] - $networkOutput[0]);
        $error += $innerError;
      }
      $error = $error/count($this->trainingData);
      //$error = sqrt($error);

      array_push($bestOutcomes, $error);
      sort($bestOutcomes);
      if(count($bestOutcomes) > $bestOutcomesNum) {
        array_pop($bestOutcomes);
      }

      $rank = array_search($error, $bestOutcomes);
      if($rank !== FALSE) {
        // We are one of the nest best! Save us!
        $this->save('top_'.$bestOutcomesNum.'_net_'.$rank.'.net');
      }

      //var_dump($bestOutcomes);

      // Randomly change all synapse weights
      $synapses = $this->allSynapses();
      foreach($synapses as $synapse) {
        $synapse->setWeight($this->generateRandomWeight());
      }

    }

    var_dump($bestOutcomes);

    echo('Setting network to the best one saved.<br>');
    $this->load('top_'.$bestOutcomesNum.'_net_0.net');
  }

}
