<?php

  include('../autoloader.php');

  // This file should include all the induvidual test files
  include('layer.php');
  include('neuron.php');


  // Testing helper functions
  function echoError($msg) {
    echo('<p style="color: red;">'.$msg.'</p>');
  }

  function echoSuccess($msg) {
    echo('<p style="color: green;">'.$msg.'</p>');
  }

?>
