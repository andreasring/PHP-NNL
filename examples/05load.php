<?php

  include('../autoloader.php');

  $network = NeuralNetworkLib\FeedForwardNetwork::load('test.net');

  var_dump($network->calculate([0, 1, 0]));
  
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
