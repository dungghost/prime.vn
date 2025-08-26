<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Prime
 * @author     OneDigital <hello@onedigital.vn>
 * @copyright  @2025 PRIME GROUP
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Prime\Component\Prime\Site\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;
use \Joomla\CMS\MVC\Model\ItemModel;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\CMS\Object\CMSObject;
use \Joomla\CMS\User\UserFactoryInterface;
use \Prime\Component\Prime\Site\Helper\PrimeHelper;

/**
 * Prime model.
 *
 * @since  1.0.0
 */
class TileModel extends ItemModel
{
	public $_item;

	

	

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 *
	 * @throws Exception
	 */
	protected function populateState()
	{
		$app  = Factory::getApplication('com_prime');
		$user = $app->getIdentity();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_prime')) && (!$user->authorise('core.edit', 'com_prime')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (Factory::getApplication()->input->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_prime.edit.tile.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('id');
			Factory::getApplication()->setUserState('com_prime.edit.tile.id', $id);
		}

		$this->setState('tile.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('tile.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @throws Exception
	 */
	public function getItem($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('tile.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table && $table->load($id))
			{
				

				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if (isset($table->state) && $table->state != $published)
					{
						throw new \Exception(Text::_('COM_PRIME_ITEM_NOT_LOADED'), 403);
					}
				}

				// Convert the Table to a clean CMSObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, CMSObject::class);

				
			}

			if (empty($this->_item))
			{
				throw new \Exception(Text::_('COM_PRIME_ITEM_NOT_LOADED'), 404);
			}
		}

		

		 $container = \Joomla\CMS\Factory::getContainer();

		 $userFactory = $container->get(UserFactoryInterface::class);

		if (isset($this->_item->created_by))
		{
			$user = $userFactory->loadUserById($this->_item->created_by);
			$this->_item->created_by_name = $user->name;
		}

		 $container = \Joomla\CMS\Factory::getContainer();

		 $userFactory = $container->get(UserFactoryInterface::class);

		if (isset($this->_item->modified_by))
		{
			$user = $userFactory->loadUserById($this->_item->modified_by);
			$this->_item->modified_by_name = $user->name;
		}

		if (isset($this->_item->brand) && $this->_item->brand != '')
		{
			if (is_object($this->_item->brand))
			{
				$this->_item->brand = ArrayHelper::fromObject($this->_item->brand);
			}

			$values = (is_array($this->_item->brand)) ? $this->_item->brand : explode(',',$this->_item->brand);

			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_brands_4126008`.`brand`')
					->from($db->quoteName('#__prime_brands', '#__prime_brands_4126008'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->brand;
				}
			}

			$this->_item->brand = !empty($textValue) ? implode(', ', $textValue) : $this->_item->brand;

		}

		if (isset($this->_item->design) && $this->_item->design != '')
		{
			if (is_object($this->_item->design))
			{
				$this->_item->design = ArrayHelper::fromObject($this->_item->design);
			}

			$values = (is_array($this->_item->design)) ? $this->_item->design : explode(',',$this->_item->design);
			$this->_item->design_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_designs_4128066`.`title`')
					->from($db->quoteName('#__prime_designs', '#__prime_designs_4128066'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->title;
				}
			}

			$this->_item->design = !empty($textValue) ? implode(', ', $textValue) : $this->_item->design;

		}

		if (isset($this->_item->thickness) && $this->_item->thickness != '')
		{
			if (is_object($this->_item->thickness))
			{
				$this->_item->thickness = ArrayHelper::fromObject($this->_item->thickness);
			}

			$values = (is_array($this->_item->thickness)) ? $this->_item->thickness : explode(',',$this->_item->thickness);
			$this->_item->thickness_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_thickness_4128072`.`thickness`')
					->from($db->quoteName('#__prime_thickness', '#__prime_thickness_4128072'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->thickness;
				}
			}

			$this->_item->thickness = !empty($textValue) ? implode(', ', $textValue) : $this->_item->thickness;

		}

		if (isset($this->_item->area) && $this->_item->area != '')
		{
			if (is_object($this->_item->area))
			{
				$this->_item->area = ArrayHelper::fromObject($this->_item->area);
			}

			$values = (is_array($this->_item->area)) ? $this->_item->area : explode(',',$this->_item->area);
			
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = "SELECT id, area  FROM #__prime_areas
							WHERE id = '$value' ";

				$db->setQuery($query);
				$results = $db->loadObject();
				$this->_item->area_ids[] = $results->id;
				$this->_item->area_titles[] = $results->area;
				if ($results)
				{
					$textValue[] = $results->area;
				}
			}

			$this->_item->area = !empty($textValue) ? implode(', ', $textValue) : $this->_item->area;
		}

		if (isset($this->_item->effects) && $this->_item->effects != '')
		{
			if (is_object($this->_item->effects))
			{
				$this->_item->effects = ArrayHelper::fromObject($this->_item->effects);
			}

			$values = (is_array($this->_item->effects)) ? $this->_item->effects : explode(',',$this->_item->effects);
			
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = "SELECT id, effect, page, image FROM #__prime_effects WHERE id = '$value' ";

				$db->setQuery($query);
				$results = $db->loadObject();
				$this->_item->effect_ids[] = $results->id;
				$this->_item->effect_pages[] = $results->page;
				$this->_item->effect_images[] = $results->image;
				$this->_item->effect_titles[] = $results->effect;
				if ($results)
				{
					$textValue[] = $results->effect;
				}
			}

			$this->_item->effects = !empty($textValue) ? implode(', ', $textValue) : $this->_item->effects;
		}

		if (isset($this->_item->color) && $this->_item->color != '')
		{
			if (is_object($this->_item->color))
			{
				$this->_item->color = ArrayHelper::fromObject($this->_item->color);
			}

			$values = (is_array($this->_item->color)) ? $this->_item->color : explode(',',$this->_item->color);
			$this->_item->color_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_colors_4128083`.`color`')
					->from($db->quoteName('#__prime_colors', '#__prime_colors_4128083'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->color;
				}
			}

			$this->_item->color = !empty($textValue) ? implode(', ', $textValue) : $this->_item->color;

		}

		if (isset($this->_item->type) && $this->_item->type != '')
		{
			if (is_object($this->_item->type))
			{
				$this->_item->type = ArrayHelper::fromObject($this->_item->type);
			}
			$this->_item->type_id = $this->_item->type;

			$values = (is_array($this->_item->type)) ? $this->_item->type : explode(',',$this->_item->type);
			$this->_item->type_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_types_4128086`.`type`')
					->from($db->quoteName('#__prime_types', '#__prime_types_4128086'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$this->_item->type_ids[] = $value;
					$this->_item->type_titles[] = $results->type;
					$textValue[] = $results->type;
				}
			}

			$this->_item->type = !empty($textValue) ? implode(', ', $textValue) : $this->_item->type;
			

		}

		if (isset($this->_item->groutcolor) && $this->_item->groutcolor != '')
		{
			if (is_object($this->_item->groutcolor))
			{
				$this->_item->groutcolor = ArrayHelper::fromObject($this->_item->groutcolor);
			}

			$values = (is_array($this->_item->groutcolor)) ? $this->_item->groutcolor : explode(',',$this->_item->groutcolor);
			
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_groutcolors_4128089`.`groutcolor`, id, page, image ')
					->from($db->quoteName('#__prime_groutcolors', '#__prime_groutcolors_4128089'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();
				$this->_item->groutcolor_ids[] = $results->id;
				$this->_item->groutcolor_pages[] = $results->page;
				$this->_item->groutcolor_images[] = $results->image;
				$this->_item->groutcolor_groutcolors[] = $results->groutcolor;
				if ($results)
				{
					$textValue[] = $results->groutcolor;
				}
			}

			$this->_item->groutcolor = !empty($textValue) ? implode(', ', $textValue) : $this->_item->groutcolor;

		}

		if (isset($this->_item->variation) && $this->_item->variation != '')
		{
			if (is_object($this->_item->variation))
			{
				$this->_item->variation = ArrayHelper::fromObject($this->_item->variation);
			}

			$values = (is_array($this->_item->variation)) ? $this->_item->variation : explode(',',$this->_item->variation);
			$this->_item->variation_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_variation_colors_4128092`.`variation`')
					->from($db->quoteName('#__prime_variation_colors', '#__prime_variation_colors_4128092'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->variation;
				}
			}

			$this->_item->variation = !empty($textValue) ? implode(', ', $textValue) : $this->_item->variation;

		}

		if (isset($this->_item->surface) && $this->_item->surface != '')
		{
			if (is_object($this->_item->surface))
			{
				$this->_item->surface = ArrayHelper::fromObject($this->_item->surface);
			}

			$values = (is_array($this->_item->surface)) ? $this->_item->surface : explode(',',$this->_item->surface);
			$this->_item->surface_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_surfaces_4128095`.`surface`')
					->from($db->quoteName('#__prime_surfaces', '#__prime_surfaces_4128095'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->surface;
				}
			}

			$this->_item->surface = !empty($textValue) ? implode(', ', $textValue) : $this->_item->surface;

		}

		if (isset($this->_item->facetile) && $this->_item->facetile != '')
		{
			if (is_object($this->_item->facetile))
			{
				$this->_item->facetile = ArrayHelper::fromObject($this->_item->facetile);
			}

			$values = (is_array($this->_item->facetile)) ? $this->_item->facetile : explode(',',$this->_item->facetile);
			$this->_item->facetile_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_faces_4128098`.`face`')
					->from($db->quoteName('#__prime_faces', '#__prime_faces_4128098'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->face;
				}
			}

			$this->_item->facetile = !empty($textValue) ? implode(', ', $textValue) : $this->_item->facetile;

		}

		if (isset($this->_item->size) && $this->_item->size != '')
		{
			if (is_object($this->_item->size))
			{
				$this->_item->size = ArrayHelper::fromObject($this->_item->size);
			}

			$values = (is_array($this->_item->size)) ? $this->_item->size : explode(',',$this->_item->size);
			$this->_item->size_id = $values;
			$textValue = array();

			foreach ($values as $value)
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__prime_sizes_4128102`.`size`')
					->from($db->quoteName('#__prime_sizes', '#__prime_sizes_4128102'))
					->where($db->quoteName('id') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->size;
				}
			}

			$this->_item->size = !empty($textValue) ? implode(', ', $textValue) : $this->_item->size;

		}

		return $this->_item;
	}
	


