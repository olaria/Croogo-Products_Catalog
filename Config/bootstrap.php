<?php

CroogoNav::add('settings.children.products_catalog', array(
        'title' => __d('croogo', 'Products Catalog'),
        'url' => array(
            'plugin' => 'settings',
            'admin' => true,
            'controller' => 'settings',
            'action' => 'prefix',
            'ProductsCatalog'
        ),
    )
);

/**
 * Add Products Catalgo to admin menu
 */
CroogoNav::add(
    'Products',
    array(
        'title' => __d('croogo', 'Products Catalog'),
        'url' => array(
            'admin' => true,
            'plugin' => 'products_catalog',
            'controller' => 'products',
            'action' => 'index',
        ),
        'weight' => 50,
        'children' => array(
            'products' => array(
                'title' => __d('croogo', 'Products'),
                'url' => array(
                    'admin' => true,
                    'plugin' => 'products_catalog',
                    'controller' => 'products',
                    'action' => 'index',
                ),
            ),
            'categories' => array(
                'title' => __d('croogo', 'Categories'),
                'url' => array(
                    'admin' => true,
                    'plugin' => 'products_catalog',
                    'controller' => 'product_categories',
                    'action' => 'index',
                ),
            ),
        ),
    )
);