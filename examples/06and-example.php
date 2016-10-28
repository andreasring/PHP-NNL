<?php

  include('../autoloader.php');

  $network = new NeuralNetworkLib\Networks\FeedForwardNetwork(2, [4], 1);

  $network->addTrainingData([1, 0], [1]);
  $network->addTrainingData([0, 1], [1]);
  $network->addTrainingData([1, 1], [0]);
  $network->addTrainingData([0, 0], [0]);

  $network->train();

  echo('1 and 0 should be 1 and is: '.$network->calculate([1, 0])[0]);
  echo('<br>');
  echo('0 and 1 should be 1 and is: '.$network->calculate([0, 1])[0]);
  echo('<br>');
  echo('1 and 1 should be 0 and is: '.$network->calculate([1, 1])[0]);
  echo('<br>');
  echo('0 and 0 should be 0 and is: '.$network->calculate([0, 0])[0]);
  echo('<br>');

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
