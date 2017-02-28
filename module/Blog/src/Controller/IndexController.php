<?php

namespace Blog\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Form\Add;
use Blog\InputFilter\AddPost;

class IndexController extends AbstractActionController
{
  protected $container;

  public function __constructor($container)
  {
    $this->container = $container;
  }

  public function indexAction()
  {
    $variables = [

    ];

    // @todo fetch blog posts

    return new ViewModel($variables);
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
