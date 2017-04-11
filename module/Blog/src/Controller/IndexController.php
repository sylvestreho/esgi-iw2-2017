<?php

namespace Blog\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Form\Add;
use Blog\Form\Edit;
use Blog\InputFilter\AddPost;
use Blog\Entity\Post;

class IndexController extends AbstractActionController
{
  protected $blogService;

  public function __construct($blogService)
  {
    $this->blogService = $blogService;
  }

  public function indexAction()
  {
      $posts = $this->blogService->fetch(
          $this->params()->fromRoute('page')
      );

      $variables = [
        'posts' => $posts
      ];

      return new ViewModel($variables);
  }

  public function addAction()
  {
    $form = new Add();

    $variables = [
      'form' => $form
    ];

    if ($this->request->isPost()) { // if form is submitted
        $blogPost = new Post();
        $form->bind($blogPost);

        $form->setInputFilter(new AddPost());

        $data = $this->request->getPost(); // key value array
        $form->setData($data);

        if ($form->isValid()) {
          $this->blogService->save($blogPost);

          return $this->redirect()->toRoute('blog_home');
        }
    }

    return new ViewModel($variables);
  }

  public function viewPostAction()
  {
    $post = $this->blogService->find(
      $this->params()->fromRoute('categorySlug'),
      $this->params()->fromRoute('postSlug')
    );

    if (is_null($post)) {
      $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
    }

    return new ViewModel(['post' => $post]);
  }

  public function deleteAction()
  {
    $this->blogService->delete($this->params()->fromRoute('postId'));
    $this->redirect()->toRoute('blog_home');
  }

  public function editAction()
  {
    $form = new Edit();
    $variables = ['form' => $form];

    if ($this->request->isPost()) { // FORM HAS BEEN SUBMITTED
      $blogPost = new Post();
      $form->bind($blogPost);
      $form->setInputFilter(new AddPost());
      $data = $this->request->getPost();
      $form->setData($data); // KEY VALUE ARRAY
      if ($form->isValid()) {
        $this->blogService->update($blogPost);
        return $this->redirect()->toRoute('blog_home');
      }
    } else {  // VIEWING POST
        $post = $this->blogService
          ->findById(
            $this->params()->fromRoute('postId')
        );
        if (is_null($post)) {
          $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        } else {
          $form->bind($post);
          $form->get('slug')->setValue($post->getSlug());
          $form->get('id')->setValue($post->getId());
          $form->get('category_id')->setValue($post->getCategory()->getId());
        }
      }
    return new ViewModel($variables);
  }
}
