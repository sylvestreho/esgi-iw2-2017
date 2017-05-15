<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Form\Add;
use User\Entity\User;
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

    if ($this->request->isPost()) {
      $user = new User();
      $form->bind($user);
      $form->setData($this->request->getPost());
    }

    return new ViewModel($variables);
  }
}
