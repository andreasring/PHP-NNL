<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Exporting a network</title>

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
          <a class="navbar-brand" href="">Exporting a network</a>
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
          <h1>Exporting a network</h1>
          <hr>

          <p>You can export a network either to file or as a string.</p>
          <p>The underlying technique is the same: Using PHP's serialize function.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Export network to file</h1>
          <hr>

          <p>Here is how you can export a network to file.</p>
          <script src="https://gist.github.com/andreasring/30056fdde7f76ef7f6c01eebabab20d0.js"></script>

        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Export network to string</h1>
          <hr>

          <p>Here is how you can export a network to a string.</p>
          <script src="https://gist.github.com/andreasring/d2aec374e1c36847c7cbed85d8369c42.js"></script>

        </div>
      </div>

    </div>

  </body>
</html>
