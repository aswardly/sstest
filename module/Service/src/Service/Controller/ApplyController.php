<?php

namespace Service\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\Factory;

class ApplyController extends AbstractActionController
{
    public function indexAction()
    {
        //endpoint for applying coupon to order 
        
        $request = $this->getRequest();
        
        if(false === $request->isPost()) {
            //invalid request method
            $response = $this->_createFailedResponse('Invalid Request Method');
            return $response;
        }
        
        try {
            //sanitize input using \Zend\Input\Filter
            $factory = new Factory();
            $inputFilter = $factory->createInputFilter(array(
                array('name' => 'orderId',
                      'required' => true,
                      //note: 'allow_empty' and 'continue_if_empty' options are deprecated since zf 2.4.8, to disallow this field to accept empty value, set these options to true and add a Zend\Validator\NotEmpty instance to the validator chain instead
                      'allow_empty' => true,
                      'continue_if_empty' => true, // if true, then continue filtering and validating if input is empty, else input is not filtered/validated
                      'filters' => array(array('name' => 'Zend\Filter\StringTrim'),
                                        ),
                      'validators' => array(array('name' => 'Zend\Validator\NotEmpty',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => "orderId cannot be empty"))
                                                 ),
                                            array('name' => 'Zend\I18n\Validator\Alnum',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('allowWhiteSpace' => false,
                                                                     'messages' => array(\Zend\I18n\Validator\Alnum::INVALID      => "Invalid data type given for orderId",
                                                                                         \Zend\I18n\Validator\Alnum::NOT_ALNUM    => "orderId must only be alphanumeric",
                                                                                         \Zend\I18n\Validator\Alnum::STRING_EMPTY => "orderId cannot be empty",
                                                                                        ),
                                                                    ),
                                                 ),
                                           ),
                ),
                array('name' => 'couponId',
                      'required' => true,
                      //note: 'allow_empty' and 'continue_if_empty' options are deprecated since zf 2.4.8, to disallow this field to accept empty value, set these options to true and add a Zend\Validator\NotEmpty instance to the validator chain instead
                      'allow_empty' => true,
                      'continue_if_empty' => true, // if true, then continue filtering and validating if input is empty, else input is not filtered/validated
                      'filters' => array(array('name' => 'Zend\Filter\StringTrim'),
                                        ),
                      'validators' => array(array('name' => 'Zend\Validator\NotEmpty',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => "couponId cannot be empty"))
                                                 ),
                                            array('name' => 'Zend\I18n\Validator\Alnum',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('allowWhiteSpace' => false,
                                                                     'messages' => array(\Zend\I18n\Validator\Alnum::INVALID      => "Invalid data type given for couponId",
                                                                                         \Zend\I18n\Validator\Alnum::NOT_ALNUM    => "couponId must only be alphanumeric",
                                                                                         \Zend\I18n\Validator\Alnum::STRING_EMPTY => "couponId cannot be empty",
                                                                                        ),
                                                                    ),
                                                 ),
                                           ),
                ),
            ));
            
            $inputFilter->setData($request->getPost());
            if(false === $inputFilter->isValid()) {
                //invalid input, return failed response
                $reasonMessage = $inputFilter->getMessages(); //returns an array of arrays
                $response = $this->_createFailedResponse($reasonMessage);
                return $response;
            }
            //input valid, get values
            $orderId = $inputFilter->getValue('orderId');
            $couponId = $inputFilter->getValue('couponId');
            
            $orderMapper = $this->getServiceLocator()->get('Emoneygw\DataMapper\Concrete\Order');
            $couponMapper = $this->getServiceLocator()->get('Emoneygw\DataMapper\Concrete\Coupon');
            
            //find whether order exists or not
            $order = $orderMapper->findById($orderId);
            if(false === $order) {
                $response = $this->_createFailedResponse('Order not found');
                return $response;
            }
            
            //find whether item exist or not
            $coupon = $couponMapper->findById($couponId);
            if(false === $coupon) {
                $response = $this->_createFailedResponse('Coupon not found');
                return $response;
            }
            
            //order and item exist, try editing item
            $order->applyCoupon($coupon);
            
            //remember to save order (persist to storage)
            $orderMapper->save($order);
            
            $response = $this->_createSuccessResponse("Coupon applied successfully");
        }
        catch(\DomainException $e) {
            //domain exception, we know this is caused by violation of business logic rules
            //TODO: log exceptions such as this
            $response = $this->_createFailedResponse($e->getMessage());
        }
        catch(\Exception $e) {
            //other exceptions
            //TODO: log exceptions such as this
            $response = $this->_createFailedResponse($e->getMessage());
        }
        return $response;
    }
    
    /**
     * Create failed json string for use as response
     * @param string $reason
     */
    private function _createFailedResponse($reason) {
        $obj = new \stdClass();
        $obj->code = 'F'; //Failed
        $obj->reason = $reason;
        $message = \Zend\Json\Json::encode($obj);
        
        $response = $this->getResponse();
        $response->setStatusCode(500);
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $response->setContent($message);
        
        return $response;
    }
    
    /**
     * Create failed json string for use as response
     * @param string $reason
     */
    private function _createSuccessResponse($message) {
        $obj = new \stdClass();
        $obj->code = 'S'; //Success
        $obj->message = $message;
        $message = \Zend\Json\Json::encode($obj);
        
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $response->setContent($message);
        
        return $response;
    }
}

