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

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');
?>

<form
	action="<?php echo Route::_('index.php?option=com_prime&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="tile-form" class="form-validate form-horizontal">

	
	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'product')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'product', Text::_('COM_PRIME_TAB_PRODUCT', true)); ?>
	<div class="row-fluid">
		<div class="col-md-12 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo Text::_('COM_PRIME_FIELDSET_CONTENT'); ?></legend>
				<?php echo $this->form->renderField('tile'); ?>
				<?php echo $this->form->renderField('sku'); ?>
				<?php echo $this->form->renderField('description'); ?>
				<?php echo $this->form->renderField('brand'); ?>
				<?php echo $this->form->renderField('language'); ?>
				<?php echo $this->form->renderField('gallery'); ?>
				<?php echo $this->form->renderField('design'); ?>
				<?php echo $this->form->renderField('thickness'); ?>
				<?php echo $this->form->renderField('image'); ?>
				<?php echo $this->form->renderField('area'); ?>
				<?php echo $this->form->renderField('effects'); ?>
				<?php echo $this->form->renderField('color'); ?>
				<?php echo $this->form->renderField('type'); ?>
				<?php echo $this->form->renderField('groutcolor'); ?>
				<?php echo $this->form->renderField('variation'); ?>
				<?php echo $this->form->renderField('surface'); ?>
				<?php echo $this->form->renderField('facetile'); ?>
				<?php echo $this->form->renderField('size'); ?>
				<?php echo $this->form->renderField('alias'); ?>
				<?php echo $this->form->renderField('video'); ?>
				<?php echo $this->form->renderField('live'); ?>
				<?php echo $this->form->renderField('collection'); ?>
			</fieldset>
		</div>
	</div>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>
	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>

	
	<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>

</form>
