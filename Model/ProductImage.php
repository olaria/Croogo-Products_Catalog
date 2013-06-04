<?php

app::uses("AppModel", "Model");

/**
 * Croogo Products Catalog Extension
 * ProductImage Model
 *
 * @category Model
 * @package  Croogo.ProductsCatalog
 * @version  0.1
 * @author   Helder Santana <helder@olaria.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.oalria.me
 */
class ProductImage extends AppModel
{

    /**
     * Model name
     *
     * @var string
     */
    public $name = "ProductImage";

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'id' => array(
            'blank' => array(
                'rule' => 'blank',
                'on' => 'create',
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'This field cannot be left blank.',
                'on' => 'update',
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'This field must be numeric.',
                'on' => 'update',
            ),
        ),
        'product_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'This field cannot be left blank.',
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'This field must be numeric.',
            ),
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'This field cannot be left blank.',
            ),
        ),
        'size' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'This field cannot be left blank.',
            ),
        ),
        'type' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'This field cannot be left blank.',
            ),
        ),
        'path' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'This field cannot be left blank.',
            ),
        ),
    );

    /**
     * Model associations: belongsTo
     *
     * @var array
     */
    public $belongsTo = array(
        'Product' => array(
            'className' => 'ProductsCatalog.Product',
            'foreignKey' => 'product_id',
        ),
    );
}