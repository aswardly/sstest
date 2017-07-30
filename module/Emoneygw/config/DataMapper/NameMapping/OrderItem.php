<?php
//name mapping used by \Zend\Stdlib\Hydrator object
//array keys are property names in domain model object, array values are field names in storage/db
//NOTE: namemapping is used by hydrator (for hydration and extraction) and data mapper (for db column names when querying)
return array('nameMapping' => array(
    'id' => 'item_id',
    //'orderId' => 'order_id', //not used in datamapper
    'product' => 'product_id',
    'productQuantity' => 'product_quantity'
    )
);