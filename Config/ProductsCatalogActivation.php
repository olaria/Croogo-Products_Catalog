<?php
/**
 * Croogo Products Catalog Extension
 *
 * @category Config
 * @package  Croogo.ProductsCatalog
 * @version  0.1
 * @author   Helder Santana <helder@olaria.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.oalria.me
 */

class ProductsCatalogActivation
{
    /**
     * Settings to ProductsCatalog extension
     *
     * @var array
     */
    protected $defaultSettings = array(
        array(
            'key' => 'ProductsCatalog.maxImageWidth',
            'value' => '800',
            'title' => 'Max image width',
            'description' => '',
            'input_type' => 'text',
            'editable' => '1',
            'params' => '',
        ),
        array(
            'key' => 'ProductsCatalog.maxImageHeight',
            'value' => '600',
            'title' => 'Max image height',
            'description' => '',
            'input_type' => 'text',
            'editable' => '1',
            'params' => '',
        ),
        array(
            'key' => 'ProductsCatalog.thumbImageWidth',
            'value' => '100',
            'title' => 'Thumb image width',
            'description' => '',
            'input_type' => 'text',
            'editable' => '1',
            'params' => '',
        ),
        array(
            'key' => 'ProductsCatalog.thumbImageHeight',
            'value' => '100',
            'title' => 'Thumb image height',
            'description' => '',
            'input_type' => 'text',
            'editable' => '1',
            'params' => '',
        ),
    );

    /**
     * onActivate will be called if this returns true
     *
     * @param object $controller Controller
     * @return boolean
     */
    public function beforeActivation($controller)
    {
        return true;
    }

    /**
     * onActivation of plugin
     *
     * @param Object $controller
     */
    public function onActivation($controller)
    {
        $controller->Croogo->addAco('ProductsCatalog');
        $controller->Croogo->addAco('ProductsCatalog/Products');
        $controller->Croogo->addAco('ProductsCatalog/Products/admin_index');
        $controller->Croogo->addAco('ProductsCatalog/Products/admin_add');
        $controller->Croogo->addAco('ProductsCatalog/Products/admin_edit');
        $controller->Croogo->addAco('ProductsCatalog/Products/admin_delete');
        $controller->Croogo->addAco('ProductsCatalog/Categories');
        $controller->Croogo->addAco('ProductsCatalog/Categories/admin_index');
        $controller->Croogo->addAco('ProductsCatalog/Categories/admin_add');
        $controller->Croogo->addAco('ProductsCatalog/Categories/admin_edit');
        $controller->Croogo->addAco('ProductsCatalog/Categories/admin_delete');

        App::import('Core', 'File');
        App::import('Model', 'CakeSchema', false);
        App::import('Model', 'ConnectionManager');

        $db = ConnectionManager::getDataSource('default');
        if (!$db->isConnected()) {
            $this->Session->setFlash(__('Could not connect to database.', true));
        } else {
            CakePlugin::load('ProductsCatalog');
            $schema =& new CakeSchema(array('plugin' => 'ProductsCatalog', 'name' => 'ProductsCatalog'));
            $schema = $schema->load();
            foreach ($schema->tables as $table => $fields) {
                $create = $db->createSchema($schema, $table);
                $db->execute($create);
            }

            $this->Setting = ClassRegistry::init('Settings.Setting');
            foreach ($this->defaultSettings as $setting) {
                $this->Setting->create();
                $this->Setting->save($setting);
            }
        }
    }

    /**
     * onDeactivate will be called if this returns true
     *
     * @param object $controller Controller
     * @return boolean
     */
    public function beforeDeactivation($controller)
    {
        return true;
    }

    /**
     * onDeactivation of plugin
     *
     * @param Object $controller
     */
    public function onDeactivation($controller)
    {
        App::import('Core', 'File');
        App::import('Model', 'CakeSchema', false);
        App::import('Model', 'ConnectionManager');

        $db = ConnectionManager::getDataSource('default');
        if (!$db->isConnected()) {
            $this->Session->setFlash(__('Could not connect to database.', true));
        } else {
            CakePlugin::load('Faq');
            $schema =& new CakeSchema(array('plugin' => 'ProductsCatalog', 'name' => 'ProductsCatalog'));
            $schema = $schema->load();
            foreach ($schema->tables as $table => $fields) {
                $drop = $db->dropSchema($schema, $table);
                $db->execute($drop);
            }
        }

        $controller->Croogo->removeAco('ProductsCatalog');
        $this->Setting = ClassRegistry::init('Settings.Setting');
        $this->Setting->deleteAll(array(
            'Setting.key LIKE' => 'ProductsCatalog.%',
        ));
    }
}