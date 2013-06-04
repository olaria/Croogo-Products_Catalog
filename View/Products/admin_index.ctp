<?php

$this->extend('/Common/admin_index');

$this->Html
    ->addCrumb('', '/admin', array('icon' => 'home'))
    ->addCrumb(__d('croogo', 'Content'), array('plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'index'))
    ->addCrumb(__d('croogo', 'Products'), $this->here);

?>
<table class="table table-striped">
    <?php
    $tableHeaders = $this->Html->tableHeaders(
        array(
            $this->Paginator->sort('id'),
            $this->Paginator->sort('Category'),
            $this->Paginator->sort('Product'),
            __d('croogo', 'Actions'),
        )
    );
    ?>
    <thead>
    <?php echo $tableHeaders; ?>
    </thead>
    <?php

    $rows = array();
    foreach ($products as $product) :
        $actions = array();
        $actions[] = $this->Croogo->adminRowActions($product['Product']['id']);
        $actions[] = $this->Croogo->adminRowAction(
            '',
            array('controller' => 'products', 'action' => 'edit', $product['Product']['id']),
            array('icon' => 'pencil', 'tooltip' => __d('croogo', 'Edit this item'))
        );
        $actions[] = $this->Croogo->adminRowAction(
            '',
            array('controller' => 'products', 'action' => 'delete', $product['Product']['id']),
            array('icon' => 'trash', 'tooltip' => __d('croogo', 'Remove this item')),
            __d('croogo', 'Are you sure?')
        );
        $actions = $this->Html->div('item-actions', implode(' ', $actions));
        $rows[] = array(
            $product['Product']['id'],
            $product['ProductCategory']['name'],
            $this->Html->link(
                $product['Product']['name'],
                array('controller' => 'products', 'action' => 'view', $product['Product']['id'])
            ),
            $actions,
        );
    endforeach;

    echo $this->Html->tableCells($rows);
    ?>
</table>