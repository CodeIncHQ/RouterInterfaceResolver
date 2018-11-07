<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     12/10/2018
// Project:  RouterRoutableResolver
//
declare(strict_types=1);
namespace CodeInc\RouterRoutableResolver;
use CodeInc\DirectoryClassesIterator\DirectoryClassesIterator;
use CodeInc\DirectoryClassesIterator\RecursiveDirectoryClassesIterator;
use CodeInc\Router\Resolvers\StaticResolver;
use CodeInc\RouterRoutableResolver\Exceptions\NotARoutableControllerException;


/**
 * Class RoutableResolver
 *
 * @package CodeInc\RouterRoutableResolver
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RoutableResolver extends StaticResolver
{
    /**
     * RoutableHandlerResolver constructor.
     *
     * @param iterable|null $classes
     * @throws \ReflectionException
     */
    public function __construct(?iterable $classes = null)
    {
        parent::__construct();
        if ($classes) {
            $this->addControllers($classes);
        }
    }

    /**
     * Adds multiple routable handlers to the resolver.
     *
     * @param iterable $classes
     * @throws \ReflectionException
     */
    public function addControllers(iterable $classes):void
    {
        foreach ($classes as $controllerClass) {
            $this->addController($controllerClass);
        }
    }

    /**
     * Adds a routable handler to the resolver.
     *
     * @param string $controllerClass
     * @throws NotARoutableControllerException
     * @throws \ReflectionException
     */
    public function addController(string $controllerClass):void
    {
        $this->addClass(new \ReflectionClass($controllerClass));
    }

    /**
     * Adds a routable to the resolver.
     *
     * @param \ReflectionClass $class
     * @throws NotARoutableControllerException
     */
    private function addClass(\ReflectionClass $class):void
    {
        if (!$class->isAbstract()) {
            $routeAdded = false;
            if ($class->isSubclassOf(RoutableControllerInterface::class)) {
                /** @var RoutableControllerInterface $controllerClass */
                /** @noinspection PhpStrictTypeCheckingInspection */
                $this->addRoute($controllerClass::getRoute(), $controllerClass);
                $routeAdded = true;
            }
            if ($class->isSubclassOf(MultiRoutableControllerInterface::class)) {
                /** @var MultiRoutableControllerInterface $controllerClass */
                foreach ($controllerClass::getRoutes() as $route) {
                    $this->addRoute($route, $class->getName());
                    $routeAdded = true;
                }
            }
            if (!$routeAdded) {
                throw new NotARoutableControllerException($class->getName());
            }
        }
    }

    /**
     * Adds all the handler in a directory implementing RoutableRequestHandlerInterface
     * or MultiRoutableRequestHandlerInterface
     *
     * @param string $dirPath
     * @param bool $recursively
     */
    public function addDirectory(string $dirPath, bool $recursively = true):void
    {
        $iterator = $recursively
            ? new RecursiveDirectoryClassesIterator($dirPath)
            : new DirectoryClassesIterator($dirPath);

        foreach ($iterator as $class)
        {
            if (!$class->isAbstract() && ($class->isSubclassOf(RoutableControllerInterface::class)
                    || $class->isSubclassOf(MultiRoutableControllerInterface::class))) {
                $this->addClass($class);
            }
        }
    }
}