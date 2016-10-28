<?php
namespace NeuralNetworkLib\Components;

/**
 * Layer class
 *
 */
class Layer {

  /**
   * Layer name
   */
  public $name = 'Layer';

  /**
   * The layer's "x" or "row" position in the network
   */
  public $position = 0;

  /**
   * The network this layer is in
   */
  public $network = NULL;

  /**
   * The number of neurons in this layer
   */
  public $neuronCount = 0;

  /**
   * TODO
   *
   * The "previous" layer in the network
   */
  public $previousLayer = NULL;

  /**
   * TODO
   *
   * The "next" layer in the network
   */
  public $nextLayer = NULL;

  /**
   * All the neurons in the layer
   */
  public $neurons = [];


  // --------------------------------------------------------------------------------------------------------
  /**
   * Layer connstruct
   *
   */
  public function __construct($network, $name, $position, $neuronCount) {
    $this->network = $network;
    $this->name = $name;
    $this->position = $position;
    $this->neuronCount = $neuronCount;

    $this->createNeurons();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Create the layer's neurons
   *
   */
  public function createNeurons() {
    for($i=0;$i<$this->neuronCount;$i++) {
      $network        = $this->network;
      $layer          = $this;
      $previousLayer  = $this->previousLayer;
      $nextLayer      = $this->nextLayer;
      $yPos           = $i;
      $name           = 'n_'.$this->position.'_'.$yPos;
      $neuron         = new Neuron($network, $layer, $previousLayer, $nextLayer, $name, $yPos);
      $this->addNeuron($neuron);
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Adds a neuron to the layer
   *
   */
  public function addNeuron($neuron) {
    $this->neurons[] = $neuron;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Add's a bias neuron to the layer
   *
   */
  public function createBiasNeuron() {
    $network        = $this->network;
    $layer          = $this;
    $previousLayer  = $this->previousLayer;
    $nextLayer      = $this->nextLayer;
    $yPos           = -3;
    $name           = 'n_'.$this->position.'_bias';
    $isBiasNeuron   = TRUE;
    $biasNeuron = new Neuron($network, $layer, $previousLayer, $nextLayer, $name, $yPos, $isBiasNeuron);
    $this->addNeuron($biasNeuron);
  }

  // --------------------------------------------------------------------------------------------------------
  /**
   * Connects the layer's bias neuron to all the other neurons in the layer
   *
   */
  public function connectBiasNeuron() {
    foreach($this->neurons as $biasNeuron) {
      // Ignore if not bioas neuron / Locate bias neuron
      if(!$biasNeuron->isBiasNeuron) {
        continue;
      }

      // Connect to all other neurons in the layer
      foreach($this->neurons as $otherNeuron) {
        // Ignore if bias neuron / find all the neurons that are not the bias neuron
        if($otherNeuron->isBiasNeuron) {
          continue;
        }

        $inputNeuron      = $biasNeuron;
        $outputNeuron     = $otherNeuron;
        $value            = 1.0;
        $weight           = 1;
        $synapse          = new Synapse($inputNeuron, $outputNeuron, $value, $weight);

        $otherNeuron->addInputSynapse($synapse);
        $biasNeuron->addOutputSynapse($synapse);
      }
    }
  }

}
