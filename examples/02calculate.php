<?php

  include('../autoloader.php');

  $network = new NeuralNetworkLib\FeedForwardNetwork(2, [4], 1);

  $start = time();
  for ($i=0; $i < 1000; $i++) {
    $value = $network->calculate([0, 1]);
  }
  $end = time();

  echo("Time: ".($end-$start));
  var_dump($value);

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
