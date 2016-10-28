<?php
namespace NeuralNetworkLib\Components;

/**
 * Neuron class
 *
 */
class Neuron {

  /**
   * The neuron's name/id
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
  public $network = NULL;

  /**
   * The neuron's layer
   */
  public $layer = NULL;

  /**
   * The "previous" layer in the network
   */
  public $previousLayer = NULL;

  /**
   * The "next" layer in the network
   */
  public $nextLayer = NULL;

  /**
   * The neuron's x position
   */
  public $xPos = 0;

  /**
   * The neuron's x position
   */
  public $yPos = 0;

  /**
   * Is this a bias neuron?
   */
  public $isBiasNeuron = FALSE;

  /**
   * The neuron's activation function
   */
  public $activationFunction;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Neuron constructor
   *
   */
  public function __construct($network, $layer, $previousLayer, $nextLayer, $name, $yPos = -1, $isBias = FALSE) {
    $this->network        = $network;
    $this->layer          = $layer;
    $this->previousLayer  = $previousLayer;
    $this->nextLayer      = $nextLayer;
    $this->name           = $name;
    $this->xPos           = $layer->position;
    $this->yPos           = $yPos;
    $this->isBiasNeuron   = $isBias;

    // Set a default activation function
    $this->activationFunction = '\NeuralNetworkLib\ActivationFunctions\hyperbolicTangent::calculate';
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

    // TODO Treshold function!?

    // Run the activation function on the value
    $value = $this->runActivationFunction($value);

    // Set the output synapses's value to the neuron value
    foreach($this->outputSynapses as $outputSynapse) {
      $outputSynapse->setValue($value);
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run whatever activation function is used
   *
   */
  public function runActivationFunction($value) {
    return call_user_func($this->activationFunction, $value);
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
