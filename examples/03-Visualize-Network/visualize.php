<?php

  include('../../autoloader.php');

  $network = new NeuralNetworkLib\Networks\FeedForwardNetwork(2, [4], 1);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>03 Visualize Network</title>

    <!-- Sigma.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sigma.js/1.1.0/sigma.min.js"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>

      body {
        padding-top: 60px;
        padding-bottom: 60px;
      }

      #graphContainer {
        height: 750px;
        width: 100%;
        border: solid #999 1px;
      }

    </style>

  </head>
  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="">Visualizing Network</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="">Example</a></li>
          </ul>
        </div>
      </div>
    </nav>


    <!-- Main content -->
    <div class="container">

      <div class="row">
        <div class="col-lg-12">
          <h1>Visualizing a neural network</h1>
          <hr>

          <p>Here is an example of how to visualize a neural network. This will work for any type of network you create.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Graph</h2>
          <hr>

          <div id="graphContainer"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Code</h2>
          <hr>

          <div class="row">
            <div class="col-lg-12">
              <h3>PHP</h3>
              <hr>

              <pre>
// Create a network to visualize
$network = new NeuralNetworkLib\Networks\FeedForwardNetwork(2, [4], 1);</pre>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <h3>HTML</h3>
              <hr>

              <pre>
&lt;-- The div to place the graph into --&gt;
&lt;div id=&quot;graphContainer&quot;&gt;&lt;/div&gt;

&lt;-- Sigma.JS - Visualize network --&gt;
&lt;script&gt;

  var jsonData = &lt;?php echo $network-&gt;exportForSigma(); ?&gt;;

  var s = new sigma({
    graph: jsonData,
    container: &#39;graphContainer&#39;
  });

&lt;/script&gt;</pre>
            </div>
          </div>
        </div>
      </div>

    </div>


    <!-- Graph code -->
    <script>

      var jsonData = <?php echo $network->exportForSigma(); ?>;

      var s = new sigma({
        graph: jsonData,
        container: 'graphContainer'
      });

    </script>

  </body>
</html>
