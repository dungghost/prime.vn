<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Prime
 * @author     OneDigital <hello@onedigital.vn>
 * @copyright  @2025 PRIME GROUP
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\User\UserFactoryInterface;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getApplication()->getIdentity();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_prime') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'tileform.xml');
$canEdit    = $user->authorise('core.edit', 'com_prime') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'tileform.xml');
$canCheckin = $user->authorise('core.manage', 'com_prime');
$canChange  = $user->authorise('core.edit.state', 'com_prime');
$canDelete  = $user->authorise('core.delete', 'com_prime');

// Import CSS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_prime.list');

$db = JFactory::getDBO();
$app   	= Factory::getApplication();
$input 	= $app->getInput();
$checked_areas	= $input->getCmd('area', array());
$checked_colors	= $input->getCmd('color', array());
$checked_designs	= $input->getCmd('design', array());
$checked_types	= $input->getCmd('type', array());
$checked_sizes	= $input->getCmd('size', array());
$checked_surfaces	= $input->getCmd('surface', array());
$list_layout	= $input->getCmd('list_layout', '');
$Itemid	= $input->getCmd('Itemid', '');
$lang = JFactory::getLanguage();
$language = $lang->getTag();
?>

<style>
	.filter_row {
		display: flex;
		justify-content: space-between;
		margin-bottom: 1rem;
		align-items: center;
		padding: 0 1rem;
	}
	
	.filter_row a {
		color: #161616;
	}
	
	.filter_row a:hover {
		color: #AB3A3E;
	}
	
	.filter_heading  {
		margin-bottom: 2rem;
		padding: 0;
	}

	.filter_heading .filter-title {
		color: #8A9298;
		font-weight: 500;
		text-transform: uppercase;
	}
	
	.filter-title {
		font-size: 14px;
		text-transform: uppercase;
		color: #161616;
		font-weight: 600;
	}
	
	.toggle_filter {
		font-size: 14px;
	}
	
	.filter_reset .reset_filter {
		color: #161616;
	}
	
	.reset_group_filter {
		color: #161616;
		text-align: right;
		display: block;
	}
	
	.filter-groups {
		margin-bottom: 1.5rem;
		padding-bottom: 1rem;
		border-bottom: 1px solid #8A9298;
	}
	
	.filter_checkbox {
		display: none;
	}
	
	.filter_checkbox + span {
		display: flex;
		align-items: center;
		position: relative;
	}
	
	.filter_checkbox:checked + span,
	.filter_checkbox:checked + img + span	{
		color: #ab3a3e;
      font-weight: 600;
	}
	
	.filter_checkbox + span:before {
		content: '';
		width: 20px;
		height: 20px;
		border: 1px solid #D2CDC8;
		display: inline-block;
		margin-right: 10px;
		border-radius: 3px;
	}
	
	.filter_checkbox:checked + span:before {
		border-color: transparent;
		background: #ab3a3e;
	}
	
	.filter_checkbox + span:after {
		content: "";
		position: absolute;
		display: none;
	}
	
	.filter_checkbox:checked + span:after {
		display: block;
		left: 6px;
		top: 2px;
		width: 8px;
		height: 13px;
		border: solid white;
		border-width: 0 3px 3px 0;
		transform: rotate(45deg);
	}
	
	.filter-content {
		padding: 0 1rem;
		display: none;
	}
	
	.filter-content label {
		cursor: pointer;
	}
	
	.filter-content label:hover {
		color: #ab3a3e;
	}
	
	.filter-content label {
		display: flex;
		margin-bottom: 10px;
		font-size: 14px;
	}
	
	.filter-size-group {
		margin-bottom: 1rem;
	}
	
	.filter-size-group a {
		color: #161616;
	}
	
	.filter-size-group i {
		margin-right: 10px;
	}
	
	.filter-size-group-container {
		margin-left: 30px;
	}
	
	.filter_colors-wrap {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 1.5rem;
	}
	
	.filter_colors label {
		display: flex;
		align-items: center;
	}
	
	.filter_colors img {
		margin-right: 10px;
	}
	
	.filter_colors .filter_checkbox {
		display: none;
	}
	
	/* Products Page */
	.wrapper-product-list {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr 1fr;
		gap: 3rem 1rem;
	}
	.wrapper-search-product-list {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
		gap: 3rem 1rem;
	}

	.item-product {
		border: 1px solid #D2CDC8;
	}

	.wrap-item-product {
		text-align: center;
		display: flex;
		flex-direction: column;
		height: 100%;
		padding: 10px;
		border-bottom: 4px solid transparent;
		text-align: center;
		justify-content: space-between;
	}
	
	.animation-image {
		display: none;
	}

	.item-product:hover,
	.wrap-item-product:hover {
		border-bottom-color: #AB3A3E;
	}
	
	.item-product:hover .animation-image  {
		display: block;
	}
	
	.item-product:hover .product-image {
		display: none;
	}
	
	.wrap-product-image {
		margin-bottom: 1rem;
		height: 100%;
		display: flex;
		align-items: center;
	}
	
	.wrap-product-image img {
		box-shadow: 0 0 16px rgba(0, 0, 0, 0.2);
		max-height: 221px;
	}

	.product-image,
	.animation-image {
		width: 100%;
	}

	.product-type {
		text-transform: uppercasel;
		color: #161616;
		font-weight: 600;
		font-size: 0.9em;
	}

	.product-sku {
		color: #8A9298;
	}

	.wrap-item-product:hover .product-sku {
		color: #AB3A3E;
	}
	/* End Products Page */
	
	.filter_heading_button {
		background: #AB3A3E no-repeat;
		color: white;
		font-weight: 600;
		text-align: center;
		width: 156px;
		height: 48px;
		line-height: 48px;
		margin-bottom: 1rem;
		padding-left: 35px;
		background-image: url('/templates/shaper_helixultimate/images/button-filter.png');
		background-position: 35px center;
		display: none;
		cursor: pointer;
	}
	
	@media (max-width: 991px) {
		.wrapper-product-list {
			grid-template-columns: 1fr 1fr;
			gap: 2rem 1rem;
		}
		.wrapper-search-product-list {
			grid-template-columns: 1fr 1fr;
			gap: 2rem 1rem;
		}
		
		.filter-container {
			display: none;
		}
		
		.active-action {
			display: block;
		}
		
		.filter_heading_button {
			display: block;
		}
	}
	
	/* module */
	.sp-module.prime-tile-search {
		margin: 0 auto 3rem;
		background: white;
		padding: 2rem 0;
	}
	.filter-groups-row {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr;
		gap: 1rem;	
	}
	.chosen-container-single .chosen-single div {
		right: 1rem;
		width: 20px;
	}
	.chosen-container-single .chosen-single div b {
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
		background-repeat: no-repeat;
		background-position: center center;
		background-size: 17px 12px;
	}
	.chosen-container-active.chosen-with-drop .chosen-single div b {
		background-position: center center;
		transform: rotatex(180deg);
	}
	.chosen-container-single .chosen-search input[type="text"] {
		background: none;
	}
	.prime-tile-search .sp-module-title {
		font-size: 28px;
		font-weight: 500;
		text-align: center;
		margin-bottom: 1.5rem;
		text-transform: uppercase;
	}
	.filter-groups-button {
		display: flex;
		justify-content: center;
		align-items: center;
	}
	
	.action-search {
		background: transparent;
		border: 0;
		color: white;
		height: auto;
		text-transform: uppercase;
	}
	
	@media (max-width: 767px) {
		.filter-groups-row {
			grid-template-columns: 1fr;
		}
		
		.wrap-product-image img {
			max-height: 211px;
		}
	}
