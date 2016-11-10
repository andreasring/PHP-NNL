<?php
namespace NeuralNetworkLib\TrainingAlgorithms\ParticleSwarm;

use \NeuralNetworkLib\Helpers\Misc as Misc;
use \NeuralNetworkLib\CostFunctions as CostFunctions;

// --------------------------------------------------------------------------------------------------------------------------
/**
 * Particle class
 *
 */
class Particle {

  /**
   * The swarm the particle is member of
   */
  public $swarm;

  /**
   * Personal best error rate rate/value/score
   */
  public $personalBestScore = 9999999;

  /**
   * Personal best error rate position
   */
  public $personalBestPosition = [];

  /**
   * Current position
   */
  public $currentPosition = [];

  /**
   * Current score
   */
  public $currentScore = 0;

  /**
   * Velocity
   */
  public $velocity = [];


  // --------------------------------------------------------------------------------------------------------
  /**
   * Particle connstruct
   *
   */
  public function __construct($swarm) {
    // Set our own swarm pointer
    $this->swarm = $swarm;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Initializes the particle
   *
   */
  public function init() {
    // Init the positions and velocity
    $numDimentions = count($this->swarm->network->allWeights);
    for($i=0;$i<$numDimentions;$i++) {
      //$randomVal = Misc::generateRandomWeight();
      $randomVal = mt_rand($this->swarm->getConfig('minPositionValue'), $this->swarm->getConfig('maxPositionValue'));
      $this->personalBestPosition[] = $randomVal;
      $this->currentPosition[] = $randomVal;
      $this->velocity[] = 0;
    }

    // Run through the training data and calculate error rate
    $this->calculateCurrentErrorRate();

    // Update personal and global scores
    $this->checkIfBetter();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * "move" the particle and evaluate scores
   *
   */
  public function move() {
    // Update velocity
    $this->updateVelocity();

    // Velocity claming if enabled
    if($this->swarm->getConfig('clampVelocityValues')) {
      $this->clampVelocityValues();
    }

    // Update position
    $this->updatePosition();

    // Position claming if enabled
    if($this->swarm->getConfig('clampPositionValues')) {
      $this->clampPositionValues();
    }

    // Run through the training data and calculate error rate
    $this->calculateCurrentErrorRate();

    // Update personal and global scores
    $this->checkIfBetter();
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Loop through training data and calculate error rate
   *
   */
  public function calculateCurrentErrorRate() {
    // Grab some stuff we need below
    $swarm          = $this->swarm;
    $network        = $swarm->network;
    $trainingDatas  = $swarm->trainingData;

    // Set network weights to particle position
    $this->setNetworkWeightsToSwarmPosition($network, $this->currentPosition);

    // Calculate current score
    $error = 0.0;
    foreach($trainingDatas as $trainingData) {
      $networkInputData = $trainingData[0];
      $expectedOutputs  = $trainingData[1];
      $networkOutputs   = $network->calculate($networkInputData);

      $innerError = CostFunctions\RootMeanSquaredError::calculate($networkOutputs, $expectedOutputs);
      $error += $innerError;
    }
    // Average sample data error for this round
    $error = $error / count($trainingDatas);

    // Update our current score
    $this->currentScore = $error;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Check if our current score is better than personal and global score and update
   *
   */
  public function checkIfBetter() {
    // Update our personal best stuff if we are better
   if($this->currentScore < $this->personalBestScore) {
      $this->personalBestScore     = $this->currentScore;
      $this->personalBestPosition  = $this->currentPosition;
    }

    // Update global best stuff if we are better
    if($this->currentScore < $this->swarm->globalBestScore) {
      $this->swarm->globalBestScore     = $this->currentScore;
      $this->swarm->globalBestPosition  = $this->currentPosition;
    }
  }

  // --------------------------------------------------------------------------------------------------------
  /**
   * Update the velocity
   *
   */
  public function updateVelocity() {
    // Update velocity
    // We need two random vectors of the same size as the position vectors
    $randomMatrix1 = [];
    $randomMatrix2 = [];
    $numDimentions  = count($this->swarm->network->allWeights);
    for($i=0;$i<$numDimentions;$i++) {
      $randomMatrix1[] = Misc::generateRandomWeight();
      $randomMatrix2[] = Misc::generateRandomWeight();
    }

    // velocity = (velocity * inertia) + (globalAcceleration * randomMatrix) * (personalBestPosition - currentPosition) + (socialAcceleration * randomMatrix2) * (globalBestPosition - currentPosition);
    $a = $this->multiplyVectorByScalar($this->velocity, $this->swarm->getConfig('inertia'));            // (velocity * inertia)
    $b = $this->multiplyVectorByScalar($randomMatrix1, $this->swarm->getConfig('globalAcceleration'));  // (globalAcceleration * randomMatrix)
    $c = $this->subtractVectorFromVector($this->personalBestPosition, $this->currentPosition);          // (personalBestPosition - currentPosition)
    $d = $this->multiplyVectorByScalar($randomMatrix2, $this->swarm->getConfig('socialAcceleration'));  // (socialAcceleration * randomMatrix2)
    $e = $this->subtractVectorFromVector($this->swarm->globalBestPosition, $this->currentPosition);     // (globalBestPosition - currentPosition)

    // velocity = $a + ($b * $c) + ($d * $e);
    $f = $this->multiplyVectorByVector($b, $c); // ($b * $c)
    $g = $this->multiplyVectorByVector($d, $e); // ($d * $e)

    // velocity = $a + $f + $g;
    $h = $this->addVectorToVector($a, $f); // $a + $f

    // velocity = $h + $g;
    $i = $this->addVectorToVector($h, $g); // $h + $g

    // Finally we have our velocity
    $this->velocity = $i;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Update the position
   *
   */
  public function updatePosition() {
    // position = position + velocity
    $this->currentPosition = $this->addVectorToVector($this->currentPosition, $this->velocity);
  }

  // --------------------------------------------------------------------------------------------------------
  /**
   * Clamp velocity values
   *
   */
  public function clampVelocityValues() {
    // Apply min max velocity limits
    foreach($this->velocity as &$pos) {
      $pos = min($pos, $this->swarm->getConfig('maxVelocityValue'));
      $pos = max($pos, $this->swarm->getConfig('minVelocityValue'));
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Clamp position values
   *
   */
  public function clampPositionValues() {
    // Apply min max position limits
    foreach($this->currentPosition as &$pos) {
      $pos = min($pos, $this->swarm->getConfig('maxPositionValue'));
      $pos = max($pos, $this->swarm->getConfig('minPositionValue'));
    }
  }

  // --------------------------------------------------------------------------------------------------------
  /**
   * Subtract scalar from vector
   *
   */
  public function subtractScalarFromVector($vector, $scalar) {
    $newVector = [];
    foreach($vector as $item) {
      $newVector[] = $item - $scalar;
    }
    return $newVector;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Add scalar to vector
   *
   */
  public function addScalarToVector($vector, $scalar) {
    $newVector = [];
    foreach($vector as $item) {
      $newVector[] = $item + $scalar;
    }
    return $newVector;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Multiply vector by scalar
   *
   */
  public function multiplyVectorByScalar($vector, $scalar) {
    $newVector = [];
    foreach($vector as $item) {
      $newVector[] = $item * $scalar;
    }
    return $newVector;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Subtract vector from vector
   *
   * $vector1 - $vector2
   *
   */
  public function subtractVectorFromVector($vector1, $vector2) {
    $newVector = [];
    foreach($vector1 as $itemIndex => $item) {
      $newVector[] = $item - $vector2[$itemIndex];
    }
    return $newVector;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Add vector to vector
   *
   * $vector1 + $vector2
   *
   */
  public function addVectorToVector($vector1, $vector2) {
    $newVector = [];
    foreach($vector1 as $itemIndex => $item) {
      $newVector[] = $item + $vector2[$itemIndex];
    }
    return $newVector;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Multiply vector by vector
   *
   * $vector1 * $vector2
   *
   */
  public function multiplyVectorByVector($vector1, $vector2) {
    $newVector = [];
    foreach($vector1 as $itemIndex => $item) {
      $newVector[] = $item * $vector2[$itemIndex];
    }
    return $newVector;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Sets the weights of a network to the values of a position matrix
   *
   */
  public function setNetworkWeightsToSwarmPosition($network, $position) {
    foreach($position as $positionIndex => $positionValue) {
      $network->allWeights[$positionIndex]->value = $positionValue;
    }
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Returns a particle position matrix from a network's weights
   *
   */
  public function getNetworkWeightsToSwarmPosition($network) {
    $position = [];
    foreach($network->allWeights as $weight) {
      $position[] = $weight->value;
    }
    return $position;
  }

}

?>
