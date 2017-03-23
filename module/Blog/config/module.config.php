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
