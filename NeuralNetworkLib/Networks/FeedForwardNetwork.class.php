<?php
namespace NeuralNetworkLib\Networks;

use \NeuralNetworkLib\Components as Components;
use \NeuralNetworkLib\Components\Layer\Layer as Layer;
use \NeuralNetworkLib\ActivationFunctions as ActivationFunctions;
use \NeuralNetworkLib\TrainingAlgorithms as TrainingAlgorithms;
use \NeuralNetworkLib\Helpers as Helpers;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Feed forward network constructor
 *
 */
class FeedForwardNetwork extends NetworkBase {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Network construtor
   *
   * Initializes the layers
   */
  public function __construct($inputLayerNodeCount, $hiddenLayerNodeCounts, $outputLayerNodeCount) {
    // Create the neurons
    $this->buildInputLayer($inputLayerNodeCount);
    $this->buildHiddenLayer($hiddenLayerNodeCounts);
    $this->buildOutputLayer($outputLayerNodeCount);

    // Connect them
    $this->connectNeurons();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Builds the input layer
   *
   */
  private function buildInputLayer($neuronCount) {
    // Create the input layer
    $previousLayer  = NULL; // There is no previous layer
    $nextLayer      = NULL; // We dont know this yet
    $layerName      = 'Input layer';
    $layerPosition  = 1;
    $inputLayer     = $this->createLayer($previousLayer, $nextLayer, $layerName, $layerPosition, $neuronCount);

    // Set the newly created layer to the input layer
    $this->inputLayer = $inputLayer;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Builds the hidden layer
   *
   */
  private function buildHiddenLayer($layerStructure) {
    // Easier to only work with arrays
    if(!is_array($layerStructure)) {
      $layerStructure = [$layerStructure];
    }

    // For each of the layer configurations
    $firstHiddenLayer = TRUE;
    for($i=0;$i<count($layerStructure);$i++) {

      // Create hidden layer
      // How many neurons in this hidden layer?
      $layerNeuronCount = $layerStructure[$i];
      // If this is the first hidden layer, we set the previous layer to the input layer
      if($firstHiddenLayer) {
        $previousLayer  = $this->inputLayer;
      } else {
        // Else we set it to the previously created hidden layer
        $previousLayer = $this->hiddenLayers[$i-1];
      }
      $nextLayer      = NULL; // We dont know this yet
      $layerName      = 'Hidden layer '.$i;
      $layerPosition  = $i+2;
      $aHiddenLayer   = $this->createLayer($previousLayer, $nextLayer, $layerName, $layerPosition, $layerNeuronCount);

      // Create and connect bias neuron
      $aHiddenLayer->createBiasNeuron();
      $aHiddenLayer->connectBiasNeuron();

      // Update the previous hidden layer's next layer
      if(!$firstHiddenLayer) {
        $this->hiddenLayers[$i-1]->nextLayer = $aHiddenLayer;
      } else {
        $this->inputLayer->nextLayer = $aHiddenLayer;
      }

      // Add new hidden layer to hidden layers list
      $this->hiddenLayers[] = $aHiddenLayer;

      // The next layer is no longer the first hidden layer
      $firstHiddenLayer = FALSE;
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Builds the input layer
   *
   */
  private function buildOutputLayer($neuronCount) {
    // Create the output layer
    $previousLayer  = $this->hiddenLayers[count($this->hiddenLayers)-1]; // The last hidden layer
    $nextLayer      = NULL; // There is no next layer after the output layer
    $layerName      = 'Output layer';
    $layerPosition  = count($this->hiddenLayers)+2;
    $outputLayer     = $this->createLayer($previousLayer, $nextLayer, $layerName, $layerPosition, $neuronCount);

    // Create and connect bias neuron
    $outputLayer->createBiasNeuron();
    $outputLayer->connectBiasNeuron();

    // Set the newly created layer to the output layer
    $this->outputLayer = $outputLayer;

    // Also update the last hidden layer next layer
    $this->hiddenLayers[count($this->hiddenLayers)-1]->nextLayer = $outputLayer;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Creates connections for all the layers and neurons
   *
   */
  private function connectNeurons() {
    // Add input synapses to the input layer
    foreach($this->inputLayer->neurons as $inputLayerNeuron) {
      // Ignore bioas neuron
      if(isset($inputLayerNeuron->isBiasNeuron)) {
        continue;
      }

      // Create and connect a synapse to the neuron
      $synapse = $inputLayerNeuron->linkNeuronTo($inputLayerNeuron, NULL, FALSE);
    }

    // Add output synapses to the output layer
    foreach($this->outputLayer->neurons as $outputLayerNeuron) {
      // Ignore if bias neuron
      if(isset($outputLayerNeuron->isBiasNeuron)) {
        continue;
      }
      // Create and connect a synapse to the neuron
      $synapse = $outputLayerNeuron->linkNeuronTo(NULL, $outputLayerNeuron);
    }

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
      $output[] = $neuron->outputSynapses[0]->getValue();
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

      // TODO WHAT ABOUT BIAS NEURON? COULD BE INDEX 0?
      $neuron->inputSynapses[0]->setValue($data[$index]);
    }
  }

}
