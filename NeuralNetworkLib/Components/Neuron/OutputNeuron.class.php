<?php
namespace NeuralNetworkLib\Components\Neuron;

/**
 * Output neuron class
 *
 */
class OutputNeuron extends NeuronBase {

  /**
   * The output neuron's output
   *
   */
  public $output = 0;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Since this is a output neuron, the calculate function is a bit different
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

    // Set the output of the neuron
    $this->output = $value;

    // Update the neuron's output data
    $this->previousOutputValue = $this->latestOutputValue;
    $this->latestOutputValue = $value;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Get output data
   *
   */
  public function getOutput() {
    return $this->output;
  }

}
