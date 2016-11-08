<?php
namespace NeuralNetworkLib\Components\Neuron;

/**
 * Input neuron class
 *
 */
class InputNeuron extends NeuronBase {

  /**
   * The input neuron's input
   *
   */
  public $inputData = 0.0;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Since this is a input neuron, the calculate function is a bit different
   *
   */
  public function calculate() {
    // Get the input data
    $inputData = $this->inputData;

    // Run activation function on the input data
    $value = $this->runActivationFunction($inputData);

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
   * Set input data
   *
   */
  public function setInputData($inputData) {
    $this->inputData = $inputData;
  }

}
