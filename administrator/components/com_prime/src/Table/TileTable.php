<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Prime
 * @author     OneDigital <hello@onedigital.vn>
 * @copyright  @2025 PRIME GROUP
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Prime\Component\Prime\Administrator\Table;
// No direct access
defined('_JEXEC') or die;

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table as Table;
use \Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use \Joomla\Database\DatabaseDriver;
use \Joomla\CMS\Filter\OutputFilter;
use \Joomla\CMS\Filesystem\File;
use \Joomla\Registry\Registry;
use \Prime\Component\Prime\Administrator\Helper\PrimeHelper;
use \Joomla\CMS\Helper\ContentHelper;


/**
 * Tile table
 *
 * @since 1.0.0
 */
class TileTable extends Table implements VersionableTableInterface, TaggableTableInterface
{
	use TaggableTableTrait;

	/**
     * Indicates that columns fully support the NULL value in the database
     *
     * @var    boolean
     * @since  4.0.0
     */
    protected $_supportNullValue = true;

	/**
	 * Check if a field is unique
	 *
	 * @param   string  $field  Name of the field
	 *
	 * @return bool True if unique
	 */
	private function isUnique ($field)
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query
			->select($db->quoteName($field))
			->from($db->quoteName($this->_tbl))
			->where($db->quoteName($field) . ' = ' . $db->quote($this->$field))
			->where($db->quoteName('id') . ' <> ' . (int) $this->{$this->_tbl_key});

		$db->setQuery($query);
		$db->execute();

