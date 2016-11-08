<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Importing a network</title>

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
          <a class="navbar-brand" href="">Importing a network</a>
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
          <h1>Importing a network</h1>
          <hr>

          <p>You can import a network either from file or from a string.</p>
          <p>The underlying technique is the same: Using PHP's unserialize function.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Import network from file</h1>
          <hr>

          <p>Here is how you can import a network from file.</p>
          <script src="https://gist.github.com/andreasring/311e5da1db15badf50684fbe21fff6a9.js"></script>

        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Import network from string</h1>
          <hr>

          <p>Here is how you can import a network from a string.</p>
          <script src="https://gist.github.com/andreasring/b062b2d91098f10f4c96bf44b56a8a0c.js"></script>

        </div>
      </div>

    </div>

  </body>
</html>
