<?php

$loader = new Loader();

$loader->setClasses([
  'app/UseCases/UseCase',
  'app/UseCases/UseCaseFactory',
  'app/UseCases/GetWordsData/UseCase'
]);

//$loader->register();