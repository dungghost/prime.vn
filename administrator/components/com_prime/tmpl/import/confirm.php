<?php
\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

$app = \Joomla\CMS\Factory::getApplication();
$importFile = $app->getUserState('com_prime.import_file');

?>

<h3><?php echo Text::_('COM_PRIME_CONFIRM_IMPORT'); ?></h3>

<p><?php echo Text::_('COM_PRIME_IMPORT_FILE_PATH'); ?>: <strong><?php echo basename($importFile); ?></strong></p>

<form action="<?php echo Route::_('index.php?option=com_prime&task=tiles.doImport'); ?>" method="post" name="confirmForm">
    <input type="hidden" name="confirmed" value="1">
    <button type="submit" class="btn btn-danger">
        <span class="icon-check"></span> <?php echo Text::_('COM_PRIME_CONFIRM_CONTINUE'); ?>
    </button>
    <a class="btn btn-secondary" href="<?php echo Route::_('index.php?option=com_prime&view=import'); ?>">
        <?php echo Text::_('JCANCEL'); ?>
    </a>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>
