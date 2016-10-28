<?php

  // Register autoloader function
  spl_autoload_register('autoloader');

  /**
   * Autoloader function
   *
   */
  function autoloader($className) {
  	// Generate the file paths for all possible folders
    $baseDir = str_replace('autoloader.php', '', __FILE__);
  	$possibleInclusionPaths = array(
  		$baseDir.$className.'.class.php'
  	);

  	// Make sure we only have one class file found
  	$numInclusionFilesFound = 0;
  	$inclusionPath = '';
  	foreach($possibleInclusionPaths as $possibleInclusionPath) {
  		if(file_exists($possibleInclusionPath)) {
  			$numInclusionFilesFound++;
  			$inclusionPath = $possibleInclusionPath;
  		}
  		if($numInclusionFilesFound > 1) {
  			throw new Exception('Multiple inclusion paths found. Dont know which to load.');

  		}
  	}
  	if($numInclusionFilesFound != 1) {
  		throw new Exception('No class, model or exception file found ('.$className.').');
  	}

  	// Include the class
  	require_once($inclusionPath);
  }


?>
