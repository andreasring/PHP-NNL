<?php
namespace NeuralNetworkLib\TrainingAlgorithms;

use \NeuralNetworkLib\TrainingAlgorithms\ParticleSwarm\Particle as Particle;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Training algorithm class: Particle swarm
 *
 */
class ParticleSwarm extends TrainingAlgorithmBase {

  /**
   * Defines default configuration for this algorithm
   */
  public $configuration = [
    'recordErrorRate'     => TRUE,
    'trainingRounds'      => 100,
    'maxStopError'        => 0.01,
    'numParticles'        => 10,
    'globalAcceleration'  => 2,
    'socialAcceleration'  => 2,
    'inertia'             => 1,
    'inertiaDampingEnabled' => TRUE,
    'inertiaDamping'      => 0.99,
    'clampPositionValues' => TRUE,
    'minPositionValue'    => -1,
    'maxPositionValue'    => 1,
    'clampVelocityValues' => TRUE,
    'maxVelocityValue'    => 2,
    'minVelocityValue'    => -2,
    'autoClercKennedy2002Values'  => TRUE
  ];

  /**
   * The swarm's best score
   */
  public $globalBestScore = 9999999;

  /**
   * The swarm's best score position
   */
  public $globalBestPosition = [];

  /**
   * The swarm's particles
   */
  public $particles = [];


  // --------------------------------------------------------------------------------------------------------
  /**
   * Particle swarm construct
   *
   */
  public function __construct($network, $config = []) {
    // Do whatever the parent constructor wants
    parent::__construct($network, $config);

    // Initialize the swarm
    $this->init();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Initialize the swarm
   *
   */
  public function init() {
    // Initialize the global best position array
    $numDimentions = count($this->network->allWeights);
    $this->globalBestPosition = array_fill(0, $numDimentions, 0);

    // Create the particles
    $numParticles = $this->getConfig('numParticles');
    for($i=0;$i<$numParticles;$i++) {
      $this->particles[] = new Particle($this);
    }

    // If autoClercKennedy2002Values is TRUE we calculate some values that are known to be good
    if($this->getConfig('autoClercKennedy2002Values')) {
      $kappa  = 1;
      $phi1   = 2.05;
      $phi2   = 2.05;
      $phi    = $phi1 + $phi2;
      $chi    = ( 2*$kappa ) / ( abs( 2-$phi-sqrt(pow($phi, 2)-4*$phi) ) );

      $this->setConfig('inertia', $chi);
      $this->setConfig('inertiaDampingEnabled', FALSE);
      $this->setConfig('globalAcceleration', $chi*$phi1);
      $this->setConfig('socialAcceleration', $chi*$phi2);
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Run the training algorithm
   *
   */
  public function train() {
    // Init all the particles (We have to do this here because we need to add training data before we can do this)
    foreach($this->particles as $particle) {
      $particle->init();
    }

    // Grab configurations
    $recordErrorRate  = $this->getConfig('recordErrorRate');
    $trainingRounds   = $this->getConfig('trainingRounds');

    // Run for trainingRounds times
    for($i=0;$i<$trainingRounds;$i++) {

      // Let the particles do their thing (They will update globalBestScore and other stuff in this class)
      foreach($this->particles as $particle) {
        $particle->move();

        // Have we reached the max stop limit?
        if($this->globalBestScore < $this->getConfig('maxStopError')) {
          $this->recordErrorValue($this->globalBestScore);
          return;
        }
      }

      // Record error rate if wanted
      if($recordErrorRate) {
        $this->recordErrorValue($this->globalBestScore);
      }

      // Have we reached the max stop limit?
      if($this->globalBestScore < $this->getConfig('maxStopError')) {
        return;
      }

      // Damp the inertia if enabled
      if($this->getConfig('inertiaDampingEnabled')) {
        $this->setConfig('inertia', $this->getConfig('inertia') * $this->getConfig('inertiaDamping'));
      }

    }

  }

}
