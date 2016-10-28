<?php

  include('autoloader.php');

  $network = new NeuralNetworkLib\FeedForwardNetwork(3, [5, 3, 2], 2);

  //print_r($network);
  var_dump($network);

?>
