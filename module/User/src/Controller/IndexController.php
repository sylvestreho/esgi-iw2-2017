<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Form\Add;
use User\Entity\User;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
  protected $addUserFilter;

  public function __construct( $addUserFilter)
  {
    $this->addUserFilter = $addUserFilter;
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
      $form->setInputFilter($this->addUserFilter);
      $form->setData($this->request->getPost());

      if ($form->isValid()) {
        // @todo save user in database
      }
    }

    return new ViewModel($variables);
  }
}
