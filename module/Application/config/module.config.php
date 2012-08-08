<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'di' => array(
        'allowed_controllers' => array(
            // this config is required, otherwise the MVC won't even attempt to ask Di for the controller!
            'Application\Controller\GreetingController',
        ),

        'instance' => array(
            'preference' => array(
                // these allow injecting correct EventManager and ServiceManager
                // (taken from the main ServiceManager) into the controller,
                // because Di doesn't know how to retrieve abstract types. These
                // dependencies are inherited from Zend\Mvc\Controller\AbstractController
                'Zend\EventManager\EventManagerInterface' => 'Zend\EventManager\EventManager',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'Zend\ServiceManager\ServiceManager',

                // additional preferences to map abstract types to concrete implementations
                // in the greeting logic
                'Application\Service\GreetingServiceInterface' => 'Application\Service\GreetingService',
                'Application\Repository\GreetingRepositoryInterface' => 'Application\Repository\StaticGreetingRepository',

                // since now also dependencies of EventManager are crawled, we need to define type preferences also
                // for SharedEventManagerInterface
                'Zend\EventManager\SharedEventManagerInterface' => 'Zend\EventManager\SharedEventManager',
            ),

            'Application\Controller\GreetingController' => array(
                // simply defining a config key for the controller. This forces the Di compiler to crawl it
                // when generating a container
                'shared' => true,
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'hello' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/hello',
                    'defaults' => array(
                        'controller' => 'Application\Controller\GreetingController',
                        'action' => 'hello',
                    ),
                ),
            ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),

        // uncomment following to verify the performance difference in using Zend\Di or a service factory
        /*'factories' => array(
            'Application\Controller\GreetingController' => function($sm) {
                return new \Application\Controller\GreetingController(
                    new \Application\Service\GreetingService(
                        new \Application\Repository\StaticGreetingRepository()
                    )
                );
            },
        ),*/
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
