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
// Date:     07/11/2018
// Project:  RouterRoutableResolver
//
declare(strict_types=1);
namespace CodeInc\RouterRoutableResolver\Exceptions;
use CodeInc\Router\Exceptions\RouterException;
use CodeInc\RouterRoutableResolver\MultiRoutableRequestHandlerInterface;
use CodeInc\RouterRoutableResolver\RoutableRequestHandlerInterface;


/**
 * Class NotARoutableHandlerException
 *
 * @package CodeInc\RouterRoutableResolver\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class NotARoutableHandlerException extends \LogicException implements RouterException
{
    /**
     * @var string
     */
    private $class;

    /**
     * NotARoutableHandlerException constructor.
     *
     * @param string $class
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $class, int $code = 0, \Throwable $previous = null)
    {
        $this->class = $class;
        parent::__construct(
            sprintf("The class '%s' is not a routable handler. All routable handler must implement '%s' or '%s' "
                ."and provide a least one valid route.",
                $class, RoutableRequestHandlerInterface::class, MultiRoutableRequestHandlerInterface::class),
            $code,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getClass():string
    {
        return $this->class;
    }
}