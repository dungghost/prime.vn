<?php
\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

?>

<form action="<?php echo Route::_('index.php?option=com_prime&task=tiles.confirmImport'); ?>"
      method="post"
      enctype="multipart/form-data"
      name="adminForm"
      id="adminForm"
      class="form-validate">

    <div class="form-group">
        <label for="import_file" class="form-label">
            <?php echo Text::_('COM_PRIME_IMPORT_FILE_LABEL'); ?>
        </label>
        <input type="file" name="import_file" id="import_file" class="form-control" required>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">
            <span class="icon-upload"></span>
            <?php echo Text::_('COM_PRIME_IMPORT_BUTTON'); ?>
        </button>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>
