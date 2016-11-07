<?php

  use \NeuralNetworkLib\Components as Components;

  // Test the layer construct
  createLayer();

  //

  // Creates a layer with all necessary parameters
  function createLayer() {
    $network        = NULL;
    $previousLayer  = NULL;
    $nextLayer      = NULL;
    $name           = 'Test layer';
    $position       = 1;
    $neuronCount    = 10;

    try {
      $layer = new Components\Layer($network, $previousLayer, $nextLayer, $name, $position, $neuronCount);

      // Verify layer data
      // While hack
      while(1) {

        if($layer->network !== $network) {
          echoError('Layer network is not set correctly!');
          echoError('Layer construct failed!');
          break;
        }

        if($layer->previousLayer !== $previousLayer) {
          echoError('Layer previous layer is not set correctly!');
          echoError('Layer construct failed!');
          break;
        }

        if($layer->nextLayer !== $nextLayer) {
          echoError('Layer next layer is not set correctly!');
          echoError('Layer construct failed!');
          break;
        }

        if($layer->name !== $name) {
          echoError('Layer name is not set correctly!');
          echoError('Layer construct failed!');
          break;
        }

        if($layer->position !== $position) {
          echoError('Layer position is not set correctly!');
          echoError('Layer construct failed!');
          break;
        }

        if($layer->neuronCount !== $neuronCount) {
          echoError('Layer neuron count is not set correctly!');
          echoError('Layer construct failed!');
          break;
        }

        if(count($layer->neurons) !== $neuronCount) {
          echoError('Layer neurons was not created correctly (count of neurouns is not correct)!');
          echoError('Layer construct failed!');
          break;
        }

        echoSuccess('Layer construct success!');
        break;
      }
    } catch (Exception $e) {
      echoError('Layer construct failed!');
    }


  }

?>
