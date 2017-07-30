<?php
namespace Emoneygw;

//use Zend\ModuleManager\Feature\FormElementProviderInterface;

class Module //implements FormElementProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            //'Zend\Loader\ClassMapAutoloader' => array(
            //__DIR__ . '/autoload_classmap.php' //classmap for emoneygw classes
            //), 
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}