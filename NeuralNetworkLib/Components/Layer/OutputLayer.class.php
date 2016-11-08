<?php
namespace NeuralNetworkLib\Components\Layer;

use \NeuralNetworkLib\Components\Neuron\OutputNeuron as OutputNeuron;

/**
 * Output layer class
 *
 */
class OutputLayer extends HiddenLayer {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Output layer connstruct
   *
   */
  public function __construct($network, $previousLayer, $nextLayer, $name, $position, $neuronCount) {
    // Call common layer constructor
    parent::__construct($network, $previousLayer, $nextLayer, $name, $position, $neuronCount);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Because this is the output layer, we have a special case of createNeuron() which will override the common and base class
   *
   */
  public function createNeuron($yPos) {
    // Create a output neturon
    $network        = $this->network;
    $layer          = $this;
    $previousLayer  = $this->previousLayer;
    $nextLayer      = $this->nextLayer;
    $yPos           = $yPos;
    $name           = 'n_'.$this->position.'_'.$yPos;
    $neuron         = new OutputNeuron($network, $layer, $previousLayer, $nextLayer, $name, $yPos);

    // Add the neuron to this layer and the network neuron store
    $this->addNeuron($neuron);
    $this->network->addNeuron($neuron);

    // Return the newly created neuron
    return $neuron;
  }

}
