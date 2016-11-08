<?php

  // Include the autoloader
  include('../../autoloader.php');


  // Create a simple network
  $networkConfig = [
    'structure' => [
      'input' => 2,
      'hidden' => [3],
      'output' => 1
    ]
  ];
  $network = new NeuralNetworkLib\Networks\FeedForwardNetwork($networkConfig);


  // Create a gradient decent trainer
  $gradientDecentConfig = [
    'trainingRounds' => 1,
    'learningRate' => 0.1
  ];
  $gradientDecentTrainer = new NeuralNetworkLib\TrainingAlgorithms\GradientDecent($network, $gradientDecentConfig);


  // Add training data (XOR)
  $gradientDecentTrainer->addTrainingData([1, 1], [0]);
  $gradientDecentTrainer->addTrainingData([0, 0], [0]);
  $gradientDecentTrainer->addTrainingData([1, 0], [1]);
  $gradientDecentTrainer->addTrainingData([0, 1], [1]);


  // Train
  $gradientDecentTrainer->train();

  /************************************************************************************************************************/

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Training a network: Gradient Decent</title>

    <!-- jQuery 3.1.1 -->
    <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>

    <!-- jQuery UI 1.12.1 -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Bootstrap 3.3.7 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>

      body {
        padding-top: 60px;
        padding-bottom: 60px;
      }

    </style>
  </head>
  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="">Training a network: Gradient Decent</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="">Example</a></li>
          </ul>
        </div>
      </div>
    </nav>



    <!-- Main content -->
    <div class="container" id="vueApp">

      <div class="row">
        <div class="col-lg-12">
          <h1>Training a network using Gradient Decent</h1>
          <hr>

          <p>Gradient decent info</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Code</h1>
          <hr>

          <p>Some text</p>
          <script src="https://gist.github.com/andreasring/a72061915b55af8f8d5605e01dcb44c4.js"></script>

        </div>
      </div>

    </div>

  </body>
</html>
