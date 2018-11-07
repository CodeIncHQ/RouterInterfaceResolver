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
use CodeInc\DirectoryClassesIterator\RecursiveDirectoryClassesIterator;
use CodeInc\RouterRoutableResolver\Exceptions\NotARoutableHandlerException;
use CodeInc\Router\Resolvers\StaticHandlerResolver;


/**
 * Class RoutableHandlerResolver
 *
 * @package CodeInc\RouterRoutableResolver
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RoutableHandlerResolver extends StaticHandlerResolver
{
    /**
     * RoutableHandlerResolver constructor.
     *
     * @param iterable|null $handlerClasses
     * @throws \ReflectionException
     */
    public function __construct(?iterable $handlerClasses = null)
    {
        parent::__construct();
        if ($handlerClasses) {
            $this->addHandlers($handlerClasses);
        }
    }

    /**
     * Adds multiple routable handlers to the resolver.
     *
     * @param iterable $handlerClasses
     * @throws \ReflectionException
     */
    public function addHandlers(iterable $handlerClasses):void
    {
        foreach ($handlerClasses as $handlersClass) {
            $this->addHandler($handlersClass);
        }
    }

    /**
     * Adds a routable handler to the resolver.
     *
     * @param string $handlerClass
     * @throws NotARoutableHandlerException
     * @throws \ReflectionException
     */
    public function addHandler(string $handlerClass):void
    {
        $reflectionClass = new \ReflectionClass($handlerClass);
        if (!$reflectionClass->isAbstract()) {
            $routeAdded = false;
            if ($reflectionClass->isSubclassOf(RoutableRequestHandlerInterface::class)) {
                /** @var RoutableRequestHandlerInterface $handlerClass */
                /** @noinspection PhpStrictTypeCheckingInspection */
                $this->addRoute($handlerClass::getRoute(), $handlerClass);
                $routeAdded = true;
            }
            if ($reflectionClass->isSubclassOf(MultiRoutableRequestHandlerInterface::class)) {
                /** @var MultiRoutableRequestHandlerInterface $handlerClass */
                foreach ($handlerClass::getRoutes() as $route) {
                    $this->addRoute($route, $handlerClass);
                    $routeAdded = true;
                }
            }
            if (!$routeAdded) {
                throw new NotARoutableHandlerException($handlerClass);
            }
        }
    }

    /**
     * Adds all the handler in a directory implementing RoutableRequestHandlerInterface
     * or MultiRoutableRequestHandlerInterface
     *
     * @param string $dirPath
     * @throws \ReflectionException
     */
    public function addDirectory(string $dirPath):void
    {
        foreach (new RecursiveDirectoryClassesIterator($dirPath) as $class)
        {
            if ($class->isSubclassOf(RoutableRequestHandlerInterface::class)
                || $class->isSubclassOf(MultiRoutableRequestHandlerInterface::class)) {
                $this->addHandler($class->getName());
            }
        }
    }
}