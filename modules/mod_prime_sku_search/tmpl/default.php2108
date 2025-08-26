<?php
/**
 * @package     Vendor\Module\PrimeSkuSearch
 *
 * @copyright   Copyright (C) 2025 OneDigital. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

/** @var Joomla\Registry\Registry $params */
/** @var stdClass $module */

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');
$app = Factory::getApplication();
$skuValue = $app->input->getString('sku', '');
?>

<div class="mod_prime_sku_search <?php echo $moduleclass_sfx; ?>">
    <form id="prime-sku-search-form" action="<?php echo Route::_('index.php?option=com_prime&view=tiles'); ?>" method="get">
        <div class="input-group">
            <input type="text" name="sku" id="sku_search_input" class="form-control" value="<?php echo htmlspecialchars($skuValue, ENT_COMPAT, 'UTF-8'); ?>" placeholder="<?php echo Text::_('MOD_PRIME_SKU_SEARCH_LABEL'); ?>">
            <button class="btn btn-primary" type="submit"><?php echo Text::_('MOD_PRIME_SKU_SEARCH_BUTTON'); ?></button>
        </div>
        <input type="hidden" name="option" value="com_prime">
        <input type="hidden" name="view" value="tiles">
    </form>
</div>