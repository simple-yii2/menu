<?php

namespace cms\menu\backend\models;

use Yii;
use yii\base\Model;

use cms\menu\common\models\Menu;

/**
 * Item editting form
 */
class ItemForm extends Model
{

	/**
	 * @var boolean Active.
	 */
	public $active;

	/**
	 * @var string Main menu item name.
	 */
	public $name;

	/**
	 * @var integer Type of menu item.
	 * @see cms\menu\common\models\Menu
	 */
	public $type;

	/**
	 * @var string Menu item url.
	 */
	public $url;

	/**
	 * @var string Resource alias for some types of menu item.
	 */
	public $alias;

	/**
	 * @var cms\menu\common\models\Menu
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param cms\menu\common\models\Menu $object 
	 */
	public function __construct(\cms\menu\common\models\Menu $object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->active = $object->active == 0 ? '0' : '1';
		$this->name = $object->name;
		$this->type = $object->type;
		$this->url = $object->url;
		$this->alias = $object->alias;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'active' => Yii::t('menu', 'Active'),
			'name' => Yii::t('menu', 'Name'),
			'type' => Yii::t('menu', 'Type'),
			'url' => Yii::t('menu', 'Url'),
			'alias' => Yii::t('menu', 'Resource'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'alias'], 'string', 'max' => 100],
			['active', 'boolean'],
			['type', 'integer'],
			['url', 'string', 'max' => 200],
		];
	}

	public function getObject()
	{
		return $this->_object;
	}

	public function getAliasList()
	{
		return $this->_object->getAliasList();
	}

	/**
	 * Save object using model attributes
	 * @param cms\menu\common\models\Menu|null $object 
	 * @return boolean
	 */
	public function save(\cms\menu\common\models\Menu $parent = null)
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->active = $this->active == 1;
		$object->name = $this->name;
		$object->type = $this->type;
		$object->url = $this->url;
		$object->alias = $this->alias;

		if ($object->getIsNewRecord()) {
			if (!$object->appendTo($parent, false))
				return false;
		} else {
			if (!$object->save(false))
				return false;
		}

		return true;
	}

}
