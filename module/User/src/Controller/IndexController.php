<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Form\Add;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
  public function __construct()
  {

  }

  public function addAction()
  {
    $form = new Add();

    $variables = [
      'form' => $form
    ];

    return new ViewModel($variables);
  }
}
