<?php
namespace NeuralNetworkLib\TrainingAlgorithms;

use \NeuralNetworkLib\CostFunctions as CostFunctions;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Training algorithm class: Gradient Decent
 *
 */
class GradientDecent extends TrainingAlgorithmBase {

  /**
   * Defines default configuration for this algorithm
   */
  public $configuration = [
    'recordErrorRate' => TRUE,
    'trainingRounds' => 1000,
    'learningRate' => 0.1,
    'momentumEnabled'=> TRUE,
    'momentumValue' => 0.2
  ];


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run the training algorithm
   *
   */
  public function train() {
    $recordErrorRate  = $this->configuration['recordErrorRate'];
    $trainingRounds   = $this->configuration['trainingRounds'];
    $learningRate     = $this->configuration['learningRate'];
    $momentumEnabled  = $this->configuration['momentumEnabled'];
    $momentumValue    = $this->configuration['momentumValue'];
    $network          = $this->network;
    $trainingDatas    = $this->trainingData;

    $error = 0.0;
    for($i=0;$i<$trainingRounds;$i++) {

      $error = 0.0;
      foreach($trainingDatas as $trainingData) {
        $networkInputData = $trainingData[0];
        $expectedOutputs  = $trainingData[1];
        $networkOutputs   = $network->calculate($networkInputData);

        // Keep track of the error rate
        $innerError = CostFunctions\RootMeanSquaredError::calculate($networkOutputs, $expectedOutputs);
        $error += $innerError;


        // Backpropagation ---------------------------------------------------------------------------------
        // First calculate error gradients for the output layer
        foreach($network->outputLayer->neurons as $outputNeuronIndex => $outputNeuron) {
          // Skip bias neuron
          if(isset($outputNeuron->isBiasNeuron)) {
            continue;
          }

          // Get the output of the neuron and the expected output of the neuron (from training data)
          $neuronOutput = $outputNeuron->latestOutputValue;
          $expectedOutput = $expectedOutputs[$outputNeuronIndex];

          // Calculate the difference
          $outputDiff = $expectedOutput - $neuronOutput;

          // Calculate the error gradient of the output neuron
          $errorGradient = \NeuralNetworkLib\ActivationFunctions\HyperbolicTangent::derivative($neuronOutput) * $outputDiff;
          $outputNeuron->errorGradient = $errorGradient;
        }


        // Now we calculate the error gradients for all the neurons in the hidden layers
        // One layer at a time, starting with the last one
        $hiddenLayer = $network->hiddenLayers[count($network->hiddenLayers)-1];
        while($hiddenLayer->previousLayer != NULL) {

          // Each neuron in the hidden layer
          foreach($hiddenLayer->neurons as $hiddenLayerNeuron) {

            // Calculate the sum of (weight * errorGradient) for all output synapses
            $sumOfConnectedSynapsesWeightsAndGradients = 0.0;
            foreach($hiddenLayerNeuron->outputSynapses as $outputSynapse) {
              $synapseInputNeuronErrorGradient = $outputSynapse->outputNeuron->errorGradient;
              $synapseWeight = $outputSynapse->getWeight();
              $sumOfConnectedSynapsesWeightsAndGradients += $synapseInputNeuronErrorGradient * $synapseWeight;
            }

            // Calculate the error gradient of this neuron based on the connected next layer neurons summed above
            $nodeValue = $hiddenLayerNeuron->latestOutputValue;
            $errorGradient = \NeuralNetworkLib\ActivationFunctions\HyperbolicTangent::derivative($nodeValue) * $sumOfConnectedSynapsesWeightsAndGradients;
            $hiddenLayerNeuron->errorGradient = $errorGradient;
          }

          // Get the previous layer and loop again!
          $hiddenLayer = $hiddenLayer->previousLayer;
        }


        // Calculate and update weights
        // Loop through all layers except input layer
        $layer = $network->outputLayer;
        while($layer->previousLayer != NULL) {

          // For all the neurons in this layer
          foreach($layer->neurons as $neuron) {
            // For all the input synapses of this neuron
            foreach($neuron->inputSynapses as $inputSynapse) {
              // Get the weight and value of the synapse connection
              $synapseWeight = $inputSynapse->getWeight();
              $synapseValue = $inputSynapse->getValue();

              // Multiply the error gradient of the neuron with the synapse value and the learning rate
              $weightCorrection = $learningRate * $synapseValue * $neuron->errorGradient;

              // If momentum enabled
              if($momentumEnabled) {
                // Get the previous weight correction if any, otherwise just use zero
                $previousWeightCorrection = $inputSynapse->previousWeightCorrection ?? 0;

                // Add inn a bit of the previous weight correction
                $weightCorrection += ($momentumValue * $previousWeightCorrection);

                // Remember the weight correction for next time
                $inputSynapse->previousWeightCorrection = $weightCorrection;
              }

              // Sum the weight correction with the old synapse weight
              $newWeight = $weightCorrection + $synapseWeight;

              // Set new weight
              $inputSynapse->setWeight($newWeight);
            }
          }

          // Get the previous layer and loop again!
          $layer = $layer->previousLayer;
        }

        // END Backpropagation ---------------------------------------------------------------------------------

      }

      // Average sample data error for this round
      $error = $error / count($trainingDatas);

      // Record error rate if wanted
      if($recordErrorRate) {
        $this->recordErrorValue($error);
      }


    }

  }

}
