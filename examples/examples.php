<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>04 Visualize Network Interactive</title>

    <!-- jQuery 3.1.1 -->
    <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>

    <!-- jQuery UI 1.12.1 -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Vis.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.css">

    <!-- Bootstrap 3.3.7 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Vue.JS 2.0.3 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.3/vue.js"></script>

    <style>

      body {
        padding-top: 60px;
        padding-bottom: 60px;
      }

      #graphContainer {
        height: 500px;
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
          <a class="navbar-brand" href="">PHP Neural Network Library - Examples</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="">Examples</a></li>
          </ul>
        </div>
      </div>
    </nav>



    <!-- Main content -->
    <div class="container" id="vueApp">

      <div class="row">
        <div class="col-lg-12">
          <h1>A collection of examples for PHP Neural Network Library</h1>
          <hr>

          <p>Here is the collection of official examples for the PHP Neural Network Library.</p>
        </div>
      </div>

      <br>

      <div class="row">
        <div class="col-lg-12">
          <h2>Creating a simple feedforward network</h2>
          <hr>

          <p>This will show you how to include the library correctly and create a simple feedforward network.</p>
          <p class="text-right"><a class="btn btn-primary btn-md" href="01-create-feedforward-network/">View example!</a></p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Creating a simple recurrent network</h2>
          <hr>

          <p>This will show you how to include the library correctly and create a simple recurrent network.</p>
          <p class="text-right"><a class="btn btn-primary btn-md" href="01-create-feedforward-network/">View example!</a></p>
        </div>
      </div>

    </div>

  </body>
</html>
