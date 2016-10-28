<?php

  include('../autoloader.php');

  $network = new NeuralNetworkLib\FeedForwardNetwork(2, [4], 1);

  $network->addTrainingData([0, 1], [0]);
  $network->addTrainingData([1, 1], [1]);
  $network->addTrainingData([1, 0], [0]);
  $network->addTrainingData([0, 0], [0]);

  $network->train();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="libs/sigma.js/sigma.min.js"></script>
  </head>
  <body>



  </body>
</html>
