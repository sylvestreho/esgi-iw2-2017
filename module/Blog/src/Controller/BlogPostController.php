<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Blog\Entity\Post;
use Blog\Entity\Category;

class BlogPostController extends AbstractRestfulController
{
  protected $blogService;

  public function __construct($blogService)
  {
    $this->blogService = $blogService;
  }

  public function create($data)
  {

  }

  public function delete($id)
  {

  }

  public function deleteList($data)
  {

  }

  public function get($id)
  {
    $post = $this->blogService->findById($id);

    $result = [
      'id'        => $post->getId(),
      'title'     => $post->getTitle(),
      'slug'      => $post->getSlug(),
      'content'   => $post->getContent(),
      'created'   => $post->getCreated(),
      'category'  => $post->getCategory()->getName()
    ];

    return new JsonModel($result);
  }

  public function getList()
  {

  }

  public function update($id, $data)
  {

  }
}
