<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Calculate a network output</title>

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
          <a class="navbar-brand" href="">Calculating a network output</a>
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
          <h1>Calculating a network output</h1>
          <hr>

          <p>This will show you how to "run" the network and get an output for a given input.</p>
          <p>The word "calculate" is used because that is the name of the function that performs the network algorithms and gives an output for a given input.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Code</h1>
          <hr>

          <p>Here is how you can calculate a network output from a given input.</p>
          <p>Note that this will result in a random output because the weights are initialized randomly, and we have not traied the network to do anything specific yet.</p>
          <script src="https://gist.github.com/andreasring/b4a97c42e0e04644953252c66ee3264e.js"></script>

        </div>
      </div>

    </div>

  </body>
</html>
