<?php

namespace menu\backend\models;

use Yii;
use yii\base\Model;

use menu\common\models\Menu;

/**
 * Main menu item editting form
 */
class MenuForm extends Model {

	/**
	 * @var string Main menu item name.
	 */
	public $name;

	/**
	 * @var boolean Active.
	 */
	public $active;

	/**
	 * @var integer Type of menu item.
	 * @see menu\common\models\Menu
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
	 * @var menu\common\models\Mainmnu Menu item model
	 */
	public $item;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'name' => Yii::t('menu', 'Name'),
			'active' => Yii::t('menu', 'Active'),
			'type' => Yii::t('menu', 'Type'),
			'url' => Yii::t('menu', 'Url'),
			'alias' => Yii::t('menu', 'Resource'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['name', 'alias'], 'string', 'max' => 100],
			['active', 'boolean'],
			['type', 'integer'],
			['url', 'string', 'max' => 200],
		];
	}

	/**
	 * @inheritdoc
	 * Set default values
	 */
	public function init() {
		parent::init();

		$this->active = true;
		$this->type = Menu::TYPE_LINK;
		
		if ($this->item !== null) {
			$this->setAttributes([
				'name' => $this->item->name,
				'active' => $this->item->active,
				'type' => $this->item->type,
				'url' => $this->item->url,
				'alias' => $this->item->alias,
			], false);
		}
	}

	/**
	 * Main menu item creation
	 * @return boolean
	 */
	public function create($parent_id) {
		if (!$this->validate())
			return false;

		$parent = Menu::findOne($parent_id);
		if ($parent === null)
			$parent = Menu::find()->roots()->one();

		if ($parent === null)
			return false;

		$this->item = new Menu;

		$this->item->setAttributes([
			'name' => $this->name,
			'active' => $this->active,
			'type' => $this->type,
			'url' => $this->url,
			'alias' => $this->alias,
		], false);

		$success = $this->item->appendTo($parent, false);

		return $success;
	}

	/**
	 * Main menu item updating
	 * @return boolean
	 */
	public function update() {
		if ($this->item === null)
			return false;

		if (!$this->validate())
			return false;

		$this->item->setAttributes([
			'name' => $this->name,
			'active' => $this->active,
			'type' => $this->type,
			'url' => $this->url,
			'alias' => $this->alias,
		], false);

		$success = $this->item->save(false);

		return $success;
	}

}