	/**
	 * Get an instance of Table class
	 *
	 * @param   string $type   Name of the Table class to get an instance of.
	 * @param   string $prefix Prefix for the table class name. Optional.
	 * @param   array  $config Array of configuration values for the Table object. Optional.
	 *
	 * @return  Table|bool Table if success, false on failure.
	 */
	public function getTable($type = 'Tile', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 * @param   string $alias Item alias
	 *
	 * @return  mixed
	 * 
	 * @deprecated  No replacement
	 */
	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();
		$result     = null;
		$aliasKey   = null;
		if (method_exists($this, 'getAliasFieldNameByView'))
		{
			$aliasKey   = $this->getAliasFieldNameByView('tile');
		}
		

		if (key_exists('alias', $properties))
		{
			$table->load(array('alias' => $alias));
			$result = $table->id;
		}
		elseif (isset($aliasKey) && key_exists($aliasKey, $properties))
		{
			$table->load(array($aliasKey => $alias));
			$result = $table->id;
		}
		
			return $result;
		
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since   1.0.0
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('tile.id');
				
		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
		
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since   1.0.0
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('tile.id');

				
		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = Factory::getApplication()->getIdentity();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
				
	}

	/**
	 * Publish the element
	 *
	 * @param   int $id    Item id
	 * @param   int $state Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
				
		$table->load($id);
		$table->state = $state;

		return $table->store();
				
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int $id Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		
			return $table->delete($id);
		
	}

	public function getAliasFieldNameByView($view)
	{
		switch ($view)
		{
			case 'tile':
			case 'tileform':
				return 'alias';
			break;
			case 'area':
			case 'areaform':
				return 'alias';
			break;
			case 'brand':
			case 'brandform':
				return 'alias';
			break;
			case 'surface':
			case 'surfaceform':
				return 'alias';
			break;
			case 'type':
			case 'typeform':
				return 'alias';
			break;
		}
	}
}
