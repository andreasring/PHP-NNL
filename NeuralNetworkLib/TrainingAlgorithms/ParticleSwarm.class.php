<?php
namespace NeuralNetworkLib\TrainingAlgorithms;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Training algorithm class: Particle swarm
 *
 *
 *
 */
class ParticleSwarm extends TrainingAlgorithmBase {

  /**
   * Defines default configuration for this algorithm
   */
  protected $configuration = [
    'trainingRounds' => 1000,
    'learningRate' => 0.1
  ];


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run the training algorithm
   *
   */
  public function train($userDefinedConfig) {

  }

}
