<?php
//name mapping used by \Zend\Stdlib\Hydrator object
//array keys are property names in domain model object, array values are field names in storage/db
//NOTE: namemapping is used by hydrator (for hydration and extraction) and data mapper (for db column names when querying)
return array('nameMapping' => array(
    'id' => 'order_id',
    'createdDate' => 'order_created_date',
    'processedDate' => 'order_processed_date',
    'submittedDate' => 'order_submitted_date',
    'status' => 'order_status',
    'totalAmount' => 'order_total_amount',
    'coupon' => 'order_coupon',
    'customerName' => 'customer_name',
    'customerAddress' => 'customer_address',
    'customerPhone' => 'customer_phone',
    'customerEmail' => 'customer_email',
    'shippingId' => 'shipping_id',
    'shippingStatus' => 'shipping_status',
    )
);
