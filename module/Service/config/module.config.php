<?php
return array(
    'service_manager' => array(
        
    ),
    'controllers' => array(
        'invokables' => array(
            'Service\Controller\Index' => 'Service\Controller\IndexController',
            'Service\Controller\Add' => 'Service\Controller\AddController',
            'Service\Controller\Remove' => 'Service\Controller\RemoveController',
            'Service\Controller\Edit' => 'Service\Controller\EditController',
            'Service\Controller\Apply' => 'Service\Controller\ApplyController',
            'Service\Controller\Submit' => 'Service\Controller\SubmitController',
            'Service\Controller\View' => 'Service\Controller\ViewController',
            'Service\Controller\Process' => 'Service\Controller\ProcessController',
            'Service\Controller\Payment' => 'Service\Controller\PaymentController',
            'Service\Controller\Shipping' => 'Service\Controller\ShippingController',
            'Service\Controller\Update' => 'Service\Controller\UpdateController',
            'Service\Controller\Inquiry' => 'Service\Controller\InquiryController'
        ),
        'factories' => array(),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            //Route for creating order
            'Service\Index\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/CreateOrder',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            //route for adding item to order
            'Service\Add\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/AddItem',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Add',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for removing item from order
            'Service\Remove\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/RemoveItem',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Remove',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for editing item from order
            'Service\Edit\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/EditItem',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Edit',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for applying coupon
            'Service\Apply\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ApplyCoupon',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Apply',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for submitting order
            'Service\Submit\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/SubmitOrder',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Submit',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for viewing order detail
            'Service\View\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ViewOrder',
                    'defaults' => array(
                        'controller' => 'Service\Controller\View',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for processing order
            'Service\Process\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ProcessOrder',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Process',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for processing order
            'Service\Payment\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/Payment',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Payment',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for adding shipping info
            'Service\Shipping\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/AddShipping',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Shipping',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for updating shipping info
            'Service\Update\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/UpdateShipping',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Update',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            //route for inquiring shipping info
            'Service\Inquiry\Index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/InquiryShipping',
                    'defaults' => array(
                        'controller' => 'Service\Controller\Inquiry',
                        'action'     => 'index',
                    ),
                ),
            ),
        )
    )
);