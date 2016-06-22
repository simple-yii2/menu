<?php

namespace mainmenu\backend\models;

use Yii;
use yii\base\Model;

use mainmenu\common\models\Mainmenu;

/**
 * Main menu item editting form
 */
class MainmenuForm extends Model {

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
	 * @see mainmenu\common\models\Mainmenu
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
	 * @var mainmenu\common\models\Mainmnu Menu item model
	 */
	public $item;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'name' => Yii::t('mainmenu', 'Name'),
			'active' => Yii::t('mainmenu', 'Active'),
			'type' => Yii::t('mainmenu', 'Type'),
			'url' => Yii::t('mainmenu', 'Url'),
			'alias' => Yii::t('mainmenu', 'Resource'),
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
		$this->type = Mainmenu::LINK;
		
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

		$parent = Mainmenu::findOne($parent_id);
		if ($parent === null)
			$parent = Mainmenu::find()->roots()->one();

		if ($parent === null)
			return false;

		$this->item = new Mainmenu;

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