		return ($db->getNumRows() == 0) ? true : false;
	}

	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_prime.tile';
		parent::__construct('#__prime_tiles', 'id', $db);
		$this->setColumnAlias('published', 'state');
		
	}

	/**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   1.0.0
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  Optional array or list of parameters to ignore
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     Table:bind
	 * @since   1.0.0
	 * @throws  \InvalidArgumentException
	 */
	public function bind($array, $ignore = '')
	{
		$date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');
		$user = Factory::getApplication()->getIdentity();
		
		$input = Factory::getApplication()->input;
		$task = $input->getString('task', '');

		if ($array['id'] == 0 && empty($array['created_by']))
		{
			$array['created_by'] = Factory::getUser()->id;
		}

		if ($array['id'] == 0 && empty($array['modified_by']))
		{
			$array['modified_by'] = Factory::getUser()->id;
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified_by'] = Factory::getUser()->id;
		}

		// Support for multiple or not foreign key field: brand
			if(!empty($array['brand']))
			{
				if(is_array($array['brand'])){
					$array['brand'] = implode(',',$array['brand']);
				}
				else if(strrpos($array['brand'], ',') != false){
					$array['brand'] = explode(',',$array['brand']);
				}
			}
			else {
				$array['brand'] = 0;
			}

		// Support for multiple or not foreign key field: design
			if(!empty($array['design']))
			{
				if(is_array($array['design'])){
					$array['design'] = implode(',',$array['design']);
				}
				else if(strrpos($array['design'], ',') != false){
					$array['design'] = explode(',',$array['design']);
				}
			}
			else {
				$array['design'] = 0;
			}

		// Support for multiple or not foreign key field: thickness
			if(!empty($array['thickness']))
			{
				if(is_array($array['thickness'])){
					$array['thickness'] = implode(',',$array['thickness']);
				}
				else if(strrpos($array['thickness'], ',') != false){
					$array['thickness'] = explode(',',$array['thickness']);
				}
			}
			else {
				$array['thickness'] = 0;
			}

		// Support for multiple field: area
		if (isset($array['area']))
		{
			if (is_array($array['area']))
			{
				$array['area'] = implode(',',$array['area']);
			}
			elseif (strpos($array['area'], ',') != false)
			{
				$array['area'] = explode(',',$array['area']);
			}
			elseif (strlen($array['area']) == 0)
			{
				$array['area'] = '';
			}
		}
		else
		{
			$array['area'] = '';
		}

		// Support for multiple field: effects
		if (isset($array['effects']))
		{
			if (is_array($array['effects']))
			{
				$array['effects'] = implode(',',$array['effects']);
			}
			elseif (strpos($array['effects'], ',') != false)
			{
				$array['effects'] = explode(',',$array['effects']);
			}
			elseif (strlen($array['effects']) == 0)
			{
				$array['effects'] = '';
			}
		}
		else
		{
			$array['effects'] = '';
		}

		// Support for multiple or not foreign key field: color
			if(!empty($array['color']))
			{
				if(is_array($array['color'])){
					$array['color'] = implode(',',$array['color']);
				}
				else if(strrpos($array['color'], ',') != false){
					$array['color'] = explode(',',$array['color']);
				}
			}
			else {
				$array['color'] = 0;
			}

		// Support for multiple or not foreign key field: type
			if(!empty($array['type']))
			{
				if(is_array($array['type'])){
					$array['type'] = implode(',',$array['type']);
				}
				else if(strrpos($array['type'], ',') != false){
					$array['type'] = explode(',',$array['type']);
				}
			}
			else {
				$array['type'] = 0;
			}

		// Support for multiple or not foreign key field: groutcolor
			if(!empty($array['groutcolor']))
			{
				if(is_array($array['groutcolor'])){
					$array['groutcolor'] = implode(',',$array['groutcolor']);
				}
				else if(strrpos($array['groutcolor'], ',') != false){
					$array['groutcolor'] = explode(',',$array['groutcolor']);
				}
			}
			else {
				$array['groutcolor'] = 0;
			}

		// Support for multiple or not foreign key field: variation
			if(!empty($array['variation']))
			{
				if(is_array($array['variation'])){
					$array['variation'] = implode(',',$array['variation']);
				}
				else if(strrpos($array['variation'], ',') != false){
					$array['variation'] = explode(',',$array['variation']);
				}
			}
			else {
				$array['variation'] = 0;
			}

		// Support for multiple or not foreign key field: surface
			if(!empty($array['surface']))
			{
				if(is_array($array['surface'])){
					$array['surface'] = implode(',',$array['surface']);
				}
				else if(strrpos($array['surface'], ',') != false){
					$array['surface'] = explode(',',$array['surface']);
				}
			}
			else {
				$array['surface'] = 0;
			}

		// Support for multiple or not foreign key field: facetile
			if(!empty($array['facetile']))
			{
				if(is_array($array['facetile'])){
					$array['facetile'] = implode(',',$array['facetile']);
				}
				else if(strrpos($array['facetile'], ',') != false){
					$array['facetile'] = explode(',',$array['facetile']);
				}
			}
			else {
				$array['facetile'] = 0;
			}

		// Support for multiple or not foreign key field: size
			if(!empty($array['size']))
			{
				if(is_array($array['size'])){
					$array['size'] = implode(',',$array['size']);
				}
				else if(strrpos($array['size'], ',') != false){
					$array['size'] = explode(',',$array['size']);
				}
			}
			else {
				$array['size'] = 0;
			}

		// Support for alias field: alias
		if (empty($array['alias']))
		{
			if (empty($array['tile']))
			{
				$array['alias'] = OutputFilter::stringURLSafe(date('Y-m-d H:i:s'));
			}
			else
			{
				if(Factory::getConfig()->get('unicodeslugs') == 1)
				{
					$array['alias'] = OutputFilter::stringURLUnicodeSlug(trim($array['tile']));
				}
				else
				{
					$array['alias'] = OutputFilter::stringURLSafe(trim($array['tile']));
				}
			}
		}


		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new Registry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new Registry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!$user->authorise('core.admin', 'com_prime.tile.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_prime/access.xml',
				"/access/section[@name='tile']/"
			);
			$default_actions = Access::getAssetRules('com_prime.tile.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
				if (key_exists($action->name, $default_actions))
				{
					$array_jaccess[$action->name] = $default_actions[$action->name];
				}
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Method to store a row in the database from the Table instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the Table instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function store($updateNulls = true)
	{
		
		
		return parent::store($updateNulls);
	}

	/**
	 * This function convert an array of Access objects into an rules array.
	 *
	 * @param   array  $jaccessrules  An array of Access objects.
	 *
	 * @return  array
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}

	/**
	 * Overloaded check function
	 *
	 * @return bool
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}
		
		// Check if alias is unique
		if (!$this->isUnique('alias'))
		{
			$count = 0;
			$currentAlias =  $this->alias;
			while(!$this->isUnique('alias')){
				$this->alias = $currentAlias . '-' . $count++;
			}
		}
		

		return parent::check();
	}

	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 *
	 * @return string The asset name
	 *
	 * @see Table::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return $this->typeAlias . '.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   Table   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see Table::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
	 */
	protected function _getAssetParentId($table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = Table::getInstance('Asset');

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName('com_prime');

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	//XXX_CUSTOM_TABLE_FUNCTION

	
    /**
     * Delete a record by id
     *
     * @param   mixed  $pk  Primary key value to delete. Optional
     *
     * @return bool
     */
    public function delete($pk = null)
    {
        $this->load($pk);
        $result = parent::delete($pk);
        
        return $result;
    }
}
