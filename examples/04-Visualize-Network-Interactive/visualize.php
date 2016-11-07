<?php

  // Include the autoloader
  include('../../autoloader.php');

  // Create a simple network
  $network = new NeuralNetworkLib\Networks\FeedForwardNetwork(2, [3], 1);

  $network->addTrainingData([1, 1], [1]);
  $network->addTrainingData([0, 0], [1]);
  $network->addTrainingData([1, 0], [0]);
  $network->addTrainingData([0, 1], [0]);

  $network->train();

  // Do a calculation with it to push data throughout the network so we get something interesting to look at
  $network->calculate([1, 1]);

  /************************************************************************************************************************/

?>
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
          <a class="navbar-brand" href="">Visualizing Network - Interactive</a>
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
          <h1>Visualizing a neural network and making it interactive</h1>
          <hr>

          <p>Here is an example of how to visualize a neural network. This will work for any type of network you create.</p>
          <p>You can also see a lot of table data about the network.</p>
          <p>Additionally you can click on neurons and synapses to see their inner workings.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <h2>Graph</h2>
          <hr>

          <div id="graphContainer"></div>
        </div>
        <div class="col-lg-6">
          <h2>Data</h2>
          <hr>

          <div id="tabs">
            <ul>
              <li><a href="#neuronTab">Neuron</a></li>
              <li><a href="#synapseTab">Synapse</a></li>
            </ul>

            <div id="neuronTab">
              <!-- Neuron info Vue component -->
              <neuron></neuron>
            </div>

            <div id="synapseTab">
              <!-- Synapse info Vue component -->
              <synapse></synapse>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <h2>Data</h2>
          <hr>

          <div id="tabs2">
            <ul>
              <li><a href="#networkTab">Network</a></li>
            </ul>

            <div id="networkTab">
              <!-- Network info Vue component -->
              <network></network>
            </div>

          </div>

        </div>
      </div>

    </div>



    <!-- Vue templates -->
    <div style="display: none;">

      <!-- Network component template -->
      <template id="networkTemplate">
        <div class="row">
          <div class="col-lg-12">

            <div class="row">
              <div class="col-lg-12">
                <h3>Overview</h3>
                <hr>

                <table class="table table-striped table-hover">
                  <tr>
                    <th width="250">Total number of layers</th>
                    <td>{{ totalNumLayers }}</td>
                  </tr>
                  <tr>
                    <th>Total number of neurons</th>
                    <td>{{ totalNumNeurons }}</td>
                  </tr>
                  <tr>
                    <th>Total number of neurons</th>
                    <td>{{ totalNumNeurons - totalNumBiasNeurons }} (Not including bias neurons)</td>
                  </tr>
                  <tr>
                    <th>Total number of bias neurons</th>
                    <td>{{ totalNumBiasNeurons }}</td>
                  </tr>
                  <tr>
                    <th>Total number of synapses</th>
                    <td>{{ totalNumSynapses }}</td>
                  </tr>
                  <tr>
                    <th>Total number of synapses</th>
                    <td>{{ totalNumSynapses - totalNumBiasSynapses }} (Not including synapses connected to bias neurons)</td>
                  </tr>
                  <tr>
                    <th>Total number of bias synapses</th>
                    <td>{{ totalNumBiasSynapses }}</td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <h3>Input layer</h3>
                <hr>

                <table class="table table-striped table-hover">
                  <tr>
                    <th width="250">Number of neurons</th>
                    <td>{{ inputLayerNumNeurons }}</td>
                  </tr>
                  <tr>
                    <th>Number of neurons</th>
                    <td>{{ inputLayerNumNeurons - inputLayerNumBiasNeurons }} (Not including bias neuron)</td>
                  </tr>
                  <tr>
                    <th>Number of bias neurons</th>
                    <td>{{ inputLayerNumBiasNeurons }}</td>
                  </tr>
                  <tr>
                    <th>Latest input values</th>
                    <td>{{ latestInputValues }}</td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <h3>Hidden layer</h3>
                <hr>

                <div v-for="hiddenLayer in hiddenLayers">
                  <h4>{{ hiddenLayer.name }}</h4>
                  <hr>

                  <table class="table table-striped table-hover">
                    <tr>
                      <th width="250">Number of neurons</th>
                      <td>{{ hiddenLayer.inputLayerNumNeurons }}</td>
                    </tr>
                    <tr>
                      <th>Number of neurons</th>
                      <td>{{ hiddenLayer.inputLayerNumNeurons - hiddenLayer.inputLayerNumBiasNeurons }} (Not including bias neuron)</td>
                    </tr>
                    <tr>
                      <th>Number of bias neurons</th>
                      <td>{{ hiddenLayer.inputLayerNumBiasNeurons }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <h3>Output layer</h3>
                <hr>

                <table class="table table-striped table-hover">
                  <tr>
                    <th width="250">Number of neurons</th>
                    <td>{{ outputLayerNumNeurons }}</td>
                  </tr>
                  <tr>
                    <th>Number of neurons</th>
                    <td>{{ outputLayerNumNeurons - outputLayerNumBiasNeurons }} (Not including bias neuron)</td>
                  </tr>
                  <tr>
                    <th>Number of bias neurons</th>
                    <td>{{ outputLayerNumBiasNeurons }}</td>
                  </tr>
                  <tr>
                    <th>Latest output values</th>
                    <td>{{ latestOutputValues }}</td>
                  </tr>
                </table>
              </div>
            </div>

          </div>
        </div>
      </template>


      <!-- Neuron component template -->
      <template id="neuronTemplate">
        <div>
          <div v-if="dataReady">
            <h3>Neuron</h3>
            <hr>
            <table class="table table-hover table-striped">
              <tr>
                <th width="200">ID</th>
                <td>{{ currentNeuron.ID }}</td>
              </tr>
              <tr>
                <th>Name</th>
                <td>{{ currentNeuron.name }}</td>
              </tr>
              <tr>
                <th>xPos</th>
                <td>{{ currentNeuron.xPos }}</td>
              </tr>
              <tr>
                <th>yPos</th>
                <td>{{ currentNeuron.yPos }}</td>
              </tr>
              <tr>
                <th>Activation Function</th>
                <td>{{ currentNeuron.activationFunction.replace('\\NeuralNetworkLib\\ActivationFunctions\\', '') }}</td>
              </tr>
              <tr>
                <th>Is bias neuron</th>
                <td>{{ currentNeuron.isBiasNeuron }}</td>
              </tr>
              <tr>
                <th>Latest output value</th>
                <td>{{ currentNeuron.latestOutputValue }}</td>
              </tr>
              <tr>
                <th>Previous output value</th>
                <td>{{ currentNeuron.previousOutputValue }}</td>
              </tr>
            </table>
          </div>
          <div v-else>
            <p>Click a neuron to display data</p>
          </div>
        </div>
      </template>


      <!-- Synapse component template -->
      <template id="synapseTemplate">
        <div>
          <div v-if="dataReady">
            <h3>Synapse</h3>
            <hr>
            <table class="table table-hover table-striped">
              <tr>
                <th width="100">ID</th>
                <td>{{ currentSynapse.ID }}</td>
              </tr>
              <tr>
                <th>Weight</th>
                <td>{{ currentSynapse.weight }}</td>
              </tr>
              <tr>
                <th>Value</th>
                <td>{{ currentSynapse.value }}</td>
              </tr>
            </table>

            <h3>Input neuron</h3>
            <hr>
            <table class="table table-hover table-striped" v-if="currentSynapse.inputNeuron != null">
              <tr>
                <th width="200">ID</th>
                <td>{{ currentSynapse.inputNeuron.ID }}</td>
              </tr>
              <tr>
                <th>Name</th>
                <td>{{ currentSynapse.inputNeuron.name }}</td>
              </tr>
              <tr>
                <th>xPos</th>
                <td>{{ currentSynapse.inputNeuron.xPos }}</td>
              </tr>
              <tr>
                <th>yPos</th>
                <td>{{ currentSynapse.inputNeuron.yPos }}</td>
              </tr>
              <tr>
                <th>Activation Function</th>
                <td>{{ currentSynapse.inputNeuron.activationFunction.replace('\\NeuralNetworkLib\\ActivationFunctions\\', '') }}</td>
              </tr>
              <tr>
                <th>Is bias neuron</th>
                <td>{{ currentSynapse.inputNeuron.isBiasNeuron }}</td>
              </tr>
              <tr>
                <th>Latest output value</th>
                <td>{{ currentSynapse.inputNeuron.latestOutputValue }}</td>
              </tr>
            </table>

            <h3>Output neuron</h3>
            <hr>
            <table class="table table-hover table-striped" v-if="currentSynapse.outputNeuron != null">
              <tr>
                <th width="200">ID</th>
                <td>{{ currentSynapse.outputNeuron.ID }}</td>
              </tr>
              <tr>
                <th>Name</th>
                <td>{{ currentSynapse.outputNeuron.name }}</td>
              </tr>
              <tr>
                <th>xPos</th>
                <td>{{ currentSynapse.outputNeuron.xPos }}</td>
              </tr>
              <tr>
                <th>yPos</th>
                <td>{{ currentSynapse.outputNeuron.yPos }}</td>
              </tr>
              <tr>
                <th>Activation Function</th>
                <td>{{ currentSynapse.outputNeuron.activationFunction.replace('\\NeuralNetworkLib\\ActivationFunctions\\', '') }}</td>
              </tr>
              <tr>
                <th>Is bias neuron</th>
                <td>{{ currentSynapse.outputNeuron.isBiasNeuron }}</td>
              </tr>
              <tr>
                <th>Latest output value</th>
                <td>{{ currentSynapse.outputNeuron.latestOutputValue }}</td>
              </tr>
            </table>
          </div>
          <div v-else>
            <p>Click a synapse to display data</p>
          </div>
        </div>
      </template>

    </div>



    <!-- Javascript -->
    <script>

      // Make stuff easely avalible globally
      var dataStore = {
        currentNode:  null,
        currentEdge:  null,
        network:      null,
        graph:        null,
        graphData:    null
      };


      // Self-invoking function on page load
      $(function() {

        // Set and initialize what we need
        dataStore.network     = <?php echo $network->jsonExport(); ?>;
        dataStore.graphData   = createGraphData(dataStore.network);


        // Vue components
        // The network info tab
        Vue.component('network', {
          template: '#networkTemplate',
          data: function() {
            return {
              network:              dataStore.network,
              totalNumLayers:       0,
              totalNumNeurons:      0,
              totalNumBiasNeurons:  0,
              totalNumSynapses:     0,
              totalNumBiasSynapses: 0,

              inputLayerNumNeurons: 0,
              inputLayerNumBiasNeurons:   0,
              latestInputValues:          0,

              hiddenLayers:               [],

              outputLayerNumNeurons:      0,
              outputLayerNumBiasNeurons:  0,
              latestOutputValues:         0,
            }
          },
          mounted: function() {
            // Overview
            this.totalNumLayers             = this.network.hiddenLayer.length + 2;
            this.totalNumNeurons            = allNeurons(this.network).length;
            this.totalNumBiasNeurons        = allBiasNeurons(this.network).length;
            this.totalNumSynapses           = allSynapses(this.network).length;
            this.totalNumBiasSynapses       = allBiasSynapses(this.network).length;


            // Input layer
            this.inputLayerNumNeurons       = this.network.inputLayer.neuronCount + 1; // This should be changed somehow to include the bias neuron(s)
            this.inputLayerNumBiasNeurons   = 1; // This should probably be changed to something dynamic
            var latestInputValues = [];
            this.network.inputLayer.neurons.forEach(function (neuron) {
              if(neuron.isBiasNeuron) {
                return;
              }
              latestInputValues.push(neuron.inputSynapses[0].value);
            });
            this.latestInputValues = latestInputValues;


            // Hidden layer
            var hiddenLayers = [];
            this.network.hiddenLayer.forEach(function (hiddenLayer) {
              var aHiddenLayer = {};

              aHiddenLayer.ID         = hiddenLayer.ID;
              aHiddenLayer.name       = hiddenLayer.name;
              aHiddenLayer.inputLayerNumNeurons       = hiddenLayer.neuronCount + 1; // This should be changed somehow to include the bias neuron(s)
              aHiddenLayer.inputLayerNumBiasNeurons   = 1; // This should probably be changed to something dynamic

              hiddenLayers.push(aHiddenLayer);
            });
            this.hiddenLayers = hiddenLayers;


            // Output layer
            this.outputLayerNumNeurons      = this.network.outputLayer.neuronCount + 1; // This should be changed somehow to include the bias neuron(s)
            this.outputLayerNumBiasNeurons  = 1; // This should probably be changed to something dynamic
            var latestOutputValues = [];
            this.network.outputLayer.neurons.forEach(function (neuron) {
              if(neuron.isBiasNeuron) {
                return;
              }
              latestOutputValues.push(neuron.latestOutputValue);
            })
            this.latestOutputValues         = latestOutputValues;
          }
        });


        // The neuron info tab component
        Vue.component('neuron', {
          template: '#neuronTemplate',
          data: function() {
            return {
              dataReady: false,
              dataStore: dataStore,
              currentNeuron: null
            }
          },
          watch: {
            'dataStore.currentNode': function () {
              this.currentNeuron = dataStore.currentNode;
              this.dataReady = true;
            }
          }
        });


        // The synapse info tab component
        Vue.component('synapse', {
          template: '#synapseTemplate',
          data: function() {
            return {
              dataReady: false,
              dataStore: dataStore,
              currentSynapse: null
            }
          },
          watch: {
            'dataStore.currentEdge': function () {
              this.currentSynapse = dataStore.currentEdge;
              this.dataReady = true;
            }
          }
        });


        // The vue app
        new Vue({
          el: '#vueApp'
        });




        // create a network
        var container = document.getElementById('graphContainer');

        var data = dataStore.graphData;

        var options = {
          nodes: {
            shape: 'circle',
            size: 20,
            font: {
                size: 16,
                color: 'black',
            },
            borderWidth: 2,
            color: {
              background: '#606060',
              border: '#202020',
              highlight: {
                background: '#909090',
                border: '#505050'
              },
              hover: {
                background: '#808080',
                border: '#404040'
              }
            }
          },
          edges: {
            font: {
              size: 16,
              color: 'black',
              align: 'top'
            },
            arrows: 'to',
            width: 2,
            color: {
              color: '#909090',
              highlight: 'blue',
              hover: 'red'
            },
          },
          groups: {
            inputLayer: {
                color: { background:'red' }
            },
            hiddenLayer: {
                color: { background:'green' }
            },
            outputLayer: {
                color: { background:'blue' }
            }
          },
          layout: {
              hierarchical: {
                  direction: 'LR',
                  sortMethod: 'directed',
                  parentCentralization: true,
                  blockShifting: true,
                  levelSeparation: 250,
                  nodeSpacing: 100,
              },
          },
          interaction: {
            hover: true,
            dragNodes: false
          },
          physics: true
        };

        // Create the network graph
        var network = new vis.Network(container, data, options);

        // Add the click functionallity to nodes and edges
        network.on("click", function (params) {
          // No edges or nodes was clicked, we dont care
          if(params.nodes.length == 0 && params.edges.length == 0) {
            return;
          }

          // Clicked a node?
          if(params.nodes.length == 1) {

            dataStore.currentNode = findNeuronByID(params.nodes[0]);
            $("#tabs").tabs({ active: 0 });

          } else {
            // clicked a edge?
            if(params.edges.length == 1) {

              dataStore.currentEdge = findSynapseByID(params.edges[0]);
              $("#tabs").tabs({ active: 1 });

            }
          }
        });


        // Initialize the jQuery UI tabs
        $("#tabs").tabs();
        $("#tabs2").tabs();

      });



      /********************* Functions *********************/

      // Creates nodes and edges data for the graph
      function createGraphData(network) {
        var nodes = allNeurons(network);
        var edges = allSynapses(network);

        var graphData = {
          'nodes': [],
          'edges': []
        };

        nodes.forEach(function (node) {
          node.yPos -= (node.layer.neuronCount/2);

          var innerNode = {};
          innerNode['id']    = node.ID;
          innerNode['label'] = node.name;
          innerNode['group'] = node.layer.name;
          graphData.nodes.push(innerNode);
        });

        edges.forEach(function (edge) {
          if(edge.inputNeuron == null || edge.outputNeuron == null) {
            return;
          }
          var innerEdge = {};
          innerEdge['id']     = edge.ID;
          innerEdge['label']  = 'w: ' + edge.weight.toString(); //edge.value.toString() + ' * ' + edge.weight.toString();
          innerEdge['from']   = edge.inputNeuron.ID;
          innerEdge['to']     = edge.outputNeuron.ID;
          graphData.edges.push(innerEdge);
        });

        return graphData;
      }


      // Returns all the neurons in a network
      function allNeurons(network) {
        var neurons = [];
        network.inputLayer.neurons.forEach(function (neuron) {
          neurons.push(neuron);
        });
        network.hiddenLayer.forEach(function (hiddenLayer) {
          hiddenLayer.neurons.forEach(function (neuron) {
            neurons.push(neuron);
          });
        });
        network.outputLayer.neurons.forEach(function (neuron) {
          neurons.push(neuron);
        });
        return neurons;
      }


      // Returns all the synapses in a network
      function allSynapses(network) {
        var neurons = allNeurons(network);
        var synapses = [];
        neurons.forEach(function (neuron) {
          neuron.inputSynapses.forEach(function (synapse) {
            var found = false;
            synapses.forEach(function (innerSynapse) {
              if(innerSynapse.ID == synapse.ID) {
                found = true;
              }
            });
            if(!found) {
              // Only add the ones that are fully connected
              if(synapse.inputNeuron != null && synapse.outputNeuron != null) {
                synapses.push(synapse);
              }
            }
          });
          neuron.outputSynapses.forEach(function (synapse) {
            var found = false;
            synapses.forEach(function (innerSynapse) {
              if(innerSynapse.ID == synapse.ID) {
                found = true;
              }
            });
            if(!found) {
              // Only add the ones that are fully connected
              if(synapse.inputNeuron != null && synapse.outputNeuron != null) {
                synapses.push(synapse);
              }
            }
          });
        });
        return synapses;
      }


      // Returns a node/neuron from a given id
      function findNeuronByID(ID) {
        var neurons = allNeurons(dataStore.network);
        var foundNeuron = false;
        neurons.forEach(function (neuron) {
          if(neuron.ID == ID) {
            foundNeuron = neuron;
          }
        });
        return foundNeuron;
      }


      // Returns a edge/synapse from a given id
      function findSynapseByID(ID) {
        var synapses = allSynapses(dataStore.network);
        var foundSynapse = false;
        synapses.forEach(function (synapse) {
          if(synapse.ID == ID) {
            foundSynapse = synapse;
          }
        });
        return foundSynapse;
      }


      // Returns all bias neurons
      function allBiasNeurons(network) {
        var neurons = allNeurons(network);
        var biasNeurons = [];
        neurons.forEach(function (neuron) {
          if(neuron.isBiasNeuron) {
            biasNeurons.push(neuron);
          }
        });
        return biasNeurons;
      }


      // Returns all synapses that is connected to a bias neuron
      function allBiasSynapses(network) {
        var synapses = allSynapses(network);
        var biasSynapses = [];
        synapses.forEach(function (synapse) {
          if(synapse.inputNeuron != null && synapse.outputNeuron != null) {
            if(synapse.inputNeuron.isBiasNeuron || synapse.outputNeuron.isBiasNeuron) {
              biasSynapses.push(synapse);
            }
          }
        });
        return biasSynapses;
      }


    </script>


  </body>
</html>
