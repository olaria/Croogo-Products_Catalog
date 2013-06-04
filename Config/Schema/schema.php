<?php

/**
 * Croogo Products Catalog Extension
 *
 * @category Schema
 * @package  Croogo.ProductsCatalog
 * @version  0.1
 * @author   Helder Santana <helder@olaria.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.oalria.me
 */
class ProductsCatalogSchema extends CakeSchema
{
    /**
     * Definitions to table categories
     *
     * @var array
     */
    public $product_categories = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'lft' => array('type' => 'integer', 'null' => true, 'default' => null),
        'rght' => array('type' => 'integer', 'null' => true, 'default' => null),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'default' => null,
            'collate' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ),
        'status' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
        'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
    );

    /**
     * Definitions to table products
     *
     * @var array
     */
    public $products = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'category_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'default' => null,
            'collate' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ),
        'description' => array(
            'type' => 'text',
            'null' => true,
            'default' => null,
            'collate' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ),
        'status' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
        'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
    );

    /**
     * Definitions to table product_images
     *
     * @var array
     */
    public $product_images = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'product_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'default' => null,
            'collate' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ),
        'size' => array(
            'type' => 'string',
            'null' => true,
            'default' => null,
            'collate' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ),
        'type' => array(
            'type' => 'string',
            'null' => true,
            'default' => null,
            'collate' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ),
        'path' => array(
            'type' => 'string',
            'null' => true,
            'default' => null,
            'collate' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
    );
}