<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Create simple feedforward network</title>

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
          <a class="navbar-brand" href="">Create feedforward network</a>
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
          <h1>Creating a simple feedforward network</h1>
          <hr>

          <p>This is how you include the library correctly and create a simple feedforward network.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Including the library</h1>
          <hr>

          <p>PHP-NNL follows the PSR-4 standard for autoloading.</p>
          <p>This means you can simply use the autoloader.php in the root folder together with the NeuralNetworkLib folder and you are good to go.</p>
          <br>
          <p>PSR-4: Autoloader documentation: <a href="http://www.php-fig.org/psr/psr-4/">http://www.php-fig.org/psr/psr-4/</a>
          <p>Autoloader code taken from <a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md">https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md</a></p>
          <br>
          <script src="https://gist.github.com/andreasring/8978fcbe8e2f034bd273410f0ef041f4.js"></script>

          <br>

          <p>Alternativelly you can include all the class files you need manually and it should still work:</p>
          <br>
          <script src="https://gist.github.com/andreasring/243a9c4e0e79b495a48a7c0844f34495.js"></script>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Creating the network</h1>
          <hr>

          <p>Creating a simple feedforward network is very easy.</p>
          <p>Here we create a network with one neuron in the input layer, one hidden layer with three neurons and one neuron in the output layer.</p>
          <br>
          <script src="https://gist.github.com/andreasring/c1f04445804ff5c7282d92117ade9616.js"></script>

          <br>

          <h3>Network graph</h3>
          <hr>

          <p>The network we just created looks like this:</p>
          <img src="https://github.com/andreasring/PHP-NNL/blob/master/examples/gfx/simpleFeedforwardNetwork.png?raw=true">
        </div>
      </div>


      <div class="row">
        <div class="col-lg-12">
          <h2>Complete code</h1>
          <hr>

          <script src="https://gist.github.com/andreasring/28c0b600241293a67a3afbb231b63bba.js"></script>
        </div>
      </div>

    </div>

  </body>
</html>
