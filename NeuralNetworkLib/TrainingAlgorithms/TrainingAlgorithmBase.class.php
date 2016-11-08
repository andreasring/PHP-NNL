<?php
namespace NeuralNetworkLib\TrainingAlgorithms;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Training algorithm base class
 *
 */
class TrainingAlgorithmBase {

  /**
   * The network to train
   */
  protected $network;

  /**
   * Trainer algorithm configuration
   */
  protected $configuration;

  /**
   * Training data
   */
  protected $trainingData;

  /**
   * Control data
   */
  protected $controlData;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Initialize a training algorithm
   *
   */
  public function __construct($network, $config = []) {
    $this->network = $network;
    $this->readConfig($config);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Allows user defined configurations to override default configurations
   *
   */
   protected function readConfig($userDefinedConfig) {
     $this->configuration = array_merge($this->configuration, $userDefinedConfig);
   }


   // --------------------------------------------------------------------------------------------------------
   /**
    * Add training data to the training algorithm
    *
    */
    public function addTrainingData($input, $output) {
      $this->trainingData[] = [$input, $output];
    }


    // --------------------------------------------------------------------------------------------------------
    /**
     * Add control data to the training algorithm
     *
     */
     public function addControlData($input, $output) {
       $this->controlData[] = [$input, $output];
     }

}
