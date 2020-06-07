<?php

return [
  [
    'method' => 'get',
    'name' => 'index',
    'uri' => '/index/users',
    'controller' => 'index',
    'action' => 'users'
  ],
  [
    'method' => 'get',
    'name' => 'words',
    'uri' => '/words',
    'controller' => 'words',
    'action' => 'index'
  ],
  [
    'method' => 'get',
    'name' => 'word',
    'uri' => '/word/{[0-9]+}',
    'controller' => 'words',
    'action' => 'word'
  ]
];