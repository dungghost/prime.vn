<?php
/**
 * @package     Vendor\Module\PrimeSkuSearch
 * @copyright   Copyright (C) 2025 OneDigital. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

namespace Vendor\Module\PrimeSkuSearch\Site\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

/**
 * Dispatcher class for mod_prime_sku_search
 *
 * @since  1.0.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data.
     *
     * @return  array
     */
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();
        return $data;
    }
}