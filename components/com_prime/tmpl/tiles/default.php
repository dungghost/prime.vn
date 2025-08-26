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

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getApplication()->getIdentity();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_prime');
$canEdit    = $user->authorise('core.edit', 'com_prime');
$canCheckin = $user->authorise('core.manage', 'com_prime');
$canChange  = $user->authorise('core.edit.state', 'com_prime');
$canDelete  = $user->authorise('core.delete', 'com_prime');

// Import CSS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_prime.list');
?>

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif;?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
    <?php if(!empty($this->filterForm)) { echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filtersHidden' => false))); } ?>
	<div class="table-responsive">
		<table class="table table-striped" id="tileList">
			<thead>
			<tr>
				
					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_SKU', 'a.sku', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_BRAND', 'a.brand', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_DESIGN', 'a.design', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_THICKNESS', 'a.thickness', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_AREA', 'a.area', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_COLOR', 'a.color', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_TYPE', 'a.type', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_SIZE', 'a.size', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_PRIME_TILES_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
					</th>

					<th >
						<?php echo HTMLHelper::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
					</th>

						<?php if ($canEdit || $canDelete): ?>
					<th class="center">
						<?php echo Text::_('COM_PRIME_TILES_ACTIONS'); ?>
					</th>
					<?php endif; ?>

			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) + 2 : 12; ?>">
					<div class="pagination">
						<?php echo $this->pagination->getPagesLinks(); ?>
					</div>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php if (!empty($this->items)) : ?>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					
					<td>
						<?php echo $item->id; ?>
					</td>
					<td>
						<?php if ($canCheckin && $item->checked_out) : ?>
							<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'tiles.', $canCheckin); ?>
						<?php endif; ?>
						<a href="<?php echo Route::_('index.php?option=com_prime&view=tile&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->sku); ?></a>
					</td>
					<td>
						<?php echo $item->brand; ?>
					</td>
					<td>
						<?php echo $item->design; ?>
					</td>
					<td>
						<?php echo $item->thickness; ?>
					</td>
					<td>
						<?php echo $item->area; ?>
					</td>
					<td>
						<?php echo $item->color; ?>
					</td>
					<td>
						<?php echo $item->type; ?>
					</td>
					<td>
						<?php echo $item->size; ?>
					</td>
					<td>
						<?php echo $item->language; ?>
					</td>
					<td class="center">
						<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'tiles.', $canChange, 'cb'); ?>
					</td>
					<?php if ($canEdit || $canDelete): ?>
						<td class="center">
                            </td>
					<?php endif; ?>

				</tr>
			<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="12" class="text-center">
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_prime&task=tileform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_PRIME_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>