<?php

  include('../autoloader.php');

  $network = new NeuralNetworkLib\Networks\FeedForwardNetwork(2, [4], 1);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="libs/sigma.js/sigma.min.js"></script>

    <style>
      #graphContainer {
        height: 500px;
        width: 500px;
        border: solid #707070 1px;
      }
    </style>
  </head>
  <body>

    <div id="graphContainer"></div>

    <script>

      var jsonData = <?php echo $network->exportForSigma(); ?>;

      var s = new sigma({
        graph: jsonData,
        container: 'graphContainer'
      });

    </script>
  </body>
</html>