</style>

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif;?>

<?php 
if($list_layout == 'search') { 
	//load search module
	?>
	<div class="sp-module prime-tile-search">
	<h3 class="sp-module-title"><?php echo Text::_('Tìm Kiếm'); ?></h3>
	<div class="filter-groups-container">
		<div class="filter-groups-row">
			<div class="filter-groups-col">
				<?php 
				//filter type
				$query = "SELECT `id`, `type` FROM #__prime_types WHERE language = '".$language."' ORDER BY `ordering`";
				$db->setQuery($query);
				$typelist[]	    = JHTML::_('select.option',  '0', JText::_( 'COM_PRIME_FORM_LBL_FILTER_TYPE' ), 'id', 'type' );
				$typelist	    = array_merge( $typelist, $db->loadObjectList());
				echo JHTML::_('select.genericlist',  $typelist, 'search_type', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'type', $checked_types[0]);
				?>
			</div>
			<div class="filter-groups-col">
				<?php 
				//filter color
				$query = "SELECT `id`, `color` FROM #__prime_colors WHERE language = '".$language."' ORDER BY `ordering`";
				$db->setQuery($query);
				$colorlist[]	    = JHTML::_('select.option',  '0', JText::_( 'COM_PRIME_FORM_LBL_FILTER_COLOR' ), 'id', 'color' );
				$colorlist	    = array_merge( $colorlist, $db->loadObjectList());
				echo JHTML::_('select.genericlist',  $colorlist, 'search_color', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'color', $checked_colors[0]);
				?>
			</div>
			<div class="filter-groups-col">
				<?php 
				//filter size
				$query = "SELECT `id`, `size` FROM #__prime_sizes WHERE 1 ORDER BY `ordering`";
				$db->setQuery($query);
				$sizelist[]	    = JHTML::_('select.option',  '0', JText::_( 'COM_PRIME_FORM_LBL_FILTER_SIZE' ), 'id', 'size' );
				$sizelist	    = array_merge( $sizelist, $db->loadObjectList());
				echo JHTML::_('select.genericlist',  $sizelist, 'search_size', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'size', $checked_sizes[0]);
				?>
			</div>
			<div class="filter-groups-col">
				<?php 
				//filter surface
				$query = "SELECT `id`, `surface` FROM #__prime_surfaces WHERE language = '".$language."' ORDER BY `ordering`";
				$db->setQuery($query);
				$surfacelist[]	    = JHTML::_('select.option',  '0', JText::_( 'COM_PRIME_FORM_LBL_FILTER_SUFACES' ), 'id', 'surface' );
				$surfacelist	    = array_merge( $surfacelist, $db->loadObjectList());
				echo JHTML::_('select.genericlist',  $surfacelist, 'search_surface', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'surface', $checked_surfaces[0]);
				?>
			</div>
			<!--
			<div class="filter-groups-col">
				<?php 
				//filter design
				$query = "SELECT `id`, `title` FROM #__prime_designs WHERE language = '".$language."' ORDER BY `ordering`";
				$db->setQuery($query);
				$designlist[]	    = JHTML::_('select.option',  '0', JText::_( 'COM_PRIME_FORM_LBL_FILTER_DESIGN' ), 'id', 'title' );
				$designlist	    = array_merge( $designlist, $db->loadObjectList());
				echo JHTML::_('select.genericlist',  $designlist, 'search_design', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'title', $checked_designs[0]);
				?>
			</div>
			-->

			<div class="filter-groups-col">
				<?php 
				//filter area
				$query = "SELECT `id`, `area` FROM #__prime_areas WHERE language = '".$language."' ORDER BY `ordering`";
				$db->setQuery($query);
				$areas = $db->loadObjectList();
				$arealist[]	    = JHTML::_('select.option',  '0', JText::_( 'COM_PRIME_FORM_LBL_FILTER_AREAS' ), 'id', 'area' );
				$arealist	    = array_merge( $arealist, $areas );
				echo JHTML::_('select.genericlist',  $arealist, 'search_area', ' class="inputbox form-select advancedSelect " size="1" ', 'id', 'area', $checked_areas[0]);
				?>
			</div>
			<div class="filter-groups-col filter-groups-button btn btn-primary btn-search">
				<span class="fa fa-search"></span>
				<input type="button" value="<?php echo JText::_( 'Tìm Kiếm' );?>" class="action-search" />
			</div>
		</div>
	</div>
	</div>
	<?php
} 
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php 
	/*
	if(!empty($this->filterForm)) { 
		echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); 
	} 
	*/
	?>
	<div class="filter_heading_button">
		<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_LABEL'); ?>
	</div>
	<div class="row product-list-container">
	<?php if($list_layout != 'search') { ?>
		<div class="col-lg-3 filter-container">
			<div class="filter_row filter_heading">
				<div class="filter-title">
					<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_LABEL'); ?>
				</div>
				<div class="filter_action filter_reset">
					<a href="#" class="reset_filter"><?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_CLEAR_ALL'); ?></a>
				</div>
			</div>
			
			<div class="filter-groups">
				<div class="filter_row">
					<div class="filter-title">
						<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_AREAS'); ?>
					</div>
					<div class="toggle_filter areas_filter" data-filter-type="areas">
						<a href="#" class="filter-minus" style="display: none;"><i class="fa-solid fa-minus"></i></a>
						<a href="#" class="filter-plus"><i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
				<div class="filter-content filter_areas">
					<?php 
					//filter area
					$query = "SELECT `id`, `area` FROM #__prime_areas WHERE language = '".$language."' ORDER BY `ordering`";
					$db->setQuery($query);
					$areas = $db->loadObjectList();
					$areas_filter = false;
					foreach($areas AS $area) {
					?>
						<label>
							<input class="filter_checkbox" type="checkbox" name="areas[]" value="<?php echo $area->id;?>" onclick="" <?php if(in_array($area->id, $checked_areas)){ echo ' checked="checked" '; $areas_filter=true;} ?> /> 
							<span><?php echo $area->area;?></span>
						</label>
					<?php
					}
					?>
				</div>
				<div class="">
					<a href="#" class="reset_group_filter" data-filter-type="areas"><?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_CLEAR'); ?></a>
				</div>
			</div>
			
			<div class="filter-groups">
				<div class="filter_row">
					<div class="filter-title">
						<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_COLOR'); ?>
					</div>
					<div class="toggle_filter colors_filter" data-filter-type="colors">
						<a href="#" class="filter-minus" style="display: none;"><i class="fa-solid fa-minus"></i></a>
						<a href="#" class="filter-plus"><i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
				<div class="filter-content filter_colors">
					<div class="filter_colors-wrap">
						<?php 
						//filter area
						$query = "SELECT `id`, `color`, `image` FROM #__prime_colors WHERE language = '".$language."' ORDER BY `ordering`";
						$db->setQuery($query);
						$colors = $db->loadObjectList();
						$colors_filter = false;
						foreach($colors AS $color) {
						?>
							<label class="color-<?php echo $color->id;?>">
								<input class="filter_checkbox" type="checkbox" name="colors[]" value="<?php echo $color->id;?>" <?php if(in_array($color->id, $checked_colors)){ echo ' checked="checked" ';$colors_filter = true;} ?> /> 
								<?php if($color->image) : ?>
									<img src="<?php echo $color->image;?>" />
								<?php endif; ?>
								<span><?php echo $color->color;?></span>
							</label>
						<?php
						}
						?>
					</div>
				</div>
				<div class="">
					<a href="#" class="reset_group_filter" data-filter-type="colors"><?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_CLEAR'); ?></a>
				</div>
			</div>
			
			<div class="filter-groups">
				<div class="filter_row">
					<div class="filter-title">
						<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_DESIGN'); ?>
					</div>
					<div class="toggle_filter designs_filter" data-filter-type="designs">
						<a href="#" class="filter-minus" style="display: none;"><i class="fa-solid fa-minus"></i></a>
						<a href="#" class="filter-plus"><i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
				<div class="filter-content filter_designs">
					<?php 
					//filter area
					$query = "SELECT `id`, `title` FROM #__prime_designs WHERE language = '".$language."' ORDER BY `ordering`";
					$db->setQuery($query);
					$designs = $db->loadObjectList();
					$designs_filter = false;
					foreach($designs AS $design) {
					?>
						<label>
							<input class="filter_checkbox" type="checkbox" name="designs[]" value="<?php echo $design->id;?>" <?php if(in_array($design->id, $checked_designs)){ echo ' checked="checked" ';$designs_filter = true;} ?> /> 
							<span><?php echo $design->title;?></span>
						</label>
					<?php
					}
					?>
				</div>
				<div class="">
					<a href="#" class="reset_group_filter" data-filter-type="designs"><?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_CLEAR'); ?></a>
				</div>
			</div>
			
			<div class="filter-groups">
				<div class="filter_row">
					<div class="filter-title">
						<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_TYPE'); ?>
					</div>
					<div class="toggle_filter types_filter" data-filter-type="types">
						<a href="#" class="filter-minus" style="display: none;"><i class="fa-solid fa-minus"></i></a>
						<a href="#" class="filter-plus"><i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
				<div class="filter-content filter_types">
					<?php 
					//filter area
					$query = "SELECT `id`, `type` FROM #__prime_types WHERE language = '".$language."' ORDER BY `ordering`";
					$db->setQuery($query);
					$types = $db->loadObjectList();
					$types_filter = false;
					foreach($types AS $type) {
					?>
						<label>
							<input class="filter_checkbox" type="checkbox" name="types[]" value="<?php echo $type->id;?>" <?php if(in_array($type->id, $checked_types)){ echo ' checked="checked" ';$types_filter = true;} ?> /> 
							<span><?php echo $type->type;?></span>
						</label>
					<?php
					}
					?>
				</div>
				<div class="">
					<a href="#" class="reset_group_filter" data-filter-type="types"><?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_CLEAR'); ?></a>
				</div>
			</div>
			
			<div class="filter-groups">
				<div class="filter_row">
					<div class="filter-title">
						<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_SIZE'); ?>
					</div>
					<div class="toggle_filter sizes_filter" data-filter-type="sizes">
						<a href="#" class="filter-minus" style="display: none;"><i class="fa-solid fa-minus"></i></a>
						<a href="#" class="filter-plus"><i class="fa-solid fa-plus"></i></a>
				</div>
				</div>
				<div class="filter-content filter_sizes">
					<?php 
					//filter area
					$query = "SELECT * FROM #__prime_group_size WHERE state = 1 ORDER BY ordering";
					$db->setQuery($query);
					$group_sizes = $db->loadObjectList();
					$sizes_filter = false;
					$sizes_filters = array();
					foreach($group_sizes AS $group_size) {
						$query = "SELECT `id`, `size` FROM #__prime_sizes WHERE state = 1 AND `group` = '".$group_size->id."' ORDER BY ordering ";
						$db->setQuery($query);
						$sizes = $db->loadObjectList();
						$sizes_filters[$group_size->id] = false;
						if(count($sizes)) {
							?>
							<div class="filter-size-group filter-size-group-<?php echo $group_size->id;?>" data-size-group-id="<?php echo $group_size->id;?>">
								<a href="#" class="filter-size-minus" style="display: none;"><i class="fa-solid icon-arrow-down"></i></a>
								<a href="#" class="filter-size-plus" ><i class="fa-solid icon-arrow-right"></i></a>
								<span><?php echo $group_size->group;?></span>
							</div>
							<?php
							foreach($sizes AS $size) {
							?>
								<label class="filter-size-group-container size-group-<?php echo $group_size->id;?>" style="display: none;">
									<input class="filter_checkbox" type="checkbox" name="sizes[]" value="<?php echo $size->id;?>" <?php if(in_array($size->id, $checked_sizes)){ echo ' checked="checked" ';$sizes_filter = true;$sizes_filters[$group_size->id] = true;} ?> /> 
									<span><?php echo $size->size;?></span>
								</label>
							<?php
							}
						}
					}
					?>
				</div>
				<div class="">
					<a href="#" class="reset_group_filter" data-filter-type="sizes"><?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_CLEAR'); ?></a>
				</div>
			</div>
			
			<div class="filter-groups">
				<div class="filter_row">
					<div class="filter-title">
						<?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_SUFACES'); ?>
					</div>
					<div class="toggle_filter surfaces_filter" data-filter-type="surfaces">
						<a href="#" class="filter-minus" style="display: none;"><i class="fa-solid fa-minus"></i></a>
						<a href="#" class="filter-plus"><i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
				<div class="filter-content filter_surfaces">
					<?php 
					//filter area
					$query = "SELECT `id`, `surface` FROM #__prime_surfaces WHERE language = '".$language."' ORDER BY `ordering`";
					$db->setQuery($query);
					$surfaces = $db->loadObjectList();
					$surfaces_filter = false;
					foreach($surfaces AS $surface) {
					?>
						<label>
							<input class="filter_checkbox" type="checkbox" name="surfaces[]" value="<?php echo $surface->id;?>" <?php if(in_array($surface->id, $checked_surfaces)){ echo ' checked="checked" ';$surfaces_filter = true;} ?> /> 
							<span><?php echo $surface->surface;?></span>
						</label>
					<?php
					}
					?>
				</div>
				<div class="">
					<a href="#" class="reset_group_filter" data-filter-type="surfaces"><?php echo Text::_('COM_PRIME_FORM_LBL_FILTER_CLEAR'); ?></a>
				</div>
			</div>
			
		</div>
	<?php } ?>
		<div class="<?php if($list_layout == 'search') { echo "col-lg-12"; }else{ echo "col-lg-9";}?>">
			<div class="<?php if($list_layout == 'search') { echo "wrapper-search-product-list"; }else{ echo "wrapper-product-list";}?>">
				<?php foreach ($this->items as $i => $item) : ?>
					<?php $canEdit = $user->authorise('core.edit', 'com_prime'); ?>
					<div class="item-product">
						<?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_prime.' . $item->id) || $item->checked_out == Factory::getApplication()->getIdentity()->id; ?>
						<?php if($canCheckin && $item->checked_out > 0) : ?>
							<a href="<?php echo Route::_('index.php?option=com_prime&task=tile.checkin&id=' . $item->id .'&'. Session::getFormToken() .'=1'); ?>">
								<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'tile.', false); ?>
							</a>
						<?php endif; ?>
							<a class="wrap-item-product" href="<?php echo JURI::root().('index.php?option=com_prime&view=tile&id='.(int) $item->id.'&Itemid='.$Itemid); ?>">
								<!--<img class="product-image" src="<?php //echo $item->image; ?>" />-->
								<?php
									$imageProductPath = JPATH_BASE . '/images/product/' . $item->sku . '/' . $item->sku . '.jpg';
									$imageUrlProduct = JURI::base() . 'images/product/' . $item->sku . '/' . $item->sku . '.jpg';
									
									$defaultImageUrl = JURI::base() . 'images/product/notfound.jpg';
									
									$imageAnimationPath = JPATH_BASE . '/images/phoi-canh/'.$item->sku.'.jpg';
									$imageUrlAnimation = JURI::base() . 'images/phoi-canh/'.$item->sku.'.jpg';
									
									$srcImgProduct = file_exists($imageProductPath) ? $imageUrlProduct : $defaultImageUrl;
									
									$srcImageAnimation = file_exists($imageAnimationPath) ? $imageUrlAnimation : $defaultImageUrl;
								?>
								<div class="wrap-product-image">
									<img class="product-image" src="<?php echo $srcImgProduct; ?>" />
									<img class="animation-image" src="<?php echo $srcImageAnimation; ?>" />
								</div>
								<div class="product-info">
									<div class="product-type">
										<span clas="product-type__color"><?php echo $item->color; ?></span>
										-
										<span clas="product-type__size"><?php echo $item->size; ?></span>
									</div>
									<div class="product-sku">
										<?php echo $this->escape($item->sku); ?>
									</div>
								</div>
							</a>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="pagination">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		</div>
	</div>
	
	
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_prime&task=tileform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_PRIME_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value=""/>
	<input type="hidden" name="filter_order_Dir" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php
	if($canDelete) {
		$wa->addInlineScript("
			jQuery(document).ready(function () {
				jQuery('.delete-button').click(deleteItem);
			});

			function deleteItem() {

				if (!confirm(\"" . Text::_('COM_PRIME_DELETE_MESSAGE') . "\")) {
					return false;
				}
			}
		", [], [], ["jquery"]);
	}
?>
<script>
(function ($) {
	$('.reset_filter').on( "click", function() {
        var base_url = "<?php echo JURI::root();?>"+"index.php?option=com_prime&view=tiles";
		window.location = base_url;
		return false;
    });
	$('.reset_group_filter').on( "click", function() {
		filter_type = $(this).data("filter-type");
        $('.filter_'+filter_type).find(':checkbox:checked').each(function(){
			$(this).prop('checked', false);
		});
		build_filter_url();
		return false;
    });
	$('.filter_checkbox').change(function() {
        build_filter_url();
		return false;
    });
	$('.toggle_filter').on( "click", function() {
		filter_type = $(this).data("filter-type");
		$(this).find(".filter-minus").toggle();
		$(this).find(".filter-plus").toggle();
		$(".filter_"+filter_type).toggle(300);
		return false;
    });
	$('.filter-size-group').on( "click", function() {
		size_group_id = $(this).data("size-group-id");
		$(this).find(".filter-size-minus").toggle();
		$(this).find(".filter-size-plus").toggle();
		$(".size-group-"+size_group_id).toggle(300);
		return false;
    });
	$('.filter_heading_button').on( "click", function() {
		$('.filter-container').toggleClass('active-action');
		return false;
    });
	
	$('.btn-search').on( "click", function() {
        var base_url = "<?php echo JURI::root();?>"+"index.php?option=com_prime&view=tiles&list_layout=search";
		if($('#search_area').length) {
			if($('#search_area').val() != '0') {
				base_url = base_url+"&area[]="+$('#search_area').val();
			}
		}
		if($('#search_color').length) {
			if($('#search_color').val() != '0') {
				base_url = base_url+"&color[]="+$('#search_color').val();
			}
		}
		if($('#search_design').length) {
			if($('#search_design').val() != '0') {
				base_url = base_url+"&design[]="+$('#search_design').val();
			}
		}
		if($('#search_type').length) {
			if($('#search_type').val() != '0') {
				base_url = base_url+"&type[]="+$('#search_type').val();
			}
		}
		if($('#search_size').length) {
			if($('#search_size').val() != '0') {
				base_url = base_url+"&size[]="+$('#search_size').val();
			}
		}
		if($('#search_surface').length) {
			if($('#search_surface').val() != '0') {
				base_url = base_url+"&surface[]="+$('#search_surface').val();
			}
		}
		window.location = base_url;
		return false;
    });
    
    // Hover product image
	<?php 
	if($areas_filter) {
		echo '$(".areas_filter").trigger("click");';
	}
	if($colors_filter) {
		echo '$(".colors_filter").trigger("click");';
	}
	if($designs_filter) {
		echo '$(".designs_filter").trigger("click");';
	}
	if($types_filter) {
		echo '$(".types_filter").trigger("click");';
	}
	if($sizes_filter) {
		echo '$(".sizes_filter").trigger("click");';
		foreach($group_sizes AS $group_size) {
			if($sizes_filters[$group_size->id]) {
				echo '$(".filter-size-group-'.$group_size->id.'").trigger("click");';
			}
		}
	}
	if($surfaces_filter) {
		echo '$(".surfaces_filter").trigger("click");';
	}
	?>
    
})(jQuery);
function build_filter_url() {
	var base_url = "<?php echo JURI::root();?>"+"index.php?option=com_prime&view=tiles";
	//areas
	$('.filter_areas').find(':checkbox:checked').each(function(){
        base_url = base_url+"&area[]="+$(this).val();
    });
	//filter_colors
	$('.filter_colors').find(':checkbox:checked').each(function(){
        base_url = base_url+"&color[]="+$(this).val();
    });
	//filter_designs
	$('.filter_designs').find(':checkbox:checked').each(function(){
        base_url = base_url+"&design[]="+$(this).val();
    });
	//filter_types
	$('.filter_types').find(':checkbox:checked').each(function(){
        base_url = base_url+"&type[]="+$(this).val();
    });
	//filter_sizes
	$('.filter_sizes').find(':checkbox:checked').each(function(){
        base_url = base_url+"&size[]="+$(this).val();
    });
	//filter_surfaces
	$('.filter_surfaces').find(':checkbox:checked').each(function(){
        base_url = base_url+"&surface[]="+$(this).val();
    });
	//console.log(base_url);
	window.location = base_url;
	return false;
}
</script>