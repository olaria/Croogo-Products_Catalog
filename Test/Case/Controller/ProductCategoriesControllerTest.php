<?php
App::uses('ProductCategoriesController', 'ProductsCatalog.Controller');
App::uses('CroogoControllerTestCase', 'Croogo.TestSuite');

class ProductCategoriesControllerTest extends CroogoControllerTestCase
{

    /**
     * fixtures
     */
    public $fixtures = array(
        'plugin.croogo.aco',
        'plugin.croogo.aro',
        'plugin.croogo.aros_aco',
        'plugin.blocks.block',
        'plugin.comments.comment',
        'plugin.contacts.contact',
        'plugin.translate.i18n',
        'plugin.settings.language',
        'plugin.menus.link',
        'plugin.menus.menu',
        'plugin.contacts.message',
        'plugin.nodes.node',
        'plugin.meta.meta',
        'plugin.taxonomy.nodes_taxonomy',
        'plugin.blocks.region',
        'plugin.users.role',
        'plugin.settings.setting',
        'plugin.taxonomy.taxonomy',
        'plugin.taxonomy.term',
        'plugin.taxonomy.type',
        'plugin.taxonomy.types_vocabulary',
        'plugin.users.user',
        'plugin.taxonomy.vocabulary',
        'plugin.products_catalog.product',
        'plugin.products_catalog.product_category',
    );

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        App::build(
            array(
                'View' => array(CakePlugin::path('ProductsCatalog') . 'View' . DS)
            ),
            App::APPEND
        );
        $this->ProductCategoriesController = $this->generate(
            'ProductsCatalog.ProductCategories',
            array(
                'methods' => array(
                    'redirect',
                ),
                'components' => array(
                    'Auth' => array('user'),
                    'Session',
                ),
            )
        );
        $this->ProductCategoriesController->Auth
            ->staticExpects($this->any())
            ->method('user')
            ->will($this->returnCallback(array($this, 'authUserCallback')));
    }

    /**
     * tearDown
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->ProductCategoriesController);
    }

    /**
     * testAdminIndex
     *
     * @return void
     */
    public function testAdminIndex()
    {
        $this->testAction('/admin/products_catalog/product_categories/index');
        $this->assertNotEmpty($this->vars['categories']);
    }

    /**
     * testAdminAdd
     *
     * @return void
     */
    public function testAdminAdd()
    {
        $this->expectFlashAndRedirect('The product category has been saved');
        $this->testAction(
            'admin/products_catalog/product_categories/add',
            array(
                'data' => array(
                    'ProductCategory' => array(
                        'parent_id' => 1,
                        'name' => 'Barcos',
                    ),
                ),
            )
        );
        $newCategory = $this->ProductCategoriesController->ProductCategory->findByName('Barcos');
        $this->assertEqual($newCategory['ProductCategory']['parent_id'], 1);
        $this->assertEqual($newCategory['ProductCategory']['name'], 'Barcos');
    }

    /**
     * testAdminAddFail
     *
     * @return void
     */
    public function testAdminAddFail()
    {
        $this->expectFlashAndRedirect('The product category could not be saved. Please, try again.',
            false,
            array('params' => array('class' => 'error'))
        );
        $this->testAction(
            'admin/products_catalog/product_categories/add',
            array(
                'data' => array(
                    'ProductCategory' => array(
                        'parent_id' => 1,
                        'name' => 'b',
                    ),
                ),
            )
        );
    }

    /**
     * testAdminEdit
     *
     * @return void
     */
    public function testAdminEdit()
    {
        $this->expectFlashAndRedirect('The product category has been saved');
        $this->testAction(
            '/admin/products_catalog/product_categories/edit/2',
            array(
                'data' => array(
                    'ProductCategory' => array(
                        'id' => 2,
                        'parent_id' => 4,
                        'name' => 'Flats',
                    ),
                ),
            )
        );
        $category = $this->ProductCategoriesController->ProductCategory->findByName('Flats');
        $this->assertEqual($category['ProductCategory']['parent_id'], 4);
        $this->assertEqual($category['ProductCategory']['name'], 'Flats');
    }

    /**
     * testAdminDelete
     *
     * @return void
     */
    public function testAdminDelete()
    {
        $this->expectFlashAndRedirect('Product category deleted');
        $this->testAction('admin/products_catalog/product_categories/delete/2');
        $hasAny = $this->ProductCategoriesController->ProductCategory->hasAny(
            array(
                'ProductCategory.parent_id' => 1,
                'ProductCategory.name' => 'Carros',
            )
        );
        $this->assertFalse($hasAny);
    }

    /**
     * testAdminMoveup
     *
     * @return void
     */
    public function testAdminMoveup()
    {
        $this->expectFlashAndRedirect('Moved up successfully');
        $this->testAction('admin/products_catalog/product_categories/moveup/3');
        $categories = $this->ProductCategoriesController->ProductCategory->find(
            'list',
            array(
                'fields' => array(
                    'id',
                    'name',
                ),
                'order' => 'ProductCategory.lft ASC',
            )
        );
        $expected = array(
            '1' => 'Veículos',
            '3' => 'Motos',
            '2' => 'Carros',
            '4' => 'Imóveis',
            '5' => 'Casas',
            '6' => 'Apartametos',
        );
        $this->assertEqual($categories, $expected);
    }

    /**
     * testAdminMovedown
     *
     * @return void
     */
    public function testAdminMovedown()
    {
        $this->expectFlashAndRedirect('Moved down successfully');
        $this->testAction('admin/products_catalog/product_categories/movedown/2');
        $categories = $this->ProductCategoriesController->ProductCategory->find(
            'list',
            array(
                'fields' => array(
                    'id',
                    'name',
                ),
                'order' => 'ProductCategory.lft ASC',
            )
        );
        $expected = array(
            '1' => 'Veículos',
            '3' => 'Motos',
            '2' => 'Carros',
            '4' => 'Imóveis',
            '5' => 'Casas',
            '6' => 'Apartametos',
        );
        $this->assertEqual($categories, $expected);
    }

}