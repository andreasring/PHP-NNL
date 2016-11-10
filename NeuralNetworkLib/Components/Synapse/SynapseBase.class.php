<?php
namespace NeuralNetworkLib\Components\Synapse;

use \NeuralNetworkLib\Components\Weight\Weight as Weight;

/**
 * Synapse base class
 *
 */
class SynapseBase {

  /**
   * The synapse's ID
   */
  public $ID;

  /**
   * The synapse's input neuron
   */
  public $inputNeuron = NULL;

  /**
   * The synapse's output neuron
   */
  public $outputNeuron = NULL;

  /**
   * The synapse's value
   */
  public $value;

  /**
   *  The synapse's weight
   */
  public $weight;

  /**
   * Is changing the value of this synapse allowed?
   */
  public $valueChangeDisabled = FALSE;

  /**
   *  Is changing the weight of this synapse allowed?
   */
  public $weightChangeDisabled = FALSE;

  /**
   * The network the weight is part of
   */
  protected $network;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Synapse base connstruct
   *
   */
  public function __construct($network, $inputNeuron = NULL, $outputNeuron = NULL, $value = 0.0, $weight = 0.0) {
    // Auto incrementing ID
    static $synapseID     = 0;
    $this->ID             = $synapseID++;

    // Set data
    $this->network        = $network;
    $this->inputNeuron    = $inputNeuron;
    $this->outputNeuron   = $outputNeuron;
    $this->value          = $value;
    //$this->weight         = $weight;
    $this->weight         = new Weight($network, $weight);
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Sets the input neuron
   *
   */
  public function setInputNeuron($inputNeuron) {
    $this->inputNeuron = $inputNeuron;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Sets the output neuron
   *
   */
  public function setOutputNeuron($outputNeuron) {
    $this->outputNeuron = $outputNeuron;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Sets the synapse value
   *
   */
  public function setValue($value) {
    if($this->valueChangeDisabled) {
      return;
    }
    $this->value = $value;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Returns the synapse value
   *
   */
  public function getValue() {
    return $this->value;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Sets the synapse weight
   *
   */
  public function setWeight($weight) {
    if($this->weightChangeDisabled) {
      return;
    }
    //$this->weight = $weight;
    $this->weight->value = $weight;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Returns the synapse weight
   *
   */
  public function getWeight() {
    return $this->weight->value;
  }

}
