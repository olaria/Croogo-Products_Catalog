<?php

app::uses("AppModel", "Model");

/**
 * Croogo Products Catalog Extension
 * Product Model
 *
 * @category Model
 * @package  Croogo.ProductsCatalog
 * @version  0.1
 * @author   Helder Santana <helder@olaria.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.oalria.me
 */
class Product extends AppModel
{

    /**
     * Model name
     *
     * @var string
     */
    public $name = "Product";

    /**
     * Behaviors used by the Model
     *
     * @var array
     */
    public $actsAs = array(
        'Search.Searchable',
    );

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
        'category_id' => array(
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
            'minLength' => array(
                'rule' => array('minLength', 3),
                'required' => true,
                'message' => 'This field must be at least 3 characters long.'
            ),
        ),
        'description' => array(
            'minLength' => array(
                'rule' => array('minLength', 3),
                'allowEmpty' => true,
                'message' => 'This field must be at least 3 characters long.'
            ),
        ),
        'status' => array(
            'inList' => array(
                'rule' => array('inList', array(0, 1)),
                'required' => true,
                'message' => 'This field must be either Active or Inactive.',
            ),
        ),
        'created' => array(
            'datetime' => array(
                'rule' => 'datetime',
                'required' => true,
                'message' => 'This field must be datetime',
            )
        ),
        'updated' => array(
            'datetime' => array(
                'rule' => 'datetime',
                'required' => true,
                'message' => 'This field must be datetime',
            )
        ),
    );

    /**
     * Model associations: belongsTo
     *
     * @var array
     */
    public $belongsTo = array(
        'ProductCategory' => array(
            'className' => 'ProductsCatalog.ProductCategory',
            'foreignKey' => 'category_id',
        ),
    );
}