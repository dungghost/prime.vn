<?php
/**
 * @package     Vendor\Module\PrimeSkuSearch
 *
 * @copyright   Copyright (C) 2025 OneDigital. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class() implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     */
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new ModuleDispatcherFactory('\Vendor\Module\PrimeSkuSearch'));
        $container->registerServiceProvider(new HelperFactory('\Vendor\Module\PrimeSkuSearch\Site\Helper'));
        $container->registerServiceProvider(new Module());
    }
};