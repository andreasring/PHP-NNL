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
 * Feed forward network constructor
 *
 */
class FeedForwardNetwork extends NetworkBase {

  /**
   * Defines default configuration for this network
   */
  public $configuration = [
    'structure' => [
      'input'   => 2,
      'hidden'  => [3],
      'output'  => 1
    ],
    'biasNeuron' => TRUE
  ];


  // --------------------------------------------------------------------------------------------------------
  /**
   * Network construtor
   *
   * Initializes the layers
   */
  public function __construct($config) {
    // Do whatever the parent want to do
    parent::__construct($config);

    // Create the neurons
    $this->buildInputLayer();
    $this->buildHiddenLayer();
    $this->buildOutputLayer();

    // Connect them
    $this->connectNeurons();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Builds the input layer
   *
   */
  private function buildInputLayer() {
    // How many neurons do we want in the input layer?
    $neuronCount = $this->configuration['structure']['input'];

    // Create the input layer
    $network        = $this;
    $previousLayer  = NULL; // There is no previous layer
    $nextLayer      = NULL; // We dont know this yet
    $layerName      = 'Input layer';
    $layerPosition  = 1;
    $inputLayer     = new InputLayer($network, $previousLayer, $nextLayer, $layerName, $layerPosition, $neuronCount);

    // Add the layer to the network layer store
    $this->addLayer($inputLayer);

    // Set the newly created layer to the input layer
    $this->inputLayer = $inputLayer;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Builds the hidden layer
   *
   */
  private function buildHiddenLayer() {
    // What should the hidden layers look like?
    $hiddenLayerStructure = $this->configuration['structure']['hidden'];

    // Easier to only work with arrays
    if(!is_array($hiddenLayerStructure)) {
      $hiddenLayerStructure = [$hiddenLayerStructure];
    }

    // For each of the layer configurations
    $firstHiddenLayer = TRUE;
    for($i=0;$i<count($hiddenLayerStructure);$i++) {

      // Create hidden layer
      // How many neurons in this hidden layer?
      $hiddenLayerNeuronCount = $hiddenLayerStructure[$i];
      // If this is the first hidden layer, we set the previous layer to the input layer
      if($firstHiddenLayer) {
        $previousLayer  = $this->inputLayer;
      } else {
        // Else we set it to the previously created hidden layer
        $previousLayer = $this->hiddenLayers[$i-1];
      }
      $network        = $this;
      $nextLayer      = NULL; // We dont know this yet
      $layerName      = 'Hidden layer '.$i;
      $layerPosition  = $i+2;
      $aHiddenLayer   = new HiddenLayer($network, $previousLayer, $nextLayer, $layerName, $layerPosition, $hiddenLayerNeuronCount);

      // Add the layer to the network layer store
      $this->addLayer($aHiddenLayer);

      // Add new hidden layer to hidden layers list
      $this->hiddenLayers[] = $aHiddenLayer;

      // Create and connect bias neuron
      if($this->configuration['biasNeuron']) {
        $aHiddenLayer->createBiasNeuron();
        $aHiddenLayer->connectBiasNeuron();
      }

      // Update the previous hidden layer's next layer
      if(!$firstHiddenLayer) {
        $this->hiddenLayers[$i-1]->nextLayer = $aHiddenLayer;
      } else {
        $this->inputLayer->nextLayer = $aHiddenLayer;
      }

      // The next layer is no longer the first hidden layer
      $firstHiddenLayer = FALSE;
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Builds the input layer
   *
   */
  private function buildOutputLayer() {
    // How many neurons do we want in the output layer?
    $neuronCount = $this->configuration['structure']['output'];

    // Create the output layer
    $network        = $this;
    $previousLayer  = $this->hiddenLayers[count($this->hiddenLayers)-1]; // The last hidden layer
    $nextLayer      = NULL; // There is no next layer after the output layer
    $layerName      = 'Output layer';
    $layerPosition  = count($this->hiddenLayers)+2;
    $outputLayer    = new OutputLayer($network, $previousLayer, $nextLayer, $layerName, $layerPosition, $neuronCount);

    // Add the layer to the network layer store
    $this->addLayer($outputLayer);

    // Set the newly created layer to the output layer
    $this->outputLayer = $outputLayer;

    // Create and connect bias neuron
    if($this->configuration['biasNeuron']) {
      $outputLayer->createBiasNeuron();
      $outputLayer->connectBiasNeuron();
    }

    // Also update the last hidden layer next layer
    $this->hiddenLayers[count($this->hiddenLayers)-1]->nextLayer = $outputLayer;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Creates connections for all the layers and neurons
   *
   */
  private function connectNeurons() {
    // Connect the input layer to the first hidden layer
    $this->connectLayers($this->inputLayer, $this->hiddenLayers[0]);

    // Connect all the hidden layers
    for($i=0;$i<count($this->hiddenLayers)-1;$i++) {
      $this->connectLayers($this->hiddenLayers[$i], $this->hiddenLayers[$i+1]);
    }

    // Connect the last hidden layer to the output layer
    $this->connectLayers($this->hiddenLayers[count($this->hiddenLayers)-1], $this->outputLayer);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Connects all neurons in layer1 to all neurons in layer2 (except the bias neuron)
   *
   */
  public function connectLayers($layer1, $layer2) {
    foreach($layer1->neurons as $layer1Neuron) {
      // Ignore if bias neuron
      if(isset($layer1Neuron->isBiasNeuron)) {
        continue;
      }

      foreach($layer2->neurons as $layer2Neuron) {
        // Ignore if bias neuron
        if(isset($layer2Neuron->isBiasNeuron)) {
          continue;
        }

        // Create and connect a synapse to the neuron
        $synapse = $layer1Neuron->linkNeuronTo($layer2Neuron);
      }
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Calculate the network output for a given input
   *
   */
  public function calculate($inputData) {
    $this->setInputData($inputData);

    // Calculate the input layer
    foreach($this->inputLayer->neurons as $neuron) {
      // Ignore bias neuron
      if(isset($neuron->isBiasNeuron)) {
        continue;
      }
      $neuron->calculate();
    }

    // Calculate the hidden layers
    foreach($this->hiddenLayers as $hiddenLayer) {
      foreach($hiddenLayer->neurons as $neuron) {
        // Ignore bias neuron
        if(isset($neuron->isBiasNeuron)) {
          continue;
        }
        $neuron->calculate();
      }
    }

    // Calculate the output layer and the network output
    $output = [];
    foreach($this->outputLayer->neurons as $neuron) {
      // Ignore bias neuron
      if(isset($neuron->isBiasNeuron)) {
        continue;
      }
      $neuron->calculate();

      // Since we are using OutputNeuron's in the output layer we can use getOutput()
      $output[] = $neuron->getOutput();
    }

    return $output;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Set the input data for the network
   *
   */
  public function setInputData($data) {
    // Set the value for neuron's input synapse in the inputLayer
    foreach($this->inputLayer->neurons as $index => $neuron) {
      // Ignore bias neuron
      if(isset($neuron->isBiasNeuron)) {
        $index--;
        continue;
      }

      // Since we are using InputNeuron's for the input layey we can use setInputData()
      $neuron->setInputData($data[$index]);
    }
  }

}
