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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Utilities\ArrayHelper;

/**
 * Methods supporting a list of Prime records.
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
	 * @see    \Joomla\CMS\MVC\Controller\BaseController
	 * @since  1.0.0
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
				'brand', 'a.brand',
				'language', 'a.language',
				'design', 'a.design',
				'thickness', 'a.thickness',
				'area', 'a.area',
				'color', 'a.color',
				'type', 'a.type',
				'size', 'a.size',
                'surface', 'a.surface',
			);
		}

		parent::__construct($config);

        $this->context = $this->context . '.' . Factory::getApplication()->getLanguage()->getTag();
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 * @throws  \Exception
	 * @since   1.0.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app   = Factory::getApplication();
		$input = $app->input;

		// Xử lý các trạng thái phân trang và sắp xếp cơ bản trước.
		// Quan trọng: Bước này lấy giá trị 'start' từ URL khi phân trang.
		parent::populateState('a.id', 'ASC');

		$currentItemid = $input->getInt('Itemid');
		$sessionItemid = $app->getUserState($this->context . '.itemid');

		$submittedFilters = $input->get('filter', null, 'array');
		$isFilterSubmission = ($submittedFilters !== null);

		if ($currentItemid != $sessionItemid) {
			// TRƯỜNG HỢP 1: Người dùng đã chuyển sang một menu item MỚI.
			// Hành động: Hủy bỏ tất cả các bộ lọc cũ và CHỈ sử dụng bộ lọc từ menu item mới.
			$menuParams = $app->getParams();
			$filters = (array) $menuParams->get('filter', []);
			$this->setState('list.start', 0);

		} elseif ($isFilterSubmission) {
			// TRƯỜNG HỢP 2: Người dùng đang ở cùng một menu item nhưng đã gửi một bộ lọc MỚI.
			// Hành động: Kết hợp các bộ lọc cơ bản từ menu với các bộ lọc do người dùng gửi lên.
			$menuParams = $app->getParams();
			$filters = (array) $menuParams->get('filter', []);
			$filters = array_merge($filters, $submittedFilters);
			$this->setState('list.start', 0);

		} else {
			// TRƯỜNG HỢP 3: Người dùng ở cùng một menu item và không gửi bộ lọc mới.
			// Điều này có nghĩa là họ đang phân trang hoặc chỉ tải lại trang.
			// Hành động: Sử dụng bộ lọc hoàn chỉnh đã được lưu trong session từ hành động cuối cùng.
			$filters = (array) $app->getUserState($this->context . '.filter', []);
		}

		// Bây giờ, lưu trạng thái cuối cùng cho request này vào session để phục vụ cho request tiếp theo (ví dụ: phân trang).
		$app->setUserState($this->context . '.filter', $filters);
		$app->setUserState($this->context . '.itemid', $currentItemid);

		// Cuối cùng, thiết lập trạng thái để model sử dụng khi xây dựng truy vấn cho lần tải trang hiện tại.
		$this->setState('filter', $filters);
		$this->setState('filter.search', (string) ($filters['search'] ?? ''));
		$this->setState('filter.area', (array) ($filters['area'] ?? []));
		$this->setState('filter.color', (array) ($filters['color'] ?? []));
		$this->setState('filter.design', (array) ($filters['design'] ?? []));
		$this->setState('filter.type', (array) ($filters['type'] ?? []));
		$this->setState('filter.size', (array) ($filters['size'] ?? []));
		$this->setState('filter.surface', (array) ($filters['surface'] ?? []));
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  \Joomla\Database\DatabaseQuery
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select($this->getState('list.select', 'DISTINCT a.*'))
			  ->from('`#__prime_tiles` AS a');
			
		$query->select('uc.name AS uEditor')->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		if (!Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_prime')) {
			$query->where('a.state = 1');
		} else {
			$query->where('(a.state IN (0, 1))');
		}

        $lang = Factory::getApplication()->getLanguage()->getTag();
        if ($lang) {
            $query->where('a.language IN (' . $db->quote($lang) . ', ' . $db->quote('*') . ')');
        }
		
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$searchQuoted = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(' . $db->quoteName('a.tile') . ' LIKE ' . $searchQuoted . ' OR ' . $db->quoteName('a.sku') . ' LIKE ' . $searchQuoted . ')');
			}
		}

		$checked_areas	= $this->getState('filter.area', []);
		$checked_colors	= $this->getState('filter.color', []);
		$checked_designs = $this->getState('filter.design', []);
		$checked_types = $this->getState('filter.type', []);
		$checked_sizes = $this->getState('filter.size', []);
		$checked_surfaces = $this->getState('filter.surface', []);

		if(!empty($checked_areas) && is_array($checked_areas) && count($checked_areas)) {
			$where_areas = array();
			foreach($checked_areas AS $checked_area) {
                if(!empty($checked_area)) {
				    $where_areas[] = "FIND_IN_SET(" . $db->quote((int)$checked_area) . ", a.area)";
                }
			}
			if (count($where_areas)) {
			    $query->where("(".implode(" OR ", $where_areas).")");
            }
		}
		
		if(!empty($checked_types) && is_array($checked_types) && count($checked_types)) {
			$where_types = array();
			foreach($checked_types AS $checked_type) {
                if(!empty($checked_type)) {
				    $where_types[] = "FIND_IN_SET(" . $db->quote((int)$checked_type) . ", a.type)";
                }
			}
			if (count($where_types)) {
			    $query->where("(".implode(" OR ", $where_types).")");
            }
		}

		if (!empty($checked_colors)) {
			ArrayHelper::toInteger($checked_colors);
			if (!empty($checked_colors)) {
				$query->where(" a.color IN (" . implode(", ", $checked_colors) . ")");
			}
		}
		
		if (!empty($checked_designs)) {
			ArrayHelper::toInteger($checked_designs);
			if (!empty($checked_designs)) {
				$query->where(" a.design IN (" . implode(", ", $checked_designs) . ")");
			}
		}

		if (!empty($checked_sizes)) {
			ArrayHelper::toInteger($checked_sizes);
			if (!empty($checked_sizes)) {
				$query->where(" a.size IN (" . implode(", ", $checked_sizes) . ")");
			}
		}

		if (!empty($checked_surfaces)) {
			ArrayHelper::toInteger($checked_surfaces);
			if (!empty($checked_surfaces)) {
				$query->where(" a.surface IN (" . implode(", ", $checked_surfaces) . ")");
			}
		}
		
		$orderCol  = $this->getState('list.ordering', 'a.id');
		$orderDirn = $this->getState('list.direction', 'ASC');
		if ($orderCol && $orderDirn) {
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
		
		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();

		if (empty($items)) {
			return [];
		}

		$relations = [
			'brand'      => ['table' => '#__prime_brands', 'value_field' => 'brand'],
			'design'     => ['table' => '#__prime_designs', 'value_field' => 'title'],
			'thickness'  => ['table' => '#__prime_thickness', 'value_field' => 'thickness'],
			'area'       => ['table' => '#__prime_areas', 'value_field' => 'area'],
			'effects'    => ['table' => '#__prime_effects', 'value_field' => 'effect'],
			'color'      => ['table' => '#__prime_colors', 'value_field' => 'color'],
			'type'       => ['table' => '#__prime_types', 'value_field' => 'type'],
			'groutcolor' => ['table' => '#__prime_groutcolors', 'value_field' => 'groutcolor'],
			'variation'  => ['table' => '#__prime_variation_colors', 'value_field' => 'variation'],
			'surface'    => ['table' => '#__prime_surfaces', 'value_field' => 'surface'],
			'facetile'   => ['table' => '#__prime_faces', 'value_field' => 'face'],
			'size'       => ['table' => '#__prime_sizes', 'value_field' => 'size'],
		];

		$idCache = [];
		foreach ($items as $item) {
			foreach ($relations as $field => $details) {
				if (!isset($idCache[$field])) $idCache[$field] = [];
				if (!empty($item->$field)) {
					$idCache[$field] = array_merge($idCache[$field], explode(',', $item->$field));
				}
			}
		}

		$valueMaps = [];
		$db = $this->getDbo();
		foreach ($relations as $field => $details) {
			$uniqueIds = array_unique(array_filter(array_map('trim', $idCache[$field])));
			if (!empty($uniqueIds)) {
				$query = $db->getQuery(true)
					->select(['id', $db->quoteName($details['value_field'])])
					->from($db->quoteName($details['table']))
					->where('id IN (' . implode(',', $uniqueIds) . ')');
				$valueMaps[$field] = $db->setQuery($query)->loadObjectList('id');
			}
		}

		foreach ($items as $item) {
			foreach ($relations as $field => $details) {
				if (!empty($item->$field)) {
					$ids = explode(',', $item->$field);
					$textValues = [];
					foreach ($ids as $id) {
						$id = trim($id);
						if (isset($valueMaps[$field][$id])) {
							$textValues[] = $valueMaps[$field][$id]->{$details['value_field']};
						}
					}
					$item->$field = implode(', ', $textValues);
				}
			}
		}

		return $items;
	}
}
