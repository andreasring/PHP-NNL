<?php
namespace NeuralNetworkLib\Components\Layer;

use \NeuralNetworkLib\Components\Neuron\BiasNeuron as BiasNeuron;
use \NeuralNetworkLib\Components\Synapse\Synapse as Synapse;

/**
 * Normal layer class
 *
 */
class Layer extends LayerBase {

  /**
   * The layer's bias neuron
   */
  public $biasNeuron = NULL;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Layer connstruct
   *
   */
  public function __construct($network, $previousLayer, $nextLayer, $name, $position, $neuronCount) {
    // Call layer base constructor
    parent::__construct($network, $previousLayer, $nextLayer, $name, $position, $neuronCount);

    // Create the neurons
    $this->createNeurons();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Add a bias neuron to the layer
   *
   */
  public function createBiasNeuron() {
    // Create bias neuron
    $network        = $this->network;
    $layer          = $this;
    $previousLayer  = $this->previousLayer;
    $nextLayer      = $this->nextLayer;
    $yPos           = -3;
    $name           = 'n_'.$this->position.'_bias';
    $biasNeuron = new BiasNeuron($network, $layer, $previousLayer, $nextLayer, $name, $yPos);

    // Add bias neuron to the layer and the network's neuron store
    $this->addNeuron($biasNeuron);
    $this->network->addNeuron($biasNeuron);

    // Set the layer's bias neuron
    $this->biasNeuron = $biasNeuron;

    // Return the newly created bias neuron
    return $biasNeuron;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Connects the layer's bias neuron to all the other neurons in the layer
   *
   */
  public function connectBiasNeuron() {
    // Connect the bias neuron to all the other neurons in the layer
    foreach($this->neurons as $neuron) {
      // Ignore if bias neuron
      if(isset($neuron->isBiasNeuron)) {
        continue;
      }

      $biasSynapse = $this->biasNeuron->linkNeuronTo($neuron);

      // Set bias synapse value and weight to 1
      $biasSynapse->setValue(1);
      $biasSynapse->setWeight(1);
    }
  }

}
