<?php
namespace NeuralNetworkLib\Components\Neuron;

use \NeuralNetworkLib\Components\Synapse\Synapse as Synapse;
use \NeuralNetworkLib\Helpers as Helpers;

/**
 * Neuron base class
 *
 */
class NeuronBase {

  /**
   * The neuron's ID
   */
  public $ID;

  /**
   * The neuron's name
   */
  public $name;

  /**
   * The neuron's inputs
   */
  public $inputSynapses = [];

  /**
   * The neuron's output
   */
  public $outputSynapses = [];

  /**
   * The network this neuron is in
   */
  protected $network = NULL;

  /**
   * The neuron's layer
   */
  public $layer = NULL;

  /**
   * The "previous" layer in the network
   */
  protected $previousLayer = NULL;

  /**
   * The "next" layer in the network
   */
  protected $nextLayer = NULL;

  /**
   * The neuron's x position
   */
  public $xPos = 0;

  /**
   * The neuron's x position
   */
  public $yPos = 0;

  /**
   * The neuron's activation function
   */
  public $activationFunction;

  /**
   * The latest output of this neuron
   */
  public $latestOutputValue = 0;

  /**
   * The previous output of this neuron
   */
  public $previousOutputValue = 0;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Neuron base constructor
   *
   */
  public function __construct($network, $layer, $previousLayer, $nextLayer, $name, $yPos = -1) {
    // Auto incrementing ID
    static $neuronID      = 0;
    $this->ID             = $neuronID++;

    // Set data
    $this->network        = $network;
    $this->layer          = $layer;
    $this->previousLayer  = $previousLayer;
    $this->nextLayer      = $nextLayer;
    $this->name           = $name;
    $this->xPos           = $layer->position;
    $this->yPos           = $yPos;

    // Set a default activation function
    $this->activationFunction = '\NeuralNetworkLib\ActivationFunctions\HyperbolicTangent::calculate';
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Calculate the neuron's value
   *
   */
  public function calculate() {
    // Calculate the neuron's value from all the input synapses
    $value = 0.0;
    foreach($this->inputSynapses as $inputSynapse) {
      $value += ($inputSynapse->getValue() * $inputSynapse->getWeight());
    }

    // Run the activation function on the value
    $value = $this->runActivationFunction($value);

    // Set the output synapses's value to the neuron value
    foreach($this->outputSynapses as $outputSynapse) {
      $outputSynapse->setValue($value);
    }

    // Update the neuron's output data
    $this->previousOutputValue = $this->latestOutputValue;
    $this->latestOutputValue = $value;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run whatever activation function is used
   *
   */
  protected function runActivationFunction($value) {
    return call_user_func($this->activationFunction, $value);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Link this neuron to another neuron
   *
   */
  public function linkNeuronTo($outputNeuron, $inputNeuron = NULL, $resolveInputNeuronToSelf = TRUE) { // Nasty hack
    // If input neuron is NULL we mean $this
    if($inputNeuron == NULL && $resolveInputNeuronToSelf) {
      $inputNeuron = $this;
    }

    // Create new synapse
    $value            = 0.0;
    $weight           = Helpers\Misc::generateRandomWeight();
    $synapse          = new Synapse($this->network, $inputNeuron, $outputNeuron, $value, $weight);

    // Add synapse to this neuron's output synapses
    if($inputNeuron) { // Nasty hack
      $inputNeuron->addOutputSynapse($synapse);
    }

    // Add synapse to the other neuron's input synapses
    if($outputNeuron) { // Nasty hack
      $outputNeuron->addInputSynapse($synapse);
    }

    // Add synapse to the network's synapse store
    $this->network->addSynapse($synapse);

    // Return the newly created synapse
    return $synapse;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Link another neuron to this neuron
   *
   */
  public function linkFromNeuron($neuron) {
    $this->linkNeuronTo($this, $neuron);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Adds a synapse to the neuron input synapses
   *
   */
  public function addInputSynapse($synapse) {
    $this->inputSynapses[] = $synapse;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Adds a synapse to the neuron output synapses
   *
   */
  public function addOutputSynapse($synapse) {
    $this->outputSynapses[] = $synapse;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Set the neuron's activation function
   *
   */
  public function setActivationFunction($function) {
    $this->activationFunction = $function;
  }

}
