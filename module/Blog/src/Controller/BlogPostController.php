<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Blog\Entity\Post;
use Blog\Entity\Category;
use Zend\Cache\StorageFactory;

class BlogPostController extends AbstractRestfulController
{
  protected $blogService;
  protected $cache;

  public function __construct($blogService)
  {
    $this->blogService = $blogService;
    $this->cache = StorageFactory::factory([
      'adapter' => [
        'name'  => 'filesystem', // could be apc, memcache etc....
        'options' => [
          'namespace' => 'api_posts'
        ]
      ],
      'plugins' => [
        'exception_handler' => [
          'throw_exceptions'  => false
        ],
        'Serializer'
      ]
    ]);
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
    $cacheKey = 'list';
    $posts = $this->cache->getItem($cacheKey);

    if (is_array($posts) && count($posts)) {
      return new JsonModel($posts);
    }

    $posts = $this->blogService->fetchAll();

    $results = [];
    foreach ($posts as $post) {
      $results[] = $this->postToArray($post);
    }
    $this->cache->setItem($cacheKey, $results);

    return new JsonModel($results);
  }

  public function update($id, $data)
  {
    $post = $this->setPost($data);
    $post->setId($id);

    $this->blogService->update($post);
    return new JsonModel(['success']);
  }

  public function patch($id, $data)
  {
    try {
      $post = $this->blogService->findById($id);
      if (!$post) {
        throw new \Exception(sprintf("Post %s not found", $id));
      }

      foreach ($data as $key => $value) {
        $setter = 'set' . ucfirst($key);
        if ($key == 'category') {
          $category = new Category();
          $category->setId($value);
          $post->setCategory($category);
        } elseif (method_exists($post, $setter)) {
          $post->$setter($value);
        }
      }
      $this->blogService->update($post);
    } catch (\Exception $e) {
      return new JsonModel([$e->getMessage()]);
    }

    return new JsonModel(["success"]);
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
