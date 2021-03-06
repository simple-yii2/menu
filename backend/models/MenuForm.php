<?php

namespace cms\menu\backend\models;

use Yii;
use yii\base\Model;

/**
 * Main menu item editting form
 */
class MenuForm extends Model
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
	 * @var string Alias
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
			'alias' => Yii::t('menu', 'Alias'),
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
			['alias', 'required'],
		];
	}

	/**
	 * Save object using model attributes
	 * @return boolean
	 */
	public function save()
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->active = $this->active == 1;
		$object->name = $this->name;
		$object->alias = $this->alias;

		if ($object->getIsNewRecord()) {
			if (!$object->makeRoot(false))
				return false;
		} else {
			if (!$object->save(false))
				return false;
		}

		return true;
	}

}
