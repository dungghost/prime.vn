<?php
/**
 * @package     Vendor\Module\PrimeTileSearch
 *
 * @copyright   Copyright (C) 2024 OneDigital. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

namespace Vendor\Module\PrimeTileSearch\Site\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Vendor\Module\PrimeTileSearch\Site\Helper\PrimeTileSearchHelper;

/**
 * Dispatcher class for mod_prime_tile_search
 *
 * @since  1.0.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data
     *
     * @return  array
     *
     * @since   1.0.0
     */
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();

        $helperName = 'PrimeTileSearchHelper';
        //$data['message'] = $this->getHelperFactory()->getHelper($helperName)->getMessage($data['params'], $this->getApplication());

        return $data;
    }
}