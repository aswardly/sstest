<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Emoneygw;

return array(
    'service_manager' => array(
        'factories' => array(
            //data mapper factories
            'Emoneygw\DataMapper\Concrete\Product' => 'Emoneygw\Factory\DataMapper\Product',
            'Emoneygw\DataMapper\Concrete\Coupon' => 'Emoneygw\Factory\DataMapper\Coupon',
            'Emoneygw\DataMapper\Concrete\Order' => 'Emoneygw\Factory\DataMapper\Order',
            'Emoneygw\DataMapper\Concrete\OrderItem' => 'Emoneygw\Factory\DataMapper\OrderItem',
            //data mapper hydrator factories
            'Emoneygw\DataMapper\Hydrator\Product' => 'Emoneygw\Factory\DataMapper\Hydrator\Product',
            'Emoneygw\DataMapper\Hydrator\Coupon' => 'Emoneygw\Factory\DataMapper\Hydrator\Coupon',
            'Emoneygw\DataMapper\Hydrator\Order' => 'Emoneygw\Factory\DataMapper\Hydrator\Order',
            'Emoneygw\DataMapper\Hydrator\OrderItem' => 'Emoneygw\Factory\DataMapper\Hydrator\OrderItem'
        ),
    )
);