<?php

namespace Blog;

return [
  // routes
  'router' => [
    'routes' => [
      'blog_home' => [
        'type' => 'Literal',
        'options' => [
          'route' => '/blog',
          'defaults' => [
            'controller'  => 'Blog\Controller\Index',
            'action'      => 'index'
          ],
        ],
        'may_terminate' => true,
        'child_routes' => [
            'paged' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/page/:page' ,   // /blog/page/:page
                    'constraints' => [ 'page' => '[0-9]+' ],
                    'defaults' => [
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'index'
                    ]
                ]
            ]
        ]
      ],
      'blog_add' => [
        'type' => 'Literal',
        'options' => [
          'route' => '/blog/post/add',
          'defaults' => [
            'controller'  => 'Blog\Controller\Index',
            'action'      => 'add'
          ]
        ]
      ],
      'edit_post' => [
        'type' => 'Segment',
        'options' => [
          'route' => '/blog/post/edit/:postId',
          'constraints' => [
            'postId' => '[0-9]+'
          ],
          'defaults' => [
            'controller'  => 'Blog\Controller\Index',
            'action'      => 'edit'
          ]
        ]
      ],
      'delete_post' => [
        'type' => 'Segment',
        'options' => [
          'route' => '/blog/post/delete/:postId',
          'constraints' => [
            'postId' => '[0-9]+'
          ],
          'defaults' => [
            'controller'  => 'Blog\Controller\Index',
            'action'      => 'delete'
          ]
        ]
      ],
      'display_post' => [
        'type' => 'Segment',
        'options' => [
          'route' => '/blog/posts/:categorySlug/:postSlug',
          'contraints' => [
            'categorySlug'  => '[a-zA-Z0-9-]+',
            'postSlug'      => '[a-zA-Z0-9-]+',
          ],
          'defaults' => [
            'controller'  => 'Blog\Controller\Index',
            'action'      => 'viewPost'
          ]
        ]
      ]
    ]
  ],

  // controllers
  'controllers' => [
    'factories' => [
      'Blog\Controller\Index' => 'Blog\Controller\IndexControllerFactory'
    ]
  ],

  // view manager
  'view_manager' => [
    'template_path_stack'   => [
      __DIR__ . '/../view'
    ]
  ]
];
