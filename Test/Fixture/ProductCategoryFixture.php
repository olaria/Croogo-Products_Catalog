<?php

/**
 * Croogo Product Extension
 * ProductCategory Fixture
 *
 * @category Fixture
 * @package  Croogo.ProductsCatalog
 * @version  0.1
 * @author   Helder Santana <helder@olaria.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.oalria.me
 */
class ProductCategoryFixture extends CroogoTestFixture
{

    public $name = 'ProductCategory';

    public $fields = array(
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

    public $records = array(
        array(
            'id' => '1',
            'parent_id' => null,
            'lft' => '1',
            'rght' => '6',
            'name' => 'Veículos',
            'status' => '1',
            'updated' => '2013-05-13 12:58:46',
            'created' => '2013-05-13 12:58:46'
        ),
        array(
            'id' => '2',
            'parent_id' => '1',
            'lft' => '2',
            'rght' => '3',
            'name' => 'Carros',
            'status' => '1',
            'updated' => '2013-05-13 12:58:55',
            'created' => '2013-05-13 12:58:55'
        ),
        array(
            'id' => '3',
            'parent_id' => '1',
            'lft' => '4',
            'rght' => '5',
            'name' => 'Motos',
            'status' => '1',
            'updated' => '2013-05-13 12:59:02',
            'created' => '2013-05-13 12:59:02'
        ),
        array(
            'id' => '4',
            'parent_id' => null,
            'lft' => '7',
            'rght' => '12',
            'name' => 'Imóveis',
            'status' => '1',
            'updated' => '2013-05-13 12:59:12',
            'created' => '2013-05-13 12:59:12'
        ),
        array(
            'id' => '5',
            'parent_id' => '4',
            'lft' => '8',
            'rght' => '9',
            'name' => 'Casas',
            'status' => '1',
            'updated' => '2013-05-13 12:59:19',
            'created' => '2013-05-13 12:59:19'
        ),
        array(
            'id' => '6',
            'parent_id' => '4',
            'lft' => '10',
            'rght' => '11',
            'name' => 'Apartametos',
            'status' => '1',
            'updated' => '2013-05-13 12:59:27',
            'created' => '2013-05-13 12:59:27'
        )
    );
}