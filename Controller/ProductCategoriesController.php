<?php

App::uses('ProductsCatalogAppController', 'ProductsCatalog.Controller');

/**
 * Croogo Products Catalog Extension
 * Product Categories Controller
 *
 * @category Controller
 * @package  Croogo.ProductsCatalog
 * @version  0.1
 * @author   Helder Santana <helder@olaria.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.oalria.me
 */
class ProductCategoriesController extends ProductsCatalogAppController
{
    public $uses = array('ProductsCatalog.ProductCategory');

    /**
     * Admin index
     *
     * @return void
     */
    public function admin_index()
    {
        $this->set('title_for_layout', __d('croogo', 'Product Categories'));

        $this->ProductCategory->recursive = 0;
        $this->paginate['ProductCategory']['order'] = 'ProductCategory.lft ASC';
        $this->set('categories', $this->paginate());
    }

    /**
     * Admin add
     *
     * @return void
     * @access public
     */
    public function admin_add()
    {
        $this->set('title_for_layout', __d('croogo', 'Add Product Category'));

        if (!empty($this->request->data)) {
            $data = $this->request->data;
            $data['ProductCategory']['status'] = 1;
            $data['ProductCategory']['created'] = date('Y-m-d H:i:s');
            $data['ProductCategory']['updated'] = date('Y-m-d H:i:s');
            $this->ProductCategory->create();
            if ($this->ProductCategory->save($data)) {
                $this->Session->setFlash(
                    __d('croogo', 'The product category has been saved'),
                    'default',
                    array('class' => 'success')
                );
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(
                    __d('croogo', 'The product category could not be saved. Please, try again.'),
                    'default',
                    array('class' => 'error')
                );
            }
        }

        $parents = array(null => '') + $this->ProductCategory->generateTreeList(null, null, null, " -> ");
        $this->set(compact('parents'));
    }

    /**
     * Admin edit
     *
     * @param integer $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        $this->set('title_for_layout', __d('croogo', 'Edit Product Category'));

        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__d('croogo', 'Invalid product category'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->request->data)) {
            $data = $this->request->data;
            $data['ProductCategory']['updated'] = date('Y-m-d H:i:s');
            $data['ProductCategory']['status'] = 1;

            $this->ProductCategory->recursive = -1;
            $category = $this->ProductCategory->read(null, $id);
            foreach ($category['ProductCategory'] as $k => $v) {
                if (!empty($data['ProductCategory'][$k])) {
                    $category['ProductCategory'][$k] = $data['ProductCategory'][$k];
                }
            }

            if ($this->ProductCategory->save($category)) {
                $this->Session->setFlash(
                    __d('croogo', 'The product category has been saved'),
                    'default',
                    array('class' => 'success')
                );
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(
                    __d('croogo', 'The product category could not be saved. Please, try again.'),
                    'default',
                    array('class' => 'error')
                );
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->ProductCategory->read(null, $id);
        }

        $parents = array(null => '') + $this->ProductCategory->generateTreeList(null, null, null, " - ");
        $this->set(compact('parents'));
    }

    /**
     * Admin delete
     *
     * @param integer $id
     * @return void
     */
    public function admin_delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(
                __d('croogo', 'Invalid id for product category'),
                'default',
                array('class' => 'error')
            );
            $this->redirect(array('action' => 'index'));
        }
        if ($this->ProductCategory->delete($id)) {
            $this->Session->setFlash(__d('croogo', 'Product category deleted'), 'default', array('class' => 'success'));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * Admin moveup
     *
     * @param integer $id
     * @param integer $step
     * @return void
     */
    public function admin_moveup($id, $step = 1)
    {
        if ($this->ProductCategory->moveUp($id, $step)) {
            $this->Session->setFlash(__d('croogo', 'Moved up successfully'), 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__d('croogo', 'Could not move up'), 'default', array('class' => 'error'));
        }

        $this->redirect(array('action' => 'index'));
    }

    /**
     * Admin moveup
     *
     * @param integer $id
     * @param integer $step
     * @return void
     */
    public function admin_movedown($id, $step = 1)
    {
        if ($this->ProductCategory->moveDown($id, $step)) {
            $this->Session->setFlash(__d('croogo', 'Moved down successfully'), 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__d('croogo', 'Could not move down'), 'default', array('class' => 'error'));
        }

        $this->redirect(array('action' => 'index'));
    }

}