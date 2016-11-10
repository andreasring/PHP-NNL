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
  public $network;

  /**
   * Trainer algorithm configuration
   */
  public $configuration = [];

  /**
   * Training data
   */
  public $trainingData;

  /**
   * Control data
   */
  public $controlData;

  /**
   * Stores the recorded error values if enabled
   */
  public $recordedErrorValues = [];


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
  public function readConfig($userDefinedConfig) {
    $this->configuration = array_merge($this->configuration, $userDefinedConfig);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Get a configuration value
   *
   */
  public function getConfig($configName) {
    return $this->configuration[$configName];
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Get a configuration value
   *
   */
  public function setConfig($configName, $configValue) {
    $this->configuration[$configName] = $configValue;
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


  // --------------------------------------------------------------------------------------------------------
  /**
  * Adds a error value to the recorded error values
  *
  */
  public function recordErrorValue($errorValue) {
    $this->recordedErrorValues[] = $errorValue;
  }

}
