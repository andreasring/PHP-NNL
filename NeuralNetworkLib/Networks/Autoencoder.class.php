<?php
namespace NeuralNetworkLib\Networks;

use \NeuralNetworkLib\Components as Components;
use \NeuralNetworkLib\ActivationFunctions as ActivationFunctions;
use \NeuralNetworkLib\TrainingAlgorithms as TrainingAlgorithms;
use \NeuralNetworkLib\Helpers as Helpers;

// --------------------------------------------------------------------------------------------------------------------------
/**
 *
 *
 */
class Autoencoder {

  /**
   * Input layer
   */
  public $inputLayer;

  /**
   * Hidden layer
   */
  public $hiddenLayer = [];

  /**
   * Output layer
   */
  public $outputLayer;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Network construtor
   *
   * Initializes the network structure
   */
  public function __construct($inputLayerNodeCount, $hiddenLayerNodeCounts, $outputLayerNodeCount) {

  }


}
