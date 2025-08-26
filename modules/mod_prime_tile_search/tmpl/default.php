<?php
/**
 * @package     Vendor\Module\PrimeTileSearch
 *
 * @copyright   Copyright (C) 2024 OneDigital. All rights reserved.
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
$db  = Factory::getDbo();

// Tải CSS (nếu có) và thư viện Bootstrap
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('formbehavior.chosen', 'select'); // Kích hoạt Chosen.js cho dropdown đẹp hơn

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

// Lấy trạng thái bộ lọc hiện tại theo ngôn ngữ để điền lại vào form
$langContext = 'com_prime.tiles.' . $app->getLanguage()->getTag();
$filters = (array) $app->getUserState($langContext . '.filter', []);

// Lấy Itemid của trang hiện tại để đảm bảo router hoạt động đúng
$activeMenuItem = $app->getMenu()->getActive();
$itemId = $activeMenuItem ? $activeMenuItem->id : null;
$formAction = Route::_('index.php?option=com_prime&view=tiles' . ($itemId ? '&Itemid=' . $itemId : ''));

// Hàm trợ giúp để tạo dropdown
function createFilterDropdown($name, $label, $table, $keyField, $valueField, $selectedValue, $db)
{
    $query = $db->getQuery(true)
        ->select([$db->quoteName($keyField), $db->quoteName($valueField)])
        ->from($db->quoteName($table))
        ->order($db->quoteName($valueField) . ' ASC');
    
    $options = $db->setQuery($query)->loadObjectList();
    
    $selectOptions = [HTMLHelper::_('select.option', '', Text::_($label))];
    
    return HTMLHelper::_('select.genericlist', array_merge($selectOptions, $options), 'filter[' . $name . '][]', [
        'class' => 'form-select advancedSelect',
        'multiple' => 'multiple',
        'data-placeholder' => Text::_($label)
    ], 'id', 'title', $selectedValue);
}
?>

<div class="mod_prime_tile_search <?php echo $moduleclass_sfx; ?>">
    <form action="<?php echo $formAction; ?>" method="post" id="prime-tile-search-form-<?php echo $module->id; ?>">
        <div class="filter-groups">
            <div class="row">
                <div class="col col-lg-3">
                    <?php 
                        $query = $db->getQuery(true)->select('id, area AS title')->from('#__prime_areas')->order('id');
                        $areas = $db->setQuery($query)->loadObjectList();
                        echo HTMLHelper::_('select.genericlist', $areas, 'filter[area][]', 'class="inputbox form-select advancedSelect" multiple="multiple" data-placeholder="' . Text::_('MOD_PRIME_TILE_SEARCH_AREA') . '"', 'id', 'title', $filters['area'] ?? []);
                    ?>
                </div>
                <div class="col col-lg-3">
                    <?php 
                        $query = $db->getQuery(true)->select('id, color AS title')->from('#__prime_colors')->order('id');
                        $colors = $db->setQuery($query)->loadObjectList();
                        echo HTMLHelper::_('select.genericlist', $colors, 'filter[color][]', 'class="inputbox form-select advancedSelect" multiple="multiple" data-placeholder="' . Text::_('MOD_PRIME_TILE_SEARCH_COLOR') . '"', 'id', 'title', $filters['color'] ?? []);
                    ?>
                </div>
                <div class="col col-lg-3">
                    <?php 
                        $query = $db->getQuery(true)->select('id, title')->from('#__prime_designs')->order('id');
                        $designs = $db->setQuery($query)->loadObjectList();
                        echo HTMLHelper::_('select.genericlist', $designs, 'filter[design][]', 'class="inputbox form-select advancedSelect" multiple="multiple" data-placeholder="' . Text::_('MOD_PRIME_TILE_SEARCH_DESIGN') . '"', 'id', 'title', $filters['design'] ?? []);
                    ?>
                </div>
                <div class="col col-lg-3">
                    <?php 
                        $query = $db->getQuery(true)->select('id, type AS title')->from('#__prime_types')->order('id');
                        $types = $db->setQuery($query)->loadObjectList();
                        echo HTMLHelper::_('select.genericlist', $types, 'filter[type][]', 'class="inputbox form-select advancedSelect" multiple="multiple" data-placeholder="' . Text::_('MOD_PRIME_TILE_SEARCH_TILESTYPES') . '"', 'id', 'title', $filters['type'] ?? []);
                    ?>
                </div>
                <div class="col col-lg-3">
                     <?php 
                        $query = $db->getQuery(true)->select('id, size AS title')->from('#__prime_sizes')->order('id');
                        $sizes = $db->setQuery($query)->loadObjectList();
                        echo HTMLHelper::_('select.genericlist', $sizes, 'filter[size][]', 'class="inputbox form-select advancedSelect" multiple="multiple" data-placeholder="' . Text::_('MOD_PRIME_TILE_SEARCH_SIZE') . '"', 'id', 'title', $filters['size'] ?? []);
                    ?>
                </div>
                <div class="col col-lg-3">
                    <?php 
                        $query = $db->getQuery(true)->select('id, surface AS title')->from('#__prime_surfaces')->order('id');
                        $surfaces = $db->setQuery($query)->loadObjectList();
                        echo HTMLHelper::_('select.genericlist', $surfaces, 'filter[surface][]', 'class="inputbox form-select advancedSelect" multiple="multiple" data-placeholder="' . Text::_('MOD_PRIME_TILE_SEARCH_SURFACE') . '"', 'id', 'title', $filters['surface'] ?? []);
                    ?>
                </div>
                <div class="col col-lg-3">
                    <button type="submit" class="btn btn-primary btn-search w-100">
                        <?php echo Text::_('MOD_PRIME_TILE_SEARCH_BOTTOM'); ?>
                    </button>
                </div>
                 <div class="col col-lg-3">
                    <button type="button" class="btn btn-secondary btn-clear-filters w-100" onclick="Joomla.submitform('search.clear');">
                        <?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" name="task" value="search">
    </form>
</div>