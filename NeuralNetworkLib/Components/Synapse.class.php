<?php
namespace NeuralNetworkLib\Components;

/**
 * Synapse class
 *
 */
class Synapse {

  /**
   *
   */
  public $inputNeuron = NULL;

  /**
   *
   */
  public $outputNeuron;

  /**
   *
   */
  private $value;

  /**
   *
   */
  private $valueChangeDisabled = FALSE;

  /**
   *
   */
  private $weight;

  /**
   *
   */
  private $weightChangeDisabled = FALSE;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Synapse connstruct
   *
   */
  public function __construct($inputNeuron = NULL, $outputNeuron = NULL, $value = 0.0, $weight = 0.0) {
    $this->inputNeuron    = $inputNeuron;
    $this->outputNeuron   = $outputNeuron;
    $this->value          = $value;
    $this->weight         = $weight;
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
    $this->weight = $weight;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Returns the synapse weight
   *
   */
  public function getWeight() {
    return $this->weight;
  }

}
