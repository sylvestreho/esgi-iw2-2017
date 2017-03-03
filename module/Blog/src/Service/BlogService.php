<?php

namespace Blog\Service;

use Blog\Entity\Post;

interface BlogService
{
  public function save(Post $post);

  public function fetchAll();

  public function fetch($page);

  /**
   * @return Post|null
   */
  public function find($categorySlug, $postSlug);

  /**
   * @return Post|null
   */
  public function findById($postId);

  public function update(Post $post);

  public function delete($postId);
}
