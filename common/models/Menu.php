<?php

namespace cms\menu\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use cms\menu\common\helpers\MenuType;

/**
 * Main menu active record
 */
class Menu extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Menu';
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->active = true;
		$this->type = MenuType::TYPE_LINK;
		$this->url = '#';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('menu', 'Name'),
			'active' => Yii::t('menu', 'Active'),
			'type' => Yii::t('menu', 'Type'),
			'url' => Yii::t('menu', 'Url'),
		];
	}

	/**
	 * Find by alias
	 * @param sring $alias Alias or id
	 * @return Menu
	 */
	public static function findByAlias($alias) {
		$model = static::findOne(['alias' => $alias]);
		if ($model === null)
			$model = static::findOne(['id' => $alias]);

		return $model;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'tree' => [
				'class' => NestedSetsBehavior::className(),
				'treeAttribute' => 'tree',
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find()
	{
		return new MenuQuery(get_called_class());
	}

}

/**
 * Main menu active query
 */
class MenuQuery extends ActiveQuery
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			NestedSetsQueryBehavior::className(),
		];
	}

}
