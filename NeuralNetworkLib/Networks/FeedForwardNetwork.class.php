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
   * Central store ???? Maybe TODO
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
    $previousLayer  = NULL; // There is no previous layer
    $nextLayer      = NULL; // We dont know this yet
    $layerName      = 'Input layer';
    $layerPosition  = 1;
    $layer          = new Components\Layer($network, $previousLayer, $nextLayer, $layerName, $layerPosition, $neuronCount);
    //$layer->createBiasNeuron();
    //$layer->connectBiasNeuron();
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
    $firstHiddenLayer = TRUE;
    for($i=0;$i<count($layerStructure);$i++) {
      $layerNeuronCount = $layerStructure[$i];

      // Create layer
      $network        = $this;
      if($firstHiddenLayer) {
        $previousLayer  = $this->inputLayer;
      } else {
        $previousLayer = $this->hiddenLayer[$i-1];
      }
      $nextLayer      = NULL; // We dont know this yet
      $layerName      = 'Hidden layer '.$i;
      $layerPosition  = $i+2;
      $aHiddenLayer   = new Components\Layer($network, $previousLayer, $nextLayer, $layerName, $layerPosition, $layerNeuronCount);
      $aHiddenLayer->createBiasNeuron();
      $aHiddenLayer->connectBiasNeuron();

      // Update the previous hidden layer's next layer
      if(!$firstHiddenLayer) {
        $this->hiddenLayer[$i-1]->nextLayer = $aHiddenLayer;
      }

      // Add new hidden layer to hidden layer list
      $this->hiddenLayer[] = $aHiddenLayer;

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
    $network        = $this;
    $previousLayer  = $this->hiddenLayer[count($this->hiddenLayer)-1]; // The last hidden layer
    $nextLayer      = NULL; // There is no next layer after the output layer
    $layerName      = 'Output layer';
    $layerPosition  = count($this->hiddenLayer)+2;
    $outputLayer    = new Components\Layer($network, $previousLayer, $nextLayer, $layerName, $layerPosition, $neuronCount);
    $outputLayer->createBiasNeuron();
    $outputLayer->connectBiasNeuron();
    $this->outputLayer = $outputLayer;

    // Also update the last hidden layer next layer
    $this->hiddenLayer[count($this->hiddenLayer)-1]->nextLayer = $outputLayer;
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
      $neuron->inputSynapses[0]->setValue($data[$index]);
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Train the network on the avalible data
   *
   */
  public function train() {

    $gradientDecentTrainer = new TrainingAlgorithms\GradientDecent($this);
    //$randomTrainer = new TrainingAlgorithms\Random($this);

    // Loop through the training data
      // Set network input data to current training data
      // Run calculation
      // Store outputted calculation and compare to expected value or something
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Generates a random weight
   *
   */
  public function generateRandomWeight() {
    //return mt_rand(-100000000, 100000000)/100000000;
    //return mt_rand(-10000000000000, 10000000000000)/10000000000000;
    return floatval(mt_rand() / mt_getrandmax());

    //return rand(-1000, 1000)/1000;
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
    // Store and remove the stuff we dont want exported
    $trainingData = $this->trainingData;
    $controlData = $this->controlData;
    $this->trainingData = [];
    $this->controlData = [];

    //$serializedData = serialize($this);
    $serializedData = json_encode(serialize($this));

    // Restore the stuff we removed before export
    $this->trainingData = $trainingData;
    $this->controlData = $controlData;

    // Return the serialized data
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
      //$unserializedNetwork = unserialize($serializedData);
      $unserializedNetwork = unserialize(json_decode($serializedData));

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

    return json_encode($jsonData, 0, 100);
  }


// --------------------------------------------------------------------------------------------------------
/**
 * Converts a object into an array recursively
 *
 */
public function object2array($object, $recur = 0) {
  if($recur > 7) {
    return NULL;
  }
  $recur++;
  $array = [];
  foreach($object as $key => $value) {
    if(is_array($value)) {
      $value = $this->object2array($value, $recur);
    } elseif(is_object($value)) {
      $value = $this->object2array($value, $recur);
    } elseif(is_resource($value)) {
      $value = NULL;
    }

    $array[$key] = $value;
  }
  return $array;
}


  // --------------------------------------------------------------------------------------------------------
  /**
   * Exports the entire network (or everything that is of interest) to json for use at client-side
   *
   */
  public function jsonExport() {
    $jsonData = [
      'network' => [
        'inputLayer' => [],
        'hiddenLayer' => [],
        'outputLayer' => []
      ]
    ];

    $jsonData = $this->object2array($this);

    return json_encode($jsonData);


    // OLD

    // Input layer
    $jsonData['inputLayer']['ID']           = $this->inputLayer->ID;
    $jsonData['inputLayer']['name']         = $this->inputLayer->name;
    $jsonData['inputLayer']['position']     = $this->inputLayer->position;
    $jsonData['inputLayer']['neuronCount']  = $this->inputLayer->neuronCount;
    $jsonData['inputLayer']['neurons']      = [];
    foreach($this->inputLayer->neurons as $neuron) {
      $jsonNeuron = [];
      $jsonNeuron['ID']   = $neuron->ID;
      $jsonNeuron['name'] = $neuron->name;
      $jsonNeuron['xPos'] = $neuron->xPos;
      $jsonNeuron['yPos'] = $neuron->yPos;
      $jsonNeuron['isBiasNeuron']         = $neuron->isBiasNeuron;
      $jsonNeuron['activationFunction']   = $neuron->activationFunction;
      $jsonNeuron['latestOutputValue']    = $neuron->latestOutputValue;
      $jsonNeuron['previousOutputValue']  = $neuron->previousOutputValue;
      $jsonNeuron['inputSynapses']        = [];
      $jsonNeuron['outputSynapses']       = [];

      foreach($neuron->inputSynapses as $synapse) {
        $jsonSynapse = [];
        $jsonSynapse['ID']    = $synapse->ID;
        $jsonSynapse['value'] = $synapse->getValue();
        $jsonSynapse['valueChangeDisabled'] = $synapse->valueChangeDisabled;
        $jsonSynapse['weight'] = $synapse->getWeight();
        $jsonSynapse['weightChangeDisabled'] = $synapse->weightChangeDisabled;
        if($synapse->inputNeuron) {
          $jsonSynapse['inputNeuron'] = [];
          $jsonSynapse['inputNeuron']['ID']           = $synapse->inputNeuron->ID;
          $jsonSynapse['inputNeuron']['name']         = $synapse->inputNeuron->name;
          $jsonSynapse['inputNeuron']['xPos']         = $synapse->inputNeuron->xPos;
          $jsonSynapse['inputNeuron']['yPos']         = $synapse->inputNeuron->yPos;
          $jsonSynapse['inputNeuron']['isBiasNeuron'] = $synapse->inputNeuron->isBiasNeuron;
          $jsonSynapse['inputNeuron']['activationFunction']   = $synapse->inputNeuron->activationFunction;
          $jsonSynapse['inputNeuron']['latestOutputValue']    = $synapse->inputNeuron->latestOutputValue;
          $jsonSynapse['inputNeuron']['previousOutputValue']  = $synapse->inputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['inputNeuron'] = NULL;
        }
        if($synapse->outputNeuron) {
          $jsonSynapse['outputNeuron'] = [];
          $jsonSynapse['outputNeuron']['ID']           = $synapse->outputNeuron->ID;
          $jsonSynapse['outputNeuron']['name']         = $synapse->outputNeuron->name;
          $jsonSynapse['outputNeuron']['xPos']         = $synapse->outputNeuron->xPos;
          $jsonSynapse['outputNeuron']['yPos']         = $synapse->outputNeuron->yPos;
          $jsonSynapse['outputNeuron']['isBiasNeuron'] = $synapse->outputNeuron->isBiasNeuron;
          $jsonSynapse['outputNeuron']['activationFunction']   = $synapse->outputNeuron->activationFunction;
          $jsonSynapse['outputNeuron']['latestOutputValue']    = $synapse->outputNeuron->latestOutputValue;
          $jsonSynapse['outputNeuron']['previousOutputValue']  = $synapse->outputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['outputNeuron'] = NULL;
        }

        $jsonNeuron['inputSynapses'][] = $jsonSynapse;
      }

      foreach($neuron->outputSynapses as $synapse) {
        $jsonSynapse = [];
        $jsonSynapse['ID']    = $synapse->ID;
        $jsonSynapse['value'] = $synapse->getValue();
        $jsonSynapse['valueChangeDisabled'] = $synapse->valueChangeDisabled;
        $jsonSynapse['weight'] = $synapse->getWeight();
        $jsonSynapse['weightChangeDisabled'] = $synapse->weightChangeDisabled;

        if($synapse->inputNeuron) {
          $jsonSynapse['inputNeuron'] = [];
          $jsonSynapse['inputNeuron']['ID']           = $synapse->inputNeuron->ID;
          $jsonSynapse['inputNeuron']['name']         = $synapse->inputNeuron->name;
          $jsonSynapse['inputNeuron']['xPos']         = $synapse->inputNeuron->xPos;
          $jsonSynapse['inputNeuron']['yPos']         = $synapse->inputNeuron->yPos;
          $jsonSynapse['inputNeuron']['isBiasNeuron'] = $synapse->inputNeuron->isBiasNeuron;
          $jsonSynapse['inputNeuron']['activationFunction']   = $synapse->inputNeuron->activationFunction;
          $jsonSynapse['inputNeuron']['latestOutputValue']    = $synapse->inputNeuron->latestOutputValue;
          $jsonSynapse['inputNeuron']['previousOutputValue']  = $synapse->inputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['inputNeuron'] = NULL;
        }
        if($synapse->outputNeuron) {
          $jsonSynapse['outputNeuron'] = [];
          $jsonSynapse['outputNeuron']['ID']           = $synapse->outputNeuron->ID;
          $jsonSynapse['outputNeuron']['name']         = $synapse->outputNeuron->name;
          $jsonSynapse['outputNeuron']['xPos']         = $synapse->outputNeuron->xPos;
          $jsonSynapse['outputNeuron']['yPos']         = $synapse->outputNeuron->yPos;
          $jsonSynapse['outputNeuron']['isBiasNeuron'] = $synapse->outputNeuron->isBiasNeuron;
          $jsonSynapse['outputNeuron']['activationFunction']   = $synapse->outputNeuron->activationFunction;
          $jsonSynapse['outputNeuron']['latestOutputValue']    = $synapse->outputNeuron->latestOutputValue;
          $jsonSynapse['outputNeuron']['previousOutputValue']  = $synapse->outputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['outputNeuron'] = NULL;
        }

        $jsonNeuron['outputSynapses'][] = $jsonSynapse;
      }

      $jsonData['inputLayer']['neurons'][] = $jsonNeuron;
    }


    // Hidden layer
    foreach($this->hiddenLayer as $hiddenLayer) {
      $jsonHiddenLayer = [];
      $jsonHiddenLayer['ID']          = $hiddenLayer->ID;
      $jsonHiddenLayer['name']         = $hiddenLayer->name;
      $jsonHiddenLayer['position']     = $hiddenLayer->position;
      $jsonHiddenLayer['neuronCount']  = $hiddenLayer->neuronCount;
      $jsonHiddenLayer['neurons']      = [];
      foreach($hiddenLayer->neurons as $neuron) {
        $jsonNeuron = [];
        $jsonNeuron['ID']   = $neuron->ID;
        $jsonNeuron['name'] = $neuron->name;
        $jsonNeuron['xPos'] = $neuron->xPos;
        $jsonNeuron['yPos'] = $neuron->yPos;
        $jsonNeuron['isBiasNeuron']         = $neuron->isBiasNeuron;
        $jsonNeuron['activationFunction']   = $neuron->activationFunction;
        $jsonNeuron['latestOutputValue']    = $neuron->latestOutputValue;
        $jsonNeuron['previousOutputValue']  = $neuron->previousOutputValue;
        $jsonNeuron['inputSynapses']        = [];
        $jsonNeuron['outputSynapses']       = [];

        foreach($neuron->inputSynapses as $synapse) {
          $jsonSynapse = [];
          $jsonSynapse['ID']    = $synapse->ID;
          $jsonSynapse['value'] = $synapse->getValue();
          $jsonSynapse['valueChangeDisabled'] = $synapse->valueChangeDisabled;
          $jsonSynapse['weight'] = $synapse->getWeight();
          $jsonSynapse['weightChangeDisabled'] = $synapse->weightChangeDisabled;

          if($synapse->inputNeuron) {
            $jsonSynapse['inputNeuron'] = [];
            $jsonSynapse['inputNeuron']['ID']           = $synapse->inputNeuron->ID;
            $jsonSynapse['inputNeuron']['name']         = $synapse->inputNeuron->name;
            $jsonSynapse['inputNeuron']['xPos']         = $synapse->inputNeuron->xPos;
            $jsonSynapse['inputNeuron']['yPos']         = $synapse->inputNeuron->yPos;
            $jsonSynapse['inputNeuron']['isBiasNeuron'] = $synapse->inputNeuron->isBiasNeuron;
            $jsonSynapse['inputNeuron']['activationFunction']   = $synapse->inputNeuron->activationFunction;
            $jsonSynapse['inputNeuron']['latestOutputValue']    = $synapse->inputNeuron->latestOutputValue;
            $jsonSynapse['inputNeuron']['previousOutputValue']  = $synapse->inputNeuron->previousOutputValue;
          } else {
            $jsonSynapse['inputNeuron'] = NULL;
          }
          if($synapse->outputNeuron) {
            $jsonSynapse['outputNeuron'] = [];
            $jsonSynapse['outputNeuron']['ID']           = $synapse->outputNeuron->ID;
            $jsonSynapse['outputNeuron']['name']         = $synapse->outputNeuron->name;
            $jsonSynapse['outputNeuron']['xPos']         = $synapse->outputNeuron->xPos;
            $jsonSynapse['outputNeuron']['yPos']         = $synapse->outputNeuron->yPos;
            $jsonSynapse['outputNeuron']['isBiasNeuron'] = $synapse->outputNeuron->isBiasNeuron;
            $jsonSynapse['outputNeuron']['activationFunction']   = $synapse->outputNeuron->activationFunction;
            $jsonSynapse['outputNeuron']['latestOutputValue']    = $synapse->outputNeuron->latestOutputValue;
            $jsonSynapse['outputNeuron']['previousOutputValue']  = $synapse->outputNeuron->previousOutputValue;
          } else {
            $jsonSynapse['outputNeuron'] = NULL;
          }

          $jsonNeuron['inputSynapses'][] = $jsonSynapse;
        }

        foreach($neuron->outputSynapses as $synapse) {
          $jsonSynapse = [];
          $jsonSynapse['ID']    = $synapse->ID;
          $jsonSynapse['value'] = $synapse->getValue();
          $jsonSynapse['valueChangeDisabled'] = $synapse->valueChangeDisabled;
          $jsonSynapse['weight'] = $synapse->getWeight();
          $jsonSynapse['weightChangeDisabled'] = $synapse->weightChangeDisabled;

          if($synapse->inputNeuron) {
            $jsonSynapse['inputNeuron'] = [];
            $jsonSynapse['inputNeuron']['ID']           = $synapse->inputNeuron->ID;
            $jsonSynapse['inputNeuron']['name']         = $synapse->inputNeuron->name;
            $jsonSynapse['inputNeuron']['xPos']         = $synapse->inputNeuron->xPos;
            $jsonSynapse['inputNeuron']['yPos']         = $synapse->inputNeuron->yPos;
            $jsonSynapse['inputNeuron']['isBiasNeuron'] = $synapse->inputNeuron->isBiasNeuron;
            $jsonSynapse['inputNeuron']['activationFunction']   = $synapse->inputNeuron->activationFunction;
            $jsonSynapse['inputNeuron']['latestOutputValue']    = $synapse->inputNeuron->latestOutputValue;
            $jsonSynapse['inputNeuron']['previousOutputValue']  = $synapse->inputNeuron->previousOutputValue;
          } else {
            $jsonSynapse['inputNeuron'] = NULL;
          }
          if($synapse->outputNeuron) {
            $jsonSynapse['outputNeuron'] = [];
            $jsonSynapse['outputNeuron']['ID']           = $synapse->outputNeuron->ID;
            $jsonSynapse['outputNeuron']['name']         = $synapse->outputNeuron->name;
            $jsonSynapse['outputNeuron']['xPos']         = $synapse->outputNeuron->xPos;
            $jsonSynapse['outputNeuron']['yPos']         = $synapse->outputNeuron->yPos;
            $jsonSynapse['outputNeuron']['isBiasNeuron'] = $synapse->outputNeuron->isBiasNeuron;
            $jsonSynapse['outputNeuron']['activationFunction']   = $synapse->outputNeuron->activationFunction;
            $jsonSynapse['outputNeuron']['latestOutputValue']    = $synapse->outputNeuron->latestOutputValue;
            $jsonSynapse['outputNeuron']['previousOutputValue']  = $synapse->outputNeuron->previousOutputValue;
          } else {
            $jsonSynapse['outputNeuron'] = NULL;
          }

          $jsonNeuron['outputSynapses'][] = $jsonSynapse;
        }

        $jsonHiddenLayer['neurons'][] = $jsonNeuron;
      }

      $jsonData['hiddenLayer'][] = $jsonHiddenLayer;
    }


    // Output layer
    $jsonData['outputLayer']['ID']          = $this->outputLayer->ID;
    $jsonData['outputLayer']['name']         = $this->outputLayer->name;
    $jsonData['outputLayer']['position']     = $this->outputLayer->position;
    $jsonData['outputLayer']['neuronCount']  = $this->outputLayer->neuronCount;
    $jsonData['outputLayer']['neurons']      = [];
    foreach($this->outputLayer->neurons as $neuron) {
      $jsonNeuron = [];
      $jsonNeuron['ID']   = $neuron->ID;
      $jsonNeuron['name'] = $neuron->name;
      $jsonNeuron['xPos'] = $neuron->xPos;
      $jsonNeuron['yPos'] = $neuron->yPos;
      $jsonNeuron['isBiasNeuron']         = $neuron->isBiasNeuron;
      $jsonNeuron['activationFunction']   = $neuron->activationFunction;
      $jsonNeuron['latestOutputValue']    = $neuron->latestOutputValue;
      $jsonNeuron['previousOutputValue']  = $neuron->previousOutputValue;
      $jsonNeuron['inputSynapses']        = [];
      $jsonNeuron['outputSynapses']       = [];

      foreach($neuron->inputSynapses as $synapse) {
        $jsonSynapse = [];
        $jsonSynapse['ID']    = $synapse->ID;
        $jsonSynapse['value'] = $synapse->getValue();
        $jsonSynapse['valueChangeDisabled'] = $synapse->valueChangeDisabled;
        $jsonSynapse['weight'] = $synapse->getWeight();
        $jsonSynapse['weightChangeDisabled'] = $synapse->weightChangeDisabled;

        if($synapse->inputNeuron) {
          $jsonSynapse['inputNeuron'] = [];
          $jsonSynapse['inputNeuron']['ID']           = $synapse->inputNeuron->ID;
          $jsonSynapse['inputNeuron']['name']         = $synapse->inputNeuron->name;
          $jsonSynapse['inputNeuron']['xPos']         = $synapse->inputNeuron->xPos;
          $jsonSynapse['inputNeuron']['yPos']         = $synapse->inputNeuron->yPos;
          $jsonSynapse['inputNeuron']['isBiasNeuron'] = $synapse->inputNeuron->isBiasNeuron;
          $jsonSynapse['inputNeuron']['activationFunction']   = $synapse->inputNeuron->activationFunction;
          $jsonSynapse['inputNeuron']['latestOutputValue']    = $synapse->inputNeuron->latestOutputValue;
          $jsonSynapse['inputNeuron']['previousOutputValue']  = $synapse->inputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['inputNeuron'] = NULL;
        }
        if($synapse->outputNeuron) {
          $jsonSynapse['outputNeuron'] = [];
          $jsonSynapse['outputNeuron']['ID']           = $synapse->outputNeuron->ID;
          $jsonSynapse['outputNeuron']['name']         = $synapse->outputNeuron->name;
          $jsonSynapse['outputNeuron']['xPos']         = $synapse->outputNeuron->xPos;
          $jsonSynapse['outputNeuron']['yPos']         = $synapse->outputNeuron->yPos;
          $jsonSynapse['outputNeuron']['isBiasNeuron'] = $synapse->outputNeuron->isBiasNeuron;
          $jsonSynapse['outputNeuron']['activationFunction']   = $synapse->outputNeuron->activationFunction;
          $jsonSynapse['outputNeuron']['latestOutputValue']    = $synapse->outputNeuron->latestOutputValue;
          $jsonSynapse['outputNeuron']['previousOutputValue']  = $synapse->outputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['outputNeuron'] = NULL;
        }

        $jsonNeuron['inputSynapses'][] = $jsonSynapse;
      }

      foreach($neuron->outputSynapses as $synapse) {
        $jsonSynapse = [];
        $jsonSynapse['ID']    = $synapse->ID;
        $jsonSynapse['value'] = $synapse->getValue();
        $jsonSynapse['valueChangeDisabled'] = $synapse->valueChangeDisabled;
        $jsonSynapse['weight'] = $synapse->getWeight();
        $jsonSynapse['weightChangeDisabled'] = $synapse->weightChangeDisabled;

        if($synapse->inputNeuron) {
          $jsonSynapse['inputNeuron'] = [];
          $jsonSynapse['inputNeuron']['ID']           = $synapse->inputNeuron->ID;
          $jsonSynapse['inputNeuron']['name']         = $synapse->inputNeuron->name;
          $jsonSynapse['inputNeuron']['xPos']         = $synapse->inputNeuron->xPos;
          $jsonSynapse['inputNeuron']['yPos']         = $synapse->inputNeuron->yPos;
          $jsonSynapse['inputNeuron']['isBiasNeuron'] = $synapse->inputNeuron->isBiasNeuron;
          $jsonSynapse['inputNeuron']['activationFunction']   = $synapse->inputNeuron->activationFunction;
          $jsonSynapse['inputNeuron']['latestOutputValue']    = $synapse->inputNeuron->latestOutputValue;
          $jsonSynapse['inputNeuron']['previousOutputValue']  = $synapse->inputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['inputNeuron'] = NULL;
        }
        if($synapse->outputNeuron) {
          $jsonSynapse['outputNeuron'] = [];
          $jsonSynapse['outputNeuron']['ID']           = $synapse->outputNeuron->ID;
          $jsonSynapse['outputNeuron']['name']         = $synapse->outputNeuron->name;
          $jsonSynapse['outputNeuron']['xPos']         = $synapse->outputNeuron->xPos;
          $jsonSynapse['outputNeuron']['yPos']         = $synapse->outputNeuron->yPos;
          $jsonSynapse['outputNeuron']['isBiasNeuron'] = $synapse->outputNeuron->isBiasNeuron;
          $jsonSynapse['outputNeuron']['activationFunction']   = $synapse->outputNeuron->activationFunction;
          $jsonSynapse['outputNeuron']['latestOutputValue']    = $synapse->outputNeuron->latestOutputValue;
          $jsonSynapse['outputNeuron']['previousOutputValue']  = $synapse->outputNeuron->previousOutputValue;
        } else {
          $jsonSynapse['outputNeuron'] = NULL;
        }

        $jsonNeuron['outputSynapses'][] = $jsonSynapse;
      }

      $jsonData['outputLayer']['neurons'][] = $jsonNeuron;
    }



    return json_encode($jsonData);
  }

}
