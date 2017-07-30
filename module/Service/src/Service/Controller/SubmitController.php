<?php

namespace Service\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\Factory;

class SubmitController extends AbstractActionController
{

    public function indexAction()
    {
        //endpoint for submitting order 
        
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
                array('name' => 'customerName',
                      'required' => true,
                      //note: 'allow_empty' and 'continue_if_empty' options are deprecated since zf 2.4.8, to disallow this field to accept empty value, set these options to true and add a Zend\Validator\NotEmpty instance to the validator chain instead
                      'allow_empty' => true,
                      'continue_if_empty' => true, // if true, then continue filtering and validating if input is empty, else input is not filtered/validated
                      'filters' => array(array('name' => 'Zend\Filter\StringTrim'),
                                        ),
                      'validators' => array(array('name' => 'Zend\Validator\NotEmpty',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => "customerName cannot be empty"))
                                                 ),
                                            array('name' => 'Zend\I18n\Validator\Alnum',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('allowWhiteSpace' => true,
                                                                     'messages' => array(\Zend\I18n\Validator\Alnum::INVALID      => "Invalid data type given for customerName",
                                                                                         \Zend\I18n\Validator\Alnum::NOT_ALNUM    => "customerName must only be alphanumeric",
                                                                                         \Zend\I18n\Validator\Alnum::STRING_EMPTY => "customerName cannot be empty",
                                                                                        ),
                                                                    ),
                                                 ),
                                           ),
                ),
                array('name' => 'customerName',
                      'required' => true,
                      //note: 'allow_empty' and 'continue_if_empty' options are deprecated since zf 2.4.8, to disallow this field to accept empty value, set these options to true and add a Zend\Validator\NotEmpty instance to the validator chain instead
                      'allow_empty' => true,
                      'continue_if_empty' => true, // if true, then continue filtering and validating if input is empty, else input is not filtered/validated
                      'filters' => array(array('name' => 'Zend\Filter\StringTrim'),
                                        ),
                      'validators' => array(array('name' => 'Zend\Validator\NotEmpty',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => "customerName cannot be empty"))
                                                 ),
                                            array('name' => 'Zend\I18n\Validator\Alnum',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('allowWhiteSpace' => true,
                                                                     'messages' => array(\Zend\I18n\Validator\Alnum::INVALID      => "Invalid data type given for customerName",
                                                                                         \Zend\I18n\Validator\Alnum::NOT_ALNUM    => "customerName must only be alphanumeric",
                                                                                         \Zend\I18n\Validator\Alnum::STRING_EMPTY => "customerName cannot be empty",
                                                                                        ),
                                                                    ),
                                                 ),
                                           ),
                ),
                array('name' => 'customerPhone',
                      'required' => true,
                      //note: 'allow_empty' and 'continue_if_empty' options are deprecated since zf 2.4.8, to disallow this field to accept empty value, set these options to true and add a Zend\Validator\NotEmpty instance to the validator chain instead
                      'allow_empty' => true,
                      'continue_if_empty' => true, // if true, then continue filtering and validating if input is empty, else input is not filtered/validated
                      'filters' => array(array('name' => 'Zend\Filter\StringTrim'),
                                        ),
                      'validators' => array(array('name' => 'Zend\Validator\NotEmpty',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => "customerPhone cannot be empty"))
                                                 ),
                                            array('name' => 'Zend\Validator\Digits',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('messages' => array(\Zend\Validator\Digits::INVALID      => "Invalid data type given for customerPhone",
                                                                                         \Zend\Validator\Digits::NOT_DIGITS   => "customerPhone must only be numbers",
                                                                                         \Zend\Validator\Digits::STRING_EMPTY => "customerPhone cannot be empty",
                                                                                        ),
                                                                    ),
                                                 ),
                                           ),
                ),
                //NOTE: validating email here can be ommited since the domain model has validation in setter method
                array('name' => 'customerEmail',
                      'required' => true,
                      //note: 'allow_empty' and 'continue_if_empty' options are deprecated since zf 2.4.8, to disallow this field to accept empty value, set these options to true and add a Zend\Validator\NotEmpty instance to the validator chain instead
                      'allow_empty' => true,
                      'continue_if_empty' => false, // if true, then continue filtering and validating if input is empty, else input is not filtered/validated
                      'filters' => array(array('name' => 'Zend\Filter\StringTrim'),
                                        ),
                      'validators' => array(array('name' => 'Zend\Validator\EmailAddress',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('useMxCheck' => false,
                                                                     'useDeepMxCheck' => false,
                                                                     'useDomainCheck' => true,
                                                                     'allow' => \Zend\Validator\Hostname::ALLOW_ALL, //allow all kind of hostname in email address
                                                                     'messages' => array(\Zend\Validator\EmailAddress::INVALID            => "Invalid data type given",//"Invalid type given. String expected",
                                                                                         \Zend\Validator\EmailAddress::INVALID_FORMAT     => "The input is not a valid email address. Use the basic format local-part@hostname",
                                                                                         \Zend\Validator\EmailAddress::INVALID_HOSTNAME   => "'%hostname%' is not a valid hostname for the email address",
                                                                                         \Zend\Validator\EmailAddress::INVALID_MX_RECORD  => "'%hostname%' does not appear to have any valid MX or A records for the email address",
                                                                                         \Zend\Validator\EmailAddress::INVALID_SEGMENT    => "'%hostname%' is not in a routable network segment. The email address should not be resolved from public network",
                                                                                         \Zend\Validator\EmailAddress::DOT_ATOM           => "'%localPart%' can not be matched against dot-atom format",
                                                                                         \Zend\Validator\EmailAddress::QUOTED_STRING      => "'%localPart%' can not be matched against quoted-string format",
                                                                                         \Zend\Validator\EmailAddress::INVALID_LOCAL_PART => "'%localPart%' is not a valid local part for the email address",
                                                                                         \Zend\Validator\EmailAddress::LENGTH_EXCEEDED    => "The input exceeds the allowed length",
                                                                                      ),
                                                                  ),
                                                ),
                                          ),
                ),
                array('name' => 'customerAddress',
                      'required' => true,
                      //note: 'allow_empty' and 'continue_if_empty' options are deprecated since zf 2.4.8, to disallow this field to accept empty value, set these options to true and add a Zend\Validator\NotEmpty instance to the validator chain instead
                      'allow_empty' => true,
                      'continue_if_empty' => true, // if true, then continue filtering and validating if input is empty, else input is not filtered/validated
                      'filters' => array(array('name' => 'Zend\Filter\StringTrim'),
                                        ),
                      'validators' => array(array('name' => 'Zend\Validator\NotEmpty',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => "customerAddress cannot be empty"))
                                                 ),
                                            array('name' => 'Zend\I18n\Validator\Alnum',
                                                  'break_chain_on_failure' => true,
                                                  'options' => array('allowWhiteSpace' => true,
                                                                     'messages' => array(\Zend\I18n\Validator\Alnum::INVALID      => "Invalid data type given for customerAddress",
                                                                                         \Zend\I18n\Validator\Alnum::NOT_ALNUM    => "customerAddress must only be alphanumeric",
                                                                                         \Zend\I18n\Validator\Alnum::STRING_EMPTY => "customerAddress cannot be empty",
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
            $customerName = $inputFilter->getValue('customerName');
            $customerEmail = $inputFilter->getValue('customerEmail');   
            $customerPhone = $inputFilter->getValue('customerPhone');
            $customerAddress = $inputFilter->getValue('customerAddress');
            
            $orderMapper = $this->getServiceLocator()->get('Emoneygw\DataMapper\Concrete\Order');
            
            //find whether order exists or not
            $order = $orderMapper->findById($orderId);
            if(false === $order) {
                $response = $this->_createFailedResponse('Order not found');
                return $response;
            }
            
            //set customer properties in order
            $order->customerName = $customerName;
            $order->customerEmail = $customerEmail;
            $order->customerPhone = $customerPhone;
            $order->customerAddress = $customerAddress;
            
            //order found, try submitting
            $order->submitOrder();
            
            //remember to save order (persist to storage)
            $orderMapper->save($order);
            
            $response = $this->_createSuccessResponse("order submitted successfully");
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