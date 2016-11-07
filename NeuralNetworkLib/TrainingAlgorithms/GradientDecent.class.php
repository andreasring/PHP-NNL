<?php
namespace NeuralNetworkLib\TrainingAlgorithms;

/**
 * Training algorithm class: Gradient Decent
 *
 */
class GradientDecent {

  /**
   * The netwotk to train
   */
  private $network;

  /**
   * Training config
   */
  private $config;

  /**
   * Best outcomes of the training
   */
  public $bestOutcomes;


  // --------------------------------------------------------------------------------------------------------
  /**
   * Attempt to train the network
   *
   */
  public function __construct($network, $config = []) {
    $this->network = $network;
    $this->config = $this->readConfig($config);

    $this->run();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Set default configurations and override with those who are passed in
   *
   */
  private function readConfig($overrideConfig) {
    $config = [
      'trainingRounds' => 1000,
      'bestOutcomesNum' => 5,
      'learningRate' => 0.1
    ];

    array_merge($config, $overrideConfig);

    return $config;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run the training algorithm
   *
   */
  private function run() {
    $bestOutcomes     = [];
    $bestOutcomesNum  = $this->config['bestOutcomesNum'];
    $trainingRounds   = $this->config['trainingRounds'];
    $learningRate     = $this->config['learningRate'];

    $network          = $this->network;
    $trainingDatas    = $network->trainingData;

    $error = 0.0;
    $previousError = 0.0;
    for($i=0;$i<$trainingRounds;$i++) {

      $error = 0.0;
      foreach($trainingDatas as $trainingData) {
        $networkInputData = $trainingData[0];
        $expectedOutputs  = $trainingData[1];
        $networkOutputs   = $network->calculate($networkInputData);


        // Keep track of the error rate
        $innerError = 0.0;
        foreach($networkOutputs as $index => $networkOutput) {
          $expectedOutput = $expectedOutputs[$index];
          //$innerError += exp($expectedOutput - $networkOutput);
          $innerError += ($expectedOutput - $networkOutput);
        }
        $innerError = $innerError / count($networkOutputs);
        //$innerError = sqrt($innerError);

        $error += $innerError;
        //var_dump($innerError);


        // Backpropagation ---------------------------------------------------------------------------------
        // First calculate error gradients for the output layer
        foreach($network->outputLayer->neurons as $outputNeuronIndex => $outputNeuron) {
          // Skip bias neuron
          if($outputNeuron->isBiasNeuron) {
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
        $hiddenLayer = $network->hiddenLayer[count($network->hiddenLayer)-1];
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

              // TODO This is where we want to do momentum stuff

              // Multiply the error gradient of the neuron with the synapse value and the learning rate
              $weightCorrection = $learningRate * $synapseValue * $neuron->errorGradient;
              // Sum the synapse weight witht he weight correction
              $newWeight = $synapseWeight + $weightCorrection;
              // Set new weight
              $inputSynapse->setWeight($newWeight);
            }
          }

          // Get the previous layer and loop again!
          $layer = $layer->previousLayer;
        }

        // END Backpropagation ---------------------------------------------------------------------------------



      }

      $error = $error / count($trainingDatas);
      var_dump($error);

      $network->save('top_gradient_net.net');
    }

    echo('Setting network to the best one saved.<br>');
    $network->load('top_gradient_net.net');
  }

}
