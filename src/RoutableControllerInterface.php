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
use CodeInc\Router\ControllerInterface;


/**
 * Interface RoutableControllerInterface
 *
 * @package CodeInc\RouterRoutableResolver
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface RoutableControllerInterface extends ControllerInterface
{
    /**
     * Returns the route for the current controller.
     *
     * @return string
     */
    public static function getRoute():string;
}