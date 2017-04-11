<?php

namespace Blog\Repository;

use Blog\Entity\Hydrator\CategoryHydrator;
use Blog\Entity\Hydrator\PostHydrator;
use Zend\Hydrator\Aggregate\AggregateHydrator;
use Zend\Db\ResultSet\HydratingResultSet;
use Blog\Repository\PostRepository;
use Zend\Db\Adapter\AdapterAwareTrait;
use Blog\Entity\Post;

class PostRepositoryImpl implements PostRepository
{
  use AdapterAwareTrait;

  public function save(Post $post)
  {
      try {
          $this->adapter
            ->getDriver()
            ->getConnection()
            ->beginTransaction();

      $sql = new \Zend\Db\Sql\Sql($this->adapter);
      $insert = $sql->insert()
        ->values([
          'title' => $post->getTitle(),
          'slug'  => $post->getSlug(),
          'content' => $post->getContent(),
          'category_id' => $post->getCategory()->getId(),
          'created' => time()
        ])
        ->into('post');
     $statement = $sql->prepareStatementForSqlObject($insert);
     $statement->execute();
     $this->adapter->getDriver()
      ->getConnection()
      ->commit();
   } catch (\Exception $e) {
        $this->adapter->getDriver()
          ->getConnection()->rollback();
   }
  }

  public function fetchAll()
  {
      $sql = new \Zend\Db\Sql\Sql($this->adapter);
      $select = $sql->select();
      $select->columns([
          'id',
          'title',
          'slug',
          'content',
          'created'
      ])->from([
        'p' => 'post'
      ])->join(
          ['c' => 'category'], // table name
          'c.id = p.category_id',
          ['category_id' => 'id', 'name', 'category_slug' => 'slug'] // column alias
      );

      $statement = $sql->prepareStatementForSqlObject($select);
      $result = $statement->execute();

      $hydrator = new AggregateHydrator();
      $hydrator->add(new PostHydrator());
      $hydrator->add(new CategoryHydrator());
      $resultSet = new HydratingResultSet($hydrator, new Post());
      $resultSet->initialize($result);

      $posts = [];
      foreach($resultSet as $post) {
          /**
           * @var \Blog\Entity\Post $post
           */
          $posts[] = $post;
      }
      return $posts;
  }

  public function fetch($page)
  {
      $sql = new \Zend\Db\Sql\Sql($this->adapter);
      $select = $sql->select();
      $select->columns([
          'id',
          'title',
          'slug',
          'content',
          'created'
      ])->from([
        'p' => 'post'
      ])->join(
          ['c' => 'category'], // table name
          'c.id = p.category_id',
          ['category_id' => 'id', 'name', 'category_slug' => 'slug'] // column alias
      );

      $hydrator = new AggregateHydrator();
      $hydrator->add(new PostHydrator());
      $hydrator->add(new CategoryHydrator());
      $resultSet = new HydratingResultSet($hydrator, new Post());

        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect(
            $select,
            $this->adapter,
            $resultSet
          );
          $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
          $paginator->setCurrentPageNumber($page);
          $paginator->setItemCountPerPage(3);

          return $paginator;
  }

  /**
   * @return Post|null
   */
  public function find($categorySlug, $postSlug)
  {
      $sql = new \Zend\Db\Sql\Sql($this->adapter);
      $select = $sql->select();
      $select->columns([
        'id',
        'title',
        'slug',
        'content',
        'created'
      ])->from(
        ['p' => 'post']
      )->join(
        ['c' => 'category'], // TABLE NAME
        'c.id = p.category_id', // JOIN CONDITION
        ['category_id' => 'id', 'name', 'category_slug' => 'slug']
      )->where(
        [ 'c.slug' => $categorySlug, 'p.slug' => $postSlug]
      );

      $statement = $sql->prepareStatementForSqlObject($select);
      $results = $statement->execute();

      $hydrator = new AggregateHydrator();
      $hydrator->add(new PostHydrator());
      $hydrator->add(new CategoryHydrator());

      $resultSet = new HydratingResultSet($hydrator, new Post());
      $resultSet->initialize($results);

      return ($resultSet->count() ? $resultSet->current() : null);
  }

  /**
   * @return Post|null
   */
  public function findById($postId)
  {
    $sql = new \Zend\Db\Sql\Sql($this->adapter);
    $select = $sql->select();
    $select->columns([
      'id',
      'title',
      'slug',
      'content',
      'created'
    ])->from(
      ['p' => 'post']
    )->join(
      ['c' => 'category'], // TABLE NAME
      'c.id = p.category_id', // JOIN CONDITION
      ['category_id' => 'id', 'name', 'category_slug' => 'slug']
    )->where(
      [ 'p.id' => $postId ]
    );

    $statement = $sql->prepareStatementForSqlObject($select);
    $results = $statement->execute();

    $hydrator = new AggregateHydrator();
    $hydrator->add(new PostHydrator());
    $hydrator->add(new CategoryHydrator());

    $resultSet = new HydratingResultSet($hydrator, new Post());
    $resultSet->initialize($results);

    return ($resultSet->count() ? $resultSet->current() : null);
  }

  public function update(Post $post)
  {
    $sql = new \Zend\Db\Sql\Sql($this->adapter);
    $update = $sql->update('post')
      ->set([
        'title'       => $post->getTitle(),
        'slug'        => $post->getSlug(),
        'content'     => $post->getContent(),
        'category_id' => $post->getCategory()->getId()
      ])->where([
        'id' => $post->getId()
      ]);

      $statement = $sql->prepareStatementForSqlObject($update);
      $statement->execute();
  }

  public function delete($postId)
  {
      $sql = new \Zend\Db\Sql\Sql($this->adapter);
      $delete = $sql->delete()
        ->from('post')
        ->where([
          'id' => $postId
        ]);

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
  }
}
