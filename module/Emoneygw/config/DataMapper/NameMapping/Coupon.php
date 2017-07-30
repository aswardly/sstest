<?php
//name mapping used by \Zend\Stdlib\Hydrator object
//array keys are property names in domain model object, array values are field names in storage/db
//NOTE: namemapping is used by hydrator (for hydration and extraction) and data mapper (for db column names when querying)
return array('nameMapping' => array(
    'id' => 'coupon_id',
    'value' => 'coupon_value',
    'type' => 'coupon_type',
    'status' => 'coupon_status',
    'quantity' => 'coupon_quantity',
    'startDate' => 'coupon_start_date',
    'expiryDate' => 'coupon_expiry_date'
    )
);