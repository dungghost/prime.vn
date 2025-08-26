<?php
/**
 * @package     Vendor\Module\PrimeSkuSearch
 * @copyright   Copyright (C) 2025 OneDigital. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Extension\Module\Module;
use Joomla\CMS\Factory;
use Joomla\DI\Container;

// Get the module's dispatcher from the DI container
try {
    /** @var Container $container */
    $container = Factory::getContainer();

    /** @var DispatcherInterface $dispatcher */
    $dispatcher = $container->get(
        'dispatcher-factory.module',
        [
            'options' => [
                'client'    => Factory::getApplication()->isClient('administrator') ? Module::CLIENT_ADMINISTRATOR : Module::CLIENT_SITE,
                'name'      => 'mod_prime_sku_search',
                'params'    => $params,
            ]
        ]
    );

    $dispatcher->dispatch();
} catch (Exception $e) {
    // Show a message in debug mode
    if (Factory::getApplication()->get('debug')) {
        echo $e->getMessage();
    }
}