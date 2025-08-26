<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Prime
 * @author     OneDigital <hello@onedigital.vn>
 * @copyright  @2025 PRIME GROUP
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Prime\Component\Prime\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;

require_once JPATH_COMPONENT . '/libraries/simplexlsx-master/src/SimpleXLSX.php'; // Adjust path as needed
use Shuchkin\SimpleXLSX;

/**
 * Tiles list controller class.
 *
 * @since  1.0.0
 */
class TilesController extends AdminController
{
	/**
	 * Method to clone existing Tiles
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function duplicate()
	{
		// Check for request forgeries
		$this->checkToken();

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new \Exception(Text::_('COM_PRIME_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Text::_('COM_PRIME_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (\Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_prime&view=tiles');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Tile', $prefix = 'Administrator', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 *
	 * @throws  Exception
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$pks   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		Factory::getApplication()->close();
	}
        
        public function confirmImport()
        {
            $app   = Factory::getApplication();
            $input = $app->input;

            $file = $input->files->get('import_file');

            if ($file['error'] === 0) {
                // Save the file temporarily for later processing
                $tmpPath = Path::clean($file['tmp_name']);

                $tmpName = uniqid('import_', true) . '_' . $file['name'];
                $dest    = JPATH_ADMINISTRATOR . '/components/com_prime/tmp/' . $tmpName;

                \Joomla\CMS\Filesystem\File::upload($file['tmp_name'], $dest);

                // Store filename in session to be used in actual import
                $app->setUserState('com_prime.import_file', $dest);

                // Redirect to confirmation view
                $this->setRedirect('index.php?option=com_prime&view=import&layout=confirm');
                return;
            }

            $this->setRedirect('index.php?option=com_prime&view=import', 'File upload failed', 'error');
        }

        public function doImport()
        {
            $app = Factory::getApplication();
            $file = $app->getUserState('com_prime.import_file');
            $db = Factory::getDbo();

            if (\Joomla\CMS\Filesystem\File::exists($file)) {
                // Do the actual import here (parse, import, etc.)
                // Example: read CSV, import records
                if ($xlsx = SimpleXLSX::parse($file)) {
                    $rows = ($xlsx->rows());
                    $rows = array_slice($rows, 2);// remove 2 first row
                    foreach ($rows as $row) {
                        $tile = new \stdClass();
                        $tile->tile = $row[1];
                        $tile->sku = $row[1];
                        $tile->brand = $row[2];
                        $tile->effects = $row[3];
                        $tile->thickness  = $row[4];
                        $tile->groutcolor  = $row[5];
                        $tile->variation  = $row[6];
                        $tile->facetile  = $row[7];
                        $tile->area  = $row[8];
                        $tile->design  = $row[9];
                        $tile->color  = $row[10];
                        $tile->size  = $row[11];
                        $tile->type  = $row[12];
                        $tile->surface  = $row[13];
                        $tile->image  = $row[14];
                        $tile->live  = $row[16];
                        $tile->video  = $row[17];
                        $tile->language  = $row[18];
						
                        $db->insertObject('#__prime_tiles', $tile);
                        
                    }
                } else {
                    echo SimpleXLSX::parseError();
                }
                
                // Clean up
                //\Joomla\CMS\Filesystem\File::delete($file);
                $app->setUserState('com_prime.import_file', null);
                $this->setRedirect('index.php?option=com_prime&view=tiles', 'Import completed successfully');
            } else {
                $this->setRedirect('index.php?option=com_prime&view=import', 'Import file not found', 'error');
            }
        }

}
