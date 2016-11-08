<?php
namespace NeuralNetworkLib\Helpers;

/**
 * Helper function collection for various neural network operations
 *
 */
class Misc {

  // --------------------------------------------------------------------------------------------------------
  /**
   * Generates a random weight
   *
   */
  public static function generateRandomWeight() {
    //return mt_rand(-100000000, 100000000)/100000000;
    //return mt_rand(-10000000000000, 10000000000000)/10000000000000;
    return floatval(mt_rand() / mt_getrandmax());

    //return rand(-1000, 1000)/1000;
  }


  // --------------------------------------------------------------------------------------------------------
  /**
   * Converts a object into an array recursively
   *
   */
  public static function object2array($object, $recur = 0) {
    if($recur > 7) {
      return NULL;
    }
    $recur++;
    $array = [];
    foreach($object as $key => $value) {
      if(is_array($value)) {
        $value = self::object2array($value, $recur);
      } elseif(is_object($value)) {
        $value = self::object2array($value, $recur);
      } elseif(is_resource($value)) {
        $value = NULL;
      }

      $array[$key] = $value;
    }
    return $array;
  }

}
