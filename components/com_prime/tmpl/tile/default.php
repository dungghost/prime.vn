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
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;


?>

<div class="item_fields">
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
    <?php endif;?>
	<table class="table">
		

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_TILE'); ?></th>
			<td><?php echo $this->item->tile; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_SKU'); ?></th>
			<td><?php echo $this->item->sku; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_DESCRIPTION'); ?></th>
			<td><?php echo nl2br($this->item->description); ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_BRAND'); ?></th>
			<td><?php echo $this->item->brand; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_LANGUAGE'); ?></th>
			<td><?php echo $this->item->language; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_GALLERY'); ?></th>
			<td><?php echo $this->item->gallery; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_DESIGN'); ?></th>
			<td><?php echo $this->item->design; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_THICKNESS'); ?></th>
			<td><?php echo $this->item->thickness; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_IMAGE'); ?></th>
			<td><?php echo $this->item->image; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_AREA'); ?></th>
			<td><?php echo $this->item->area; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_EFFECTS'); ?></th>
			<td><?php echo $this->item->effects; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_COLOR'); ?></th>
			<td><?php echo $this->item->color; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_TYPE'); ?></th>
			<td><?php echo $this->item->type; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_GROUTCOLOR'); ?></th>
			<td><?php echo $this->item->groutcolor; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_VARIATION'); ?></th>
			<td><?php echo $this->item->variation; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_SURFACE'); ?></th>
			<td><?php echo $this->item->surface; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_FACETILE'); ?></th>
			<td><?php echo $this->item->facetile; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_SIZE'); ?></th>
			<td><?php echo $this->item->size; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_ALIAS'); ?></th>
			<td><?php echo $this->item->alias; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_VIDEO'); ?></th>
			<td><?php echo $this->item->video; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_LIVE'); ?></th>
			<td><?php echo $this->item->live; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_COLLECTION'); ?></th>
			<td><?php echo $this->item->collection; ?></td>
		</tr>

	</table>
	

</div>

