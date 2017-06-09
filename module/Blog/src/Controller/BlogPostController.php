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
    $post = $this->setPost($data);

    $this->blogService->save($post);
    return new JsonModel(['success']);
  }

  public function delete($id)
  {
    try {
      $this->blogService->delete($id);
      $message = 'success';
    } catch (\Exception $e) {
      $message = $e->getMessage();
    }

    return new JsonModel([$message]);
  }

  public function deleteList($data)
  {

  }

  public function get($id)
  {
    $post = $this->blogService->findById($id);

    $result = $this->postToArray($post);

    return new JsonModel($result);
  }

  public function getList()
  {
    $posts = $this->blogService->fetchAll();

    $results = [];
    foreach ($posts as $post) {
      $results[] = $this->postToArray($post);
    }

    return new JsonModel($results);
  }

  public function update($id, $data)
  {

  }

  protected function postToArray($post)
  {
      return [
        'id'        => $post->getId(),
        'title'     => $post->getTitle(),
        'slug'      => $post->getSlug(),
        'content'   => $post->getContent(),
        'created'   => $post->getCreated(),
        'category'  => $post->getCategory()->getName()
      ];
  }

  protected function setPost($data)
  {
    $post = new Post();
    $post->setTitle($data['title']);
    $post->setSlug($data['slug']);
    $post->setCreated(time());
    $post->setContent($data['content']);
    $category = new Category();
    $category->setId($data['category']);
    $post->setCategory($category);

    return $post;
  }
}
