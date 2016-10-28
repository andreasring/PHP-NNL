<?php

  include('../autoloader.php');

  $network = new NeuralNetworkLib\FeedForwardNetwork(3, [4, 4], 2);

  $network->save('test.net');

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
