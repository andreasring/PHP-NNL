<?php

  // Include the autoloader
  include('../../autoloader.php');


  // Create a simple network
  $networkConfig = [
    'structure' => [
      'input' => 2,
      'hidden' => [2],
      'output' => 1
    ]
  ];
  $network = new NeuralNetworkLib\Networks\FeedForwardNetwork($networkConfig);


  // Create a particle swarm trainer
  $particleSwarmConfig = [
    'recordErrorRate'     => TRUE,
    'trainingRounds'      => 25,
    'maxStopError'        => 0.01,
    'numParticles'        => 1000,
    'globalAcceleration'  => 2,
    'socialAcceleration'  => 2,
    'inertia'             => 1,
    'inertiaDampingEnabled' => TRUE,
    'inertiaDamping'      => 0.99,
    'clampPositionValues' => TRUE,
    'minPositionValue'    => -15,
    'maxPositionValue'    => 15,
    'clampVelocityValues' => TRUE,
    'maxVelocityValue'    => 2,
    'minVelocityValue'    => -2,
    'autoClercKennedy2002Values' => TRUE
  ];
  $particleSwarmTrainer = new NeuralNetworkLib\TrainingAlgorithms\ParticleSwarm($network, $particleSwarmConfig);


  // Add training data (XOR)
  $particleSwarmTrainer->addTrainingData([1, 1], [0]);
  $particleSwarmTrainer->addTrainingData([0, 0], [0]);
  $particleSwarmTrainer->addTrainingData([1, 0], [1]);
  $particleSwarmTrainer->addTrainingData([0, 1], [1]);


  // Train
  $particleSwarmTrainer->train();


  // Fetch the recorded error rate
  $recordedErrorRate = $particleSwarmTrainer->recordedErrorValues;

  /************************************************************************************************************************/

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Training a network: Particle Swarm</title>

    <!-- jQuery 3.1.1 -->
    <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>

    <!-- jQuery UI 1.12.1 -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Bootstrap 3.3.7 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Vis.js 4.16.1 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.css">

    <!-- amcharts 3.20.17 -->
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

    <style>

      body {
        padding-top: 60px;
        padding-bottom: 60px;
      }

      #errorRateGraph {
        width: 100%;
        height: 500px;
        border: solid #ddd 1px;
      }

    </style>
  </head>
  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="">Training a network: Particle Swarm</a>
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
          <h1>Training a network using Particle Swarm</h1>
          <hr>

          <p>Particle swarm info</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Code</h1>
          <hr>

          <p>Some text</p>
          <script src="https://gist.github.com/andreasring/139c508a7ed2bcf97af52f7739086c14.js"></script>

        </div>
      </div>


      <div class="row">
        <div class="col-lg-12">
          <h2>Visualizing error rate</h1>
          <hr>

          <div id="errorRateGraph"></div>

          <script type="text/javascript">

            var chart = AmCharts.makeChart("errorRateGraph", {
                "type": "serial",
                "theme": "light",
                "mouseWheelZoomEnabled":true,
                "valueAxes": [{
                    "id": "v1",
                    "ignoreAxisWidth": true
                }],
                "graphs": [{
                    "id": "g1",
                    "bullet": "round",
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "bulletSize": 5,
                    "hideBulletsCount": 100,
                    "lineThickness": 2,
                    "useLineColorForBulletBorder": true,
                    "valueField": "y",
                    "balloonText": "<span style='font-size: 18px;'>[[value]]</span>"
                }],
                "chartScrollbar": {
                    "graph": "g1",
                    "oppositeAxis": false,
                    "offset": 30,
                    "scrollbarHeight": 80,
                    "backgroundAlpha": 0,
                    "selectedBackgroundAlpha": 0.1,
                    "selectedBackgroundColor": "#888888",
                    "graphFillAlpha": 0,
                    "graphLineAlpha": 1,
                    "graphLineColor": "#67b7dc",
                    "selectedGraphFillAlpha": 0,
                    "selectedGraphLineAlpha": 1,
                    "selectedGraphLineColor": "#67b7dc",
                    "autoGridCount": true,
                    "color":"#AAAAAA"
                },
                "chartCursor": {
                    "pan": true,
                    "valueLineEnabled": true,
                    "valueLineBalloonEnabled": true,
                    "cursorAlpha": 1,
                    "cursorColor": "#258cbb",
                    "limitToGraph": "g1",
                    "valueLineAlpha": 0.2,
                    "valueZoomable": false
                },
                "valueScrollbar":{
                  "oppositeAxis": false,
                  "offset": 70,
                  "scrollbarHeight": 10
                },
                "categoryField": "x",
                "categoryAxis": {
                    "parseDates": false,
                    "dashLength": 1,
                    "minorGridEnabled": true
                },
                "dataProvider": [
                  <?php
                    foreach($recordedErrorRate as $errorIndex => $errorRate) {
                      echo('{x: '.$errorIndex.', y: '.$errorRate.'},');
                    }
                  ?>
                ]
            });

          </script>
        </div>
      </div>


    </div>

  </body>
</html>
