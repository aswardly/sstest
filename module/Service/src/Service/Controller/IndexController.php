<?php

namespace Service\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{   
    public function indexAction()
    {
        //endpoint for handling order creation request
        
        $request = $this->getRequest();
        
        if(false === $request->isPost()) {
            //invalid request method
            $response = $this->_createFailedResponse('Invalid Request Method');
            return $response;
        }
        
        try {
            $orderMapper = $this->getServiceLocator()->get('Emoneygw\DataMapper\Concrete\Order');
            
            $newOrder = new \Emoneygw\Model\Concrete\Order();
            $newOrder->createOrder();
            
            $orderMapper->save($newOrder);
            
            $response = $this->_createSuccessResponse($newOrder->id);
            return $response;
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
    private function _createSuccessResponse($orderId) {
        $obj = new \stdClass();
        $obj->code = 'S'; //Success
        $obj->orderId = $orderId;
        $message = \Zend\Json\Json::encode($obj);
        
        $response = $this->getResponse(); 
        $response->setStatusCode(200);
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $response->setContent($message);
        
        return $response;
    }
}

