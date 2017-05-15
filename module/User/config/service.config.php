<?php

namespace User;

return [
  'factories' => [
    'User\InputFilter\AddUser' => function(\Zend\ServiceManager\ServiceManager $sm) {
      $addUserFilter = new \User\InputFilter\AddUser($sm->get('Zend\Db\Adapter\Adapter'));
    }
  ]
];
