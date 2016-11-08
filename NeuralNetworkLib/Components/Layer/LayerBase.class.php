<?php
namespace NeuralNetworkLib\Components\Layer;

use \NeuralNetworkLib\Components\Neuron\Neuron as Neuron;

/**
 * Layer base class
 *
 */
class LayerBase {

  /**
   * Layer ID
   */
  public $ID;

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
  protected $network = NULL;

  /**
   * The number of neurons in this layer
   */
  public $neuronCount = 0;

  /**
   * The "previous" layer in the network
   */
  public $previousLayer = NULL;

  /**
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
  public function __construct($network, $previousLayer, $nextLayer, $name, $position, $neuronCount) {
    // Auto incrementing ID
    static $layerID       = 0;
    $this->ID             = $layerID++;

    // Set data
    $this->network        = $network;
    $this->previousLayer  = $previousLayer;
    $this->nextLayer      = $nextLayer;
    $this->name           = $name;
    $this->position       = $position;
    $this->neuronCount    = $neuronCount;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Create the layer's neurons from $this->neuronCount
   *
   */
  public function createNeurons() {
    for($i=0;$i<$this->neuronCount;$i++) {
      $this->createNeuron($i);
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Create a new neuron for this layer
   *
   */
  public function createNeuron($yPos) {
    // Create the neturon
    $network        = $this->network;
    $layer          = $this;
    $previousLayer  = $this->previousLayer;
    $nextLayer      = $this->nextLayer;
    $yPos           = $yPos;
    $name           = 'n_'.$this->position.'_'.$yPos;
    $neuron         = new Neuron($network, $layer, $previousLayer, $nextLayer, $name, $yPos);

    // Add the neuron to this layer and the network neuron store
    $this->addNeuron($neuron);
    $this->network->addNeuron($neuron);

    // Return the newly created neuron
    return $neuron;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Adds a neuron to the layer
   *
   */
  public function addNeuron($neuron) {
    $this->neurons[] = $neuron;
  }

}
