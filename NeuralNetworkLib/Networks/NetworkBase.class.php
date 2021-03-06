<?php
namespace NeuralNetworkLib\Networks;

use \NeuralNetworkLib\Components as Components;
use \NeuralNetworkLib\Components\Layer\HiddenLayer as HiddenLayer;
use \NeuralNetworkLib\Components\Layer\InputLayer as InputLayer;
use \NeuralNetworkLib\Components\Layer\OutputLayer as OutputLayer;
use \NeuralNetworkLib\ActivationFunctions as ActivationFunctions;
use \NeuralNetworkLib\TrainingAlgorithms as TrainingAlgorithms;
use \NeuralNetworkLib\Helpers as Helpers;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Neural network base class
 *
 */
class NetworkBase {

  /**
   * Network configuration
   */
  public $configuration = [];

  /**
   * Input layer
   */
  public $inputLayer;

  /**
   * Hidden layer
   */
  public $hiddenLayers = [];

  /**
   * Output layer
   */
  public $outputLayer;

  /**
   * Training data
   */
  public $trainingData = [];

  /**
   * Control data
   */
  public $controlData = [];

  /**
   * ALL layers in the network
   */
  public $allLayers = [];

  /**
   * ALL neurons in the network
   */
  public $allNeurons = [];

  /**
   * ALL synapses in the network
   */
  public $allSynapses = [];

  /**
   * ALL weights in the network
   */
  public $allWeights = [];


  // --------------------------------------------------------------------------------------------------------
  /**
   * Network base construtor
   *
   */
  public function __construct($config = []) {
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
   * Adds a layer to the network's layer store
   */
  public function addLayer($layer) {
    $this->allLayers[] = $layer;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Adds a neuron to the network's neuron store
   */
  public function addNeuron($neuron) {
    $this->allNeurons[] = $neuron;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Adds a synapse to the network's synapse store
   */
  public function addSynapse($synapse) {
    $this->allSynapses[] = $synapse;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Adds a weight to the network's weight store
   */
  public function addWeight($weight) {
    $this->allWeights[] = $weight;
  }



  // --------------------------------------------------------------------------------------------------------
  /**
   * Save the network state to file
   *
   */
  public function exportToFile($filePath) {
    if(file_put_contents($filePath, $this->exportNetworkData())) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Load a network state to file
   *
   */
  public static function importFromFile($filePath) {
    $serializedData = file_get_contents($filePath);
    if($serializedData) {
      $unserializedNetwork = unserialize($serializedData);
      return $unserializedNetwork;
    } else {
      return FALSE;
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Returns the network state as a string
   *
   */
  public function exportToString() {
    return $this->exportNetworkData();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Load a network state from string
   *
   */
  public static function importFromString($serializedData) {
    $unserializedNetwork = unserialize($serializedData);
    return $unserializedNetwork;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Exports the entire network state to a string
   *
   */
  private function exportNetworkData() {
    $serializedData = serialize($this);
    return $serializedData;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Exports the entire network (or everything that is of interest) to json for use at client-side
   *
   */
  public function jsonExport() {
    $jsonData = Helpers\Misc::object2array($this);
    return json_encode($jsonData);
  }

}
