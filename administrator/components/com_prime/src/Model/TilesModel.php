<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Prime
 * @author     OneDigital <hello@onedigital.vn>
 * @copyright  @2025 PRIME GROUP
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Prime\Component\Prime\Administrator\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use \Joomla\Utilities\ArrayHelper;
use Prime\Component\Prime\Administrator\Helper\PrimeHelper;

/**
 * Methods supporting a list of Tiles records.
 *
 * @since  1.0.0
 */
class TilesModel extends ListModel
{
	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'state', 'a.state',
				'ordering', 'a.ordering',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'tile', 'a.tile',
				'sku', 'a.sku',
				'description', 'a.description',
				'brand', 'a.brand',
				'language', 'a.language',
				'gallery', 'a.gallery',
				'design', 'a.design',
				'thickness', 'a.thickness',
				'image', 'a.image',
				'area', 'a.area',
				'effects', 'a.effects',
				'color', 'a.color',
				'type', 'a.type',
				'groutcolor', 'a.groutcolor',
				'variation', 'a.variation',
				'surface', 'a.surface',
				'facetile', 'a.facetile',
				'size', 'a.size',
				'alias', 'a.alias',
				'video', 'a.video',
				'live', 'a.live',
				'collection', 'a.collection',
			);
		}

		parent::__construct($config);
	}


	

	

	

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('id', 'ASC');

		$context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $context);

		// Split context into component and optional section
		if (!empty($context))
		{
			$parts = FieldsHelper::extract($context);

			if ($parts)
			{
				$this->setState('filter.component', $parts[0]);
				$this->setState('filter.section', $parts[1]);
			}
		}
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string A store id.
	 *
	 * @since   1.0.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		
		return parent::getStoreId($id);
		
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  DatabaseQuery
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__prime_tiles` AS a');
		
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the foreign key 'brand'
		$query->select('`#__prime_brands_4126008`.`brand` AS brands_fk_value_4126008');
		$query->join('LEFT', '#__prime_brands AS #__prime_brands_4126008 ON #__prime_brands_4126008.`id` = a.`brand`');
		// Join over the foreign key 'design'
		$query->select('`#__prime_designs_4128066`.`title` AS designs_fk_value_4128066');
		$query->join('LEFT', '#__prime_designs AS #__prime_designs_4128066 ON #__prime_designs_4128066.`id` = a.`design`');
		// Join over the foreign key 'thickness'
		$query->select('`#__prime_thickness_4128072`.`thickness` AS thicknesses_fk_value_4128072');
		$query->join('LEFT', '#__prime_thickness AS #__prime_thickness_4128072 ON #__prime_thickness_4128072.`id` = a.`thickness`');
		// Join over the foreign key 'color'
		$query->select('`#__prime_colors_4128083`.`color` AS colors_fk_value_4128083');
		$query->join('LEFT', '#__prime_colors AS #__prime_colors_4128083 ON #__prime_colors_4128083.`id` = a.`color`');
		// Join over the foreign key 'type'
		$query->select('`#__prime_types_4128086`.`type` AS types_fk_value_4128086');
		$query->join('LEFT', '#__prime_types AS #__prime_types_4128086 ON #__prime_types_4128086.`id` = a.`type`');
		// Join over the foreign key 'groutcolor'
		$query->select('`#__prime_groutcolors_4128089`.`groutcolor` AS groutcolors_fk_value_4128089');
		$query->join('LEFT', '#__prime_groutcolors AS #__prime_groutcolors_4128089 ON #__prime_groutcolors_4128089.`id` = a.`groutcolor`');
		// Join over the foreign key 'variation'
		$query->select('`#__prime_variation_colors_4128092`.`variation` AS variationcolors_fk_value_4128092');
		$query->join('LEFT', '#__prime_variation_colors AS #__prime_variation_colors_4128092 ON #__prime_variation_colors_4128092.`id` = a.`variation`');
		// Join over the foreign key 'surface'
		$query->select('`#__prime_surfaces_4128095`.`surface` AS surfaces_fk_value_4128095');
		$query->join('LEFT', '#__prime_surfaces AS #__prime_surfaces_4128095 ON #__prime_surfaces_4128095.`id` = a.`surface`');
		// Join over the foreign key 'facetile'
		$query->select('`#__prime_faces_4128098`.`face` AS faces_fk_value_4128098');
		$query->join('LEFT', '#__prime_faces AS #__prime_faces_4128098 ON #__prime_faces_4128098.`id` = a.`facetile`');
		// Join over the foreign key 'size'
		$query->select('`#__prime_sizes_4128102`.`size` AS sizes_fk_value_4128102');
		$query->join('LEFT', '#__prime_sizes AS #__prime_sizes_4128102 ON #__prime_sizes_4128102.`id` = a.`size`');
		

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif (empty($published))
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.tile LIKE ' . $search . ' )');
			}
		}
		

		// Filtering brand
		$filter_brand = $this->state->get("filter.brand");

		if ($filter_brand !== null && !empty($filter_brand))
		{
			$query->where("a.`brand` = '".$db->escape($filter_brand)."'");
		}

		// Filtering language
		$filter_language = $this->state->get("filter.language");

		if ($filter_language !== null && !empty($filter_language))
		{
			$query->where("a.`language` = '".$db->escape($filter_language)."'");
		}

		// Filtering design
		$filter_design = $this->state->get("filter.design");

		if ($filter_design !== null && !empty($filter_design))
		{
			$query->where("a.`design` = '".$db->escape($filter_design)."'");
		}

		// Filtering thickness
		$filter_thickness = $this->state->get("filter.thickness");

		if ($filter_thickness !== null && !empty($filter_thickness))
		{
			$query->where("a.`thickness` = '".$db->escape($filter_thickness)."'");
		}

		// Filtering size
		$filter_size = $this->state->get("filter.size");

		if ($filter_size !== null && !empty($filter_size))
		{
			$query->where("a.`size` = '".$db->escape($filter_size)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'ASC');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $oneItem)
		{

			if (isset($oneItem->brand))
			{
				$values    = explode(',', $oneItem->brand);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = $this->getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__prime_brands_4126008`.`brand`')
						->from($db->quoteName('#__prime_brands', '#__prime_brands_4126008'))
						->where($db->quoteName('#__prime_brands_4126008.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->brand;
					}
				}

				$oneItem->brand = !empty($textValue) ? implode(', ', $textValue) : $oneItem->brand;
			}

			if (isset($oneItem->design))
			{
				$values    = explode(',', $oneItem->design);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = $this->getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__prime_designs_4128066`.`title`')
						->from($db->quoteName('#__prime_designs', '#__prime_designs_4128066'))
						->where($db->quoteName('#__prime_designs_4128066.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->title;
					}
				}

				$oneItem->design = !empty($textValue) ? implode(', ', $textValue) : $oneItem->design;
			}

			if (isset($oneItem->thickness))
			{
				$values    = explode(',', $oneItem->thickness);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = $this->getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__prime_thickness_4128072`.`thickness`')
						->from($db->quoteName('#__prime_thickness', '#__prime_thickness_4128072'))
						->where($db->quoteName('#__prime_thickness_4128072.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->thickness;
					}
				}

				$oneItem->thickness = !empty($textValue) ? implode(', ', $textValue) : $oneItem->thickness;
			}

			if (isset($oneItem->area))
			{
				$values    = explode(',', $oneItem->area);
				$textValue = array();

				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = $this->getDbo();
						$query = "SELECT id, area  FROM #__prime_areas
 WHERE id = '$value' ";
						$db->setQuery($query);
						$results = $db->loadObject();

						if ($results)
						{
							$textValue[] = $results->area;
						}
					}
				}

				$oneItem->area = !empty($textValue) ? implode(', ', $textValue) : $oneItem->area;
			}

			if (isset($oneItem->color))
			{
				$values    = explode(',', $oneItem->color);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = $this->getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__prime_colors_4128083`.`color`')
						->from($db->quoteName('#__prime_colors', '#__prime_colors_4128083'))
						->where($db->quoteName('#__prime_colors_4128083.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->color;
					}
				}

				$oneItem->color = !empty($textValue) ? implode(', ', $textValue) : $oneItem->color;
			}

			if (isset($oneItem->type))
			{
				$values    = explode(',', $oneItem->type);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = $this->getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__prime_types_4128086`.`type`')
						->from($db->quoteName('#__prime_types', '#__prime_types_4128086'))
						->where($db->quoteName('#__prime_types_4128086.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->type;
					}
				}

				$oneItem->type = !empty($textValue) ? implode(', ', $textValue) : $oneItem->type;
			}

			if (isset($oneItem->size))
			{
				$values    = explode(',', $oneItem->size);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = $this->getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__prime_sizes_4128102`.`size`')
						->from($db->quoteName('#__prime_sizes', '#__prime_sizes_4128102'))
						->where($db->quoteName('#__prime_sizes_4128102.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->size;
					}
				}

				$oneItem->size = !empty($textValue) ? implode(', ', $textValue) : $oneItem->size;
			}
		}

		return $items;
	}
}
