<?php
namespace NeuralNetworkLib\Components\Layer;

use \NeuralNetworkLib\Components\Neuron\InputNeuron as InputNeuron;

/**
 * Input layer class
 *
 */
class InputLayer extends HiddenLayer {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Input layer connstruct
   *
   */
  public function __construct($network, $previousLayer, $nextLayer, $name, $position, $neuronCount) {
    // Call common layer constructor
    parent::__construct($network, $previousLayer, $nextLayer, $name, $position, $neuronCount);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Because this is the input layer, we have a special case of createNeuron() which will override the common and base class
   *
   */
  public function createNeuron($yPos) {
    // Create a input neturon
    $network        = $this->network;
    $layer          = $this;
    $previousLayer  = $this->previousLayer;
    $nextLayer      = $this->nextLayer;
    $yPos           = $yPos;
    $name           = 'n_'.$this->position.'_'.$yPos;
    $neuron         = new InputNeuron($network, $layer, $previousLayer, $nextLayer, $name, $yPos);

    // Add the neuron to this layer and the network neuron store
    $this->addNeuron($neuron);
    $this->network->addNeuron($neuron);

    // Return the newly created neuron
    return $neuron;
  }

}
