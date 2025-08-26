<?php
/**
 * @package     Vendor\Module\PrimeTileSearch
 *
 * @copyright   Copyright (C) 2024 OneDigital. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;

/** @var Joomla\Registry\Registry $params */
/** @var stdClass $module */
/** @var object $message Data from ModuleHelper::getMessage() */

// Get the WebAsset Manager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

// Load jQuery
$wa->useScript('jquery');
$wa->useScript('jquery-noconflict');
$wa->useScript('jquery-migrate');

// Add stylesheets and scripts
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

$db = JFactory::getDBO();
?>

<div class="<?php echo 'mod_prime_tile_search ' . $moduleclass_sfx; ?>">
    <?php if ($message->error) : ?>
        <div class="alert alert-error">
            <?php echo Text::_($message->error); ?>
        </div>
    <?php else : ?>
        <?php if ($message->show_title) : ?>
            <h3><?php echo Text::_('MOD_PRIME_TILE_SEARCH_TITLE'); ?></h3>
        <?php endif; ?>
        
		<div class="filter-groups">
			<div class="row">
				<div class="col col-lg-3">
					<?php 
					//filter area
					$query = "SELECT `id`, `area` FROM #__prime_areas WHERE 1 ORDER BY `id`";
					$db->setQuery($query);
					$areas = $db->loadObjectList();
					$arealist[]	    = JHTML::_('select.option',  '0', JText::_( 'MOD_PRIME_TILE_SEARCH_AREA' ), 'id', 'area' );
					$arealist	    = array_merge( $arealist, $areas );
					echo JHTML::_('select.genericlist',  $arealist, 'search_area', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'area');
					?>
				</div>
				<div class="col col-lg-3">
					<?php 
					//filter color
					$query = "SELECT `id`, `color` FROM #__prime_colors WHERE 1 ORDER BY `id`";
					$db->setQuery($query);
					$colorlist[]	    = JHTML::_('select.option',  '0', JText::_( 'MOD_PRIME_TILE_SEARCH_COLOR' ), 'id', 'color' );
					$colorlist	    = array_merge( $colorlist, $db->loadObjectList());
					echo JHTML::_('select.genericlist',  $colorlist, 'search_color', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'color');
					?>
				</div>
				<div class="col col-lg-3">
					<?php 
					//filter design
					$query = "SELECT `id`, `title` FROM #__prime_designs WHERE 1 ORDER BY `id`";
					$db->setQuery($query);
					$designlist[]	    = JHTML::_('select.option',  '0', JText::_( 'MOD_PRIME_TILE_SEARCH_DESIGN' ), 'id', 'title' );
					$designlist	    = array_merge( $designlist, $db->loadObjectList());
					echo JHTML::_('select.genericlist',  $designlist, 'search_design', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'title');
					?>
				</div>
				<div class="col col-lg-3">
					<?php 
					//filter type
					$query = "SELECT `id`, `type` FROM #__prime_types WHERE 1 ORDER BY `id`";
					$db->setQuery($query);
					$typelist[]	    = JHTML::_('select.option',  '0', JText::_( 'MOD_PRIME_TILE_SEARCH_TILESTYPES' ), 'id', 'type' );
					$typelist	    = array_merge( $typelist, $db->loadObjectList());
					echo JHTML::_('select.genericlist',  $typelist, 'search_type', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'type');
					?>
				</div>
				<div class="col col-lg-3">
					<?php 
					//filter size
					$query = "SELECT `id`, `size` FROM #__prime_sizes WHERE 1 ORDER BY `id`";
					$db->setQuery($query);
					$sizelist[]	    = JHTML::_('select.option',  '0', JText::_( 'MOD_PRIME_TILE_SEARCH_SIZE' ), 'id', 'size' );
					$sizelist	    = array_merge( $sizelist, $db->loadObjectList());
					echo JHTML::_('select.genericlist',  $sizelist, 'search_size', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'size');
					?>
				</div>
				<div class="col col-lg-3">
					<?php 
					//filter surface
					$query = "SELECT `id`, `surface` FROM #__prime_surfaces WHERE 1 ORDER BY `id`";
					$db->setQuery($query);
					$surfacelist[]	    = JHTML::_('select.option',  '0', JText::_( 'MOD_PRIME_TILE_SEARCH_SURFACE' ), 'id', 'surface' );
					$surfacelist	    = array_merge( $surfacelist, $db->loadObjectList());
					echo JHTML::_('select.genericlist',  $surfacelist, 'search_surface', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'surface');
					?>
				</div>
				<div class="col col-lg-3">
					<input type="button" value="<?php echo JText::_( 'MOD_PRIME_TILE_SEARCH_BOTTOM' );?>" class="inputbox btn-search" />
				</div>
			</div>
		</div>
		
		
        <div class="module-content">
            <?php echo $message->message; ?>
        </div>
    <?php endif; ?>
</div>

<script>
(function ($) {
	$('.btn-search').on( "click", function() {
        var base_url = "<?php echo JURI::root();?>"+"index.php?option=com_prime&view=tiles&list_layout=product";
		if($('#search_area').val() != '0') {
			base_url = base_url+"&area[]="+$('#search_area').val();
		};
		if($('#search_color').val() != '0') {
			base_url = base_url+"&color[]="+$('#search_color').val();
		};
		if($('#search_design').val() != '0') {
			base_url = base_url+"&design[]="+$('#search_design').val();
		};
		if($('#search_type').val() != '0') {
			base_url = base_url+"&type[]="+$('#search_type').val();
		};
		if($('#search_size').val() != '0') {
			base_url = base_url+"&size[]="+$('#search_size').val();
		};
		if($('#search_surface').val() != '0') {
			base_url = base_url+"&surface[]="+$('#search_surface').val();
		};
		window.location = base_url;
		return false;
    });
	
})(jQuery);
</script>