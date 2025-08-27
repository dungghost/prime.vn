<?php
/**
 * @package     Vendor\Module\PrimeSkuSearch
 *
 * @copyright   Copyright (C) 2025 OneDigital. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\Registry\Registry $params */
/** @var stdClass $module */

$app = Factory::getApplication();
$doc = Factory::getDocument();

$doc->addStyleSheet(Uri::base(true) . '/modules/mod_prime_sku_search/assets/css/mod_prime_sku_search.css');
HTMLHelper::_('bootstrap.tooltip');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

// Lấy giá trị tìm kiếm từ session
$langContext = 'com_prime.tiles.' . $app->getLanguage()->getTag();
$filters = (array) $app->getUserState($langContext . '.filter', []);
$searchValue = $filters['search'] ?? '';

// Tìm Itemid dành riêng cho trang sản phẩm để đảm bảo URL và phân trang chính xác
$menu = $app->getMenu();
$menuItem = $menu->getItems('link', 'index.php?option=com_prime&view=tiles', true);
$itemId = $menuItem ? $menuItem->id : null;

// Xây dựng URL action cho form, sử dụng Itemid đã tìm thấy nếu có
$formAction = Route::_('index.php?option=com_prime&view=tiles' . ($itemId ? '&Itemid=' . $itemId : ''));

?>

<div class="mod_prime_sku_search <?php echo $moduleclass_sfx; ?>">
    <?php if ($module->showtitle) : ?>
        <h3 class="page-header"><?php echo $module->title; ?></h3>
    <?php endif; ?>
    <form id="prime-sku-search-form-<?php echo $module->id; ?>" action="<?php echo $formAction; ?>" method="get">
        <div class="input-group">
            <label for="sku_search_input-<?php echo $module->id; ?>" class="visually-hidden"><?php echo Text::_('MOD_PRIME_SKU_SEARCH_LABEL'); ?></label>
            <input type="text" name="filter[search]" id="sku_search_input-<?php echo $module->id; ?>" class="form-control" value="<?php echo htmlspecialchars($searchValue, ENT_COMPAT, 'UTF-8'); ?>" placeholder="<?php echo Text::_('MOD_PRIME_SKU_SEARCH_LABEL'); ?>">
            
            <?php if (!empty($searchValue)) : ?>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-clear-sku" id="btn-clear-sku-search-<?php echo $module->id; ?>" title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>">
                        <span class="icon-remove" aria-hidden="true"></span>
                    </button>
                </span>
            <?php endif; ?>
            
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sku-search" type="submit" title="<?php echo Text::_('MOD_PRIME_SKU_SEARCH_BUTTON'); ?>">
                    <span class="icon-search" aria-hidden="true"></span>
                </button>
            </span>
        </div>
    </form>
</div>

<?php
// Thêm JavaScript để xóa ô tìm kiếm
$script = "
document.addEventListener('DOMContentLoaded', function() {
    var clearButton = document.getElementById('btn-clear-sku-search-{$module->id}');
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            var input = document.getElementById('sku_search_input-{$module->id}');
            var form = document.getElementById('prime-sku-search-form-{$module->id}');
            if (input && form) {
                input.value = '';
                form.submit();
            }
        });
    }
});
";
$doc->addScriptDeclaration($script);
?>
