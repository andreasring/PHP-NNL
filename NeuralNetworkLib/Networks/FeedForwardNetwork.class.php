<?php
namespace NeuralNetworkLib\Networks;

use \NeuralNetworkLib\Components as Components;
use \NeuralNetworkLib\ActivationFunctions as ActivationFunctions;
use \NeuralNetworkLib\TrainingAlgorithms as TrainingAlgorithms;
use \NeuralNetworkLib\Helpers as Helpers;

/**
 * Feed forward network constructor
 *
 */
class FeedForwardNetwork {

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

  /**
   * Training data
   */
  public $trainingData = [];

  /**
   * Control data
   */
  public $controlData = [];

  /**
   * Central store
   *  - Layers
   *  - Neurons
   *  - Synapses
   *  - Weights
   */


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
    $network        = $this;
    $layerName      = 'Input layer';
    $layerPosition  = 1;
    $layer          = new Components\Layer($network, $layerName, $layerPosition, $neuronCount);
    $layer->createBiasNeuron();
    $layer->connectBiasNeuron();
    $this->inputLayer = $layer;
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
    for($i=0;$i<count($layerStructure);$i++) {
      $layerNeuronCount = $layerStructure[$i];

      // Create layer
      $network        = $this;
      $layerName      = 'Hidden layer '.$i;
      $layerPosition  = $i+2;
      $aHiddenLayer   = new Components\Layer($network, $layerName, $layerPosition, $layerNeuronCount);
      $aHiddenLayer->createBiasNeuron();
      $aHiddenLayer->connectBiasNeuron();

      // Add new hidden layer to hidden layer list
      $this->hiddenLayer[] = $aHiddenLayer;
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Builds the input layer
   *
   */
  private function buildOutputLayer($neuronCount) {
    // Create the output layer
    $network        = $this;
    $layerName      = 'Output layer';
    $layerPosition  = count($this->hiddenLayer)+2;
    $outputLayer    = new Components\Layer($network, $layerName, $layerPosition, $neuronCount);
    $outputLayer->createBiasNeuron();
    $outputLayer->connectBiasNeuron();
    $this->outputLayer = $outputLayer;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Creates connections for all the layers and neurons
   *
   */
  private function connectNeurons() {
    // Add input synapses to the input layer
    foreach($this->inputLayer->neurons as $neuron) {
      // Ignore bioas neuron; The bios neuron is connected below
      if($neuron->isBiasNeuron) {
        continue;
      }

      $inputNeuron      = NULL;
      $outputNeuron     = $neuron;
      $value            = 0.0;
      $weight           = 1.0;
      $synapse          = new Components\Synapse($inputNeuron, $outputNeuron, $value, $weight);

      $neuron->addInputSynapse($synapse);
    }


    // Add output synapses to the output layer
    foreach($this->outputLayer->neurons as $neuron) {
      // Ignore if bias neuron
      if($neuron->isBiasNeuron) {
        continue;
      }

      $inputNeuron      = $neuron;
      $outputNeuron     = NULL;
      $value            = 0.0;
      $weight           = $this->generateRandomWeight();
      $synapse          = new Components\Synapse($inputNeuron, $outputNeuron, $value, $weight);

      $neuron->addOutputSynapse($synapse);
    }


    // Connect the input layer to the first hidden layer
    $this->connectLayers($this->inputLayer, $this->hiddenLayer[0]);

    // Connect all the hidden layers
    for($i=0;$i<count($this->hiddenLayer)-1;$i++) {
      $this->connectLayers($this->hiddenLayer[$i], $this->hiddenLayer[$i+1]);
    }

    // Connect the last hidden layer to the output layer
    $this->connectLayers($this->hiddenLayer[count($this->hiddenLayer)-1], $this->outputLayer);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Connects all neurons in layer1 to all neurons in layer2 (except the bias neuron)
   *
   */
  public function connectLayers($layer1, $layer2) {
    foreach($layer1->neurons as $layer1Neuron) {
      // Ignore if bias neuron
      if($layer1Neuron->isBiasNeuron) {
        continue;
      }

      foreach($layer2->neurons as $layer2Neuron) {
        // Ignore if bias neuron
        if($layer2Neuron->isBiasNeuron) {
          continue;
        }

        $inputNeuron      = $layer1Neuron;
        $outputNeuron     = $layer2Neuron;
        $value            = 0.0;
        $weight           = $this->generateRandomWeight();
        $synapse          = new Components\Synapse($layer1Neuron, $layer2Neuron, $value, $weight);

        $layer1Neuron->addOutputSynapse($synapse);
        $layer2Neuron->addInputSynapse($synapse);
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
      if($neuron->isBiasNeuron) {
        continue;
      }
      $neuron->calculate();
    }

    // Calculate the hidden layers
    foreach($this->hiddenLayer as $hiddenLayer) {
      foreach($hiddenLayer->neurons as $neuron) {
        if($neuron->isBiasNeuron) {
          continue;
        }
        $neuron->calculate();
      }
    }

    // Calculate the output layer and the network output
    $output = [];
    foreach($this->outputLayer->neurons as $neuron) {
      if($neuron->isBiasNeuron) {
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
      if($neuron->isBiasNeuron) {
        $index--;
        continue;
      }
      // 1 because of bias neuron is 0 (But what do we do if we have no bias neuron?)
      $neuron->inputSynapses[1]->setValue($data[$index]);
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Train the network on the avalible data
   *
   */
  public function train() {

    // For now we will just do a random weight change algorithm
    $this->randomWeightChange();

    // Loop through the training data
      // Set network input data to current training data
      // Run calculation
      // Store outputted calculation and compare to expected value or something
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Performs gradient decent on the network
   *
   */
  public function gradientDescent() {

  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Performs gradient decent on the network
   *
   */
  public function randomWeightChange() {
    $bestOutcomes = [];
    $bestOutcomesNum = 5;

    for ($i=0; $i < 10000; $i++) {

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


  // --------------------------------------------------------------------------------------------------------
  /**
   * Generates a random weight
   *
   */
  public function generateRandomWeight() {
    return rand(0, 1000)/1000;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Add training data
   *
   */
  public function addTrainingData($data, $result) {
    $this->trainingData[] = [$data, $result];
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Add control data
   *
   */
  public function addControlData($data, $result) {
    $this->controlData[] = [$data, $result];
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Save the network state to file
   *
   */
  public function save($filePath) {
    if(file_put_contents($filePath, $this->export())) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Save the network state to file
   *
   */
  public function export() {
    $serializedData = serialize($this);
    return $serializedData;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Load a network state from file
   *
   */
  public static function load($filePath) {
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
   * Returns all the neurons in the network
   *
   */
  public function allNeurons() {
    $neurons = [];
    foreach($this->inputLayer->neurons as $neuron) {
      $neurons[] = $neuron;
    }
    foreach($this->hiddenLayer as $hiddenLayer) {
      foreach($hiddenLayer->neurons as $neuron) {
        $neurons[] = $neuron;
      }
    }
    foreach($this->outputLayer->neurons as $neuron) {
      $neurons[] = $neuron;
    }
    return $neurons;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Returns all the synapses in the network
   *
   */
  public function allSynapses() {
    $neurons = $this->allNeurons();
    $synapses = [];
    foreach($neurons as $neuron) {
      foreach($neuron->inputSynapses as $synapse) {
        if(!in_array($synapse, $synapses, TRUE)) {
          $synapses[] = $synapse;
        }
      }
      foreach($neuron->outputSynapses as $synapse) {
        if(!in_array($synapse, $synapses, TRUE)) {
          $synapses[] = $synapse;
        }
      }
    }
    return $synapses;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Exports data as json to visualize the network using sigma.js
   *
   */
  public function exportForSigma() {
    $nodes = $this->allNeurons();
    $edges = $this->allSynapses();

    $jsonData = [
      'nodes' => [],
      'edges' => []
    ];

    foreach($nodes as $node) {
      // Centering all the layers
      $node->yPos -= ($node->layer->neuronCount/2);

      $jsonData['nodes'][] = [
        'id'      => $node->name,
        'label'   => $node->name,
        'x'       => $node->xPos,
        'y'       => $node->yPos,
        'size'    => 10,
        'color'    => '#666'
      ];
    }

    $i = 0;
    foreach($edges as $edge) {
      if(!isset($edge->inputNeuron) || !isset($edge->outputNeuron)) {
        continue;
      }
      $jsonData['edges'][] = [
        'id'      => 'e_'.$i++,
        'source'   => $edge->inputNeuron->name,
        'target'   => $edge->outputNeuron->name,
        'size'     => 10,
        'color'    => '#ccc'
      ];
    }

    return json_encode($jsonData, 0, 10);
  }

}
