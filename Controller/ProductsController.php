<?php

App::uses('AppController', 'Controller');

/**
 * Croogo Products Catalog Extension
 * Products Controller
 *
 * @category Controller
 * @package  Croogo.ProductsCatalog
 * @version  0.1
 * @author   Helder Santana <helder@olaria.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.oalria.me
 */
class ProductsController extends ProductsCatalogAppController
{
    public $uses = array('ProductsCatalog.Product', 'ProductsCatalog.ProductCategory', 'ProductsCatalog.ProductImage');
    public $components = array('ProductsCatalog.ImageResizer');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Security->unlockedActions = array('admin_upload_image');
    }

    /**
     * Admin index
     *
     * @return void
     */
    public function admin_index()
    {
        $this->set('title_for_layout', __d('croogo', 'Products'));

        $this->Product->recursive = 0;
        $this->paginate['Product']['order'] = 'Product.updated DESC';
        $this->set('products', $this->paginate());
    }

    /**
     * Admin add
     *
     * @return void
     * @access public
     */
    public function admin_add()
    {
        $this->set('title_for_layout', __d('croogo', 'Add Product'));

        if (!empty($this->request->data)) {
            $data = $this->request->data;
            $data['Product']['status'] = 1;
            $data['Product']['created'] = date('Y-m-d H:i:s');
            $data['Product']['updated'] = date('Y-m-d H:i:s');
            $this->Product->create();
            if ($this->Product->save($data)) {
                $this->Session->setFlash(
                    __d('croogo', 'The product has been saved'),
                    'default',
                    array('class' => 'success')
                );
                $this->redirect(array('action' => 'edit', $this->Product->id));
            } else {
                $this->Session->setFlash(
                    __d('croogo', 'The product could not be saved. Please, try again.'),
                    'default',
                    array('class' => 'error')
                );
            }
        }

        $categories = $this->Product->ProductCategory->generateTreeList(null, null, null, " -> ");
        $this->set(compact('categories'));
    }

    /**
     * Admin edit
     *
     * @param integer $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        $this->set('title_for_layout', __d('croogo', 'Edit Product'));

        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__d('croogo', 'Invalid product'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->request->data)) {
            $data = $this->request->data;
            $data['Product']['updated'] = date('Y-m-d H:i:s');
            $data['Product']['status'] = 1;

            $this->Product->recursive = -1;
            $product = $this->Product->read(null, $id);
            foreach ($product['Product'] as $k => $v) {
                if (!empty($data['Product'][$k])) {
                    $product['Product'][$k] = $data['Product'][$k];
                }
            }

            if ($this->Product->save($product)) {
                $this->Session->setFlash(
                    __d('croogo', 'The product has been saved'),
                    'default',
                    array('class' => 'success')
                );
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(
                    __d('croogo', 'The product could not be saved. Please, try again.'),
                    'default',
                    array('class' => 'error')
                );
            }
        }

        if (empty($this->request->data)) {
            $this->request->data = $this->Product->read(null, $id);
        }

        $categories = $this->Product->ProductCategory->generateTreeList(null, null, null, " -> ");
        $this->set(compact('categories'));
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
            $this->Session->setFlash(__d('croogo', 'Invalid id for product'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Product->delete($id)) {
            $this->ProductImage->recursive = -1;
            $images = $this->ProductImage->find('all', array('conditions' => array('product_id' => $id)));

            foreach ($images as $image) {
                $file = $image['ProductImage']['path'] . $image['ProductImage']['name'];
                $tfile = $image['ProductImage']['path'] . "t_" . $image['ProductImage']['name'];

                if (file_exists($file) || file_exists($tfile)) {
                    unlink($file);
                    unlink($tfile);
                    $this->ProductImage->id = $image['ProductImage']['id'];
                    $this->ProductImage->delete();
                }
            }

            $this->Session->setFlash(__d('croogo', 'Product deleted'), 'default', array('class' => 'success'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function admin_upload_image($id)
    {
        if (!$id) {
            $this->Session->setFlash(__d('croogo', 'Invalid id for product'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }

        $info = array();
        $this->autoRender = false;

        if ($this->request->isPost() || $this->request->isPut()) {
        //if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $info['files'] = $this->upload($id);
        } else {
            $info['files'] = $this->getImages($id);
        }

        header('Pragma: no-cache');
        header('Cache-Control: private, no-cache');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        header('Vary: Accept');
        echo json_encode($info);
    }

    protected function getImages($id)
    {
        $data = array();
        $baseUrl = $this->request->base;
        $this->ProductImage->recursive = -1;
        $images = $this->ProductImage->find('all', array('conditions' => array('product_id' => $id)));

        foreach ($images as $image) {
            $img = new stdClass();
            $img->name = $image['ProductImage']['name'];
            $img->size = $image['ProductImage']['size'];
            $img->type = $image['ProductImage']['type'];
            $img->url = $baseUrl . "/admin/products_catalog/products/image/" . $image['ProductImage']['id'];
            $img->thumbnail_url = $baseUrl . "/admin/products_catalog/products/thumb/" . $image['ProductImage']['id'];
            $img->delete_url = $baseUrl . "/admin/products_catalog/products/delete_image/" . $image['ProductImage']['id'];
            $img->delete_type = 'DELETE';
            $data[] = $img;
        }

        return $data;
    }

    protected function upload($id) {
        $ext = substr($_FILES['files']['name']['0'], -4);
        $name = sha1($id . $_FILES['files']['name']['0'] . date('Y-m-d H:i:s')) . $ext;
        $tname = "t_$name";
        $path = WWW_ROOT . "uploads" . DS;
        $imageWidth = Configure::read('ProductsCatalog.maxImageWidth');
        $imageHeight = Configure::read('ProductsCatalog.maxImageHeight');
        $thumbWidth = Configure::read('ProductsCatalog.thumbImageWidth');
        $thumbHeight = Configure::read('ProductsCatalog.thumbImageHeight');

        $succeed = $this->ImageResizer->resizeImage(
                $_FILES['files']['tmp_name'][0],
                array(
                    'output' => $path . $tname,
                    'cropZoom' => true,
                    'maxHeight' => $thumbHeight,
                    'maxWidth' => $thumbWidth
                )
            ) && $this->ImageResizer->resizeImage(
                $_FILES['files']['tmp_name'][0],
                array(
                    'output' => $path . $name,
                    'cropZoom' => true,
                    'maxHeight' => $imageHeight,
                    'maxWidth' => $imageWidth
                )
            );


        $data = array();
        $baseUrl = $this->request->base;
        if ($succeed) {
            $this->ProductImage->save(array(
                    'name' => $name,
                    'size' => filesize($path . $name),
                    'type' => $_FILES['files']['type'][0],
                    'path' => $path,
                    'product_id' => $id,
                ));

            $image = new stdClass();
            $image->name = $name;
            $image->size = filesize($path . $name);
            $image->type = $_FILES['files']['type'];
            $image->url = $baseUrl . "/admin/products_catalog/products/image/" . $this->ProductImage->id;
            $image->thumbnail_url = $baseUrl . "/admin/products_catalog/products/thumb/" . $this->ProductImage->id;
            $image->delete_url = $baseUrl . "/admin/products_catalog/products/delete_image/" . $this->ProductImage->id;
            $image->delete_type = 'DELETE';

            $data[] = $image;
        }

        return $data;
    }

    public function admin_image($id)
    {
        if (!$id) {
            $this->Session->setFlash(__d('croogo', 'Invalid id for product image'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }

        $this->autoRender = false;
        $this->ProductImage->recursive = -1;
        $image = $this->ProductImage->read(null, $id);
        $file = $image['ProductImage']['path'] . $image['ProductImage']['name'];

        if ($image['ProductImage']['type'] == "image/jpeg") {
            header('Content-Type: image/jpeg');
            $im = @imagecreatefromjpeg($file);
            imagejpeg($im);
            imagedestroy($im);
        } else {
            header('Content-Type: image/png');
            $im = @imagecreatefrompng($file);
            imagepng($im);
            imagedestroy($im);
        }
    }

    public function admin_thumb($id)
    {
        if (!$id) {
            $this->Session->setFlash(__d('croogo', 'Invalid id for product image'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }

        $this->autoRender = false;
        $this->ProductImage->recursive = -1;
        $image = $this->ProductImage->read(null, $id);
        $file = $image['ProductImage']['path'] . "t_" . $image['ProductImage']['name'];

        if ($image['ProductImage']['type'] == "image/jpeg") {
            header('Content-Type: image/jpeg');
            $im = @imagecreatefromjpeg($file);
            imagejpeg($im);
            imagedestroy($im);
        } else {
            header('Content-Type: image/png');
            $im = @imagecreatefrompng($file);
            imagepng($im);
            imagedestroy($im);
        }
    }

    public function admin_delete_image($id)
    {
        if (!$id) {
            $this->Session->setFlash(__d('croogo', 'Invalid id for product image'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }

        $this->autoRender = false;
        $this->ProductImage->recursive = -1;
        $image = $this->ProductImage->read(null, $id);
        $file = $image['ProductImage']['path'] . $image['ProductImage']['name'];
        $tfile = $image['ProductImage']['path'] . "t_" . $image['ProductImage']['name'];

        if (file_exists($file) || file_exists($tfile)) {
            unlink($file);
            unlink($tfile);
            $this->ProductImage->id = $id;
            $this->ProductImage->delete();
        }
    }

    public function view()
    {
        $faqs = $this->Faq->generateHierarchy();

        $this->set(compact('faqs'));
    }
}