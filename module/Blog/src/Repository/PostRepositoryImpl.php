<?php

namespace Blog\Repository;

use Blog\Repository\PostRepository;
use Zend\Db\Adapter\AdapterAwareTrait;
use Blog\Entity\Post;

class PostRepositoryImpl implements PostRepository
{
  use AdapterAwareTrait;

  public function save(Post $post)
  {

  }

  public function fetchAll()
  {

  }

  public function fetch($page)
  {

  }

  /**
   * @return Post|null
   */
  public function find($categorySlug, $postSlug)
  {

  }

  /**
   * @return Post|null
   */
  public function findById($postId)
  {

  }

  public function update(Post $post)
  {

  }

  public function delete($postId)
  {

  }
}
