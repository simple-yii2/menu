<?php

namespace cms\menu\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Menu active record
 */
class Menu extends ActiveRecord
{

	const SECTION = 0;
	const LINK = 1;
	const PAGE = 2;
	const GALLERY = 3;
	const CONTACT = 4;
	const NEWS = 5;
	const REVIEW = 6;
	const FEEDBACK = 7;

	private static $typeNames = [
		self::SECTION => 'Section',
		self::LINK => 'Link',
		self::PAGE => 'Page',
		self::GALLERY => 'Gallery',
		self::CONTACT => 'Contacts',
		self::NEWS => 'News',
		self::REVIEW => 'Reviews',
		self::FEEDBACK => 'Feedback',
	];

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
	public static function instantiate($row)
	{
		switch ($row['type']) {
			case self::SECTION:
				return new MenuSection;
			case self::LINK:
				return new MenuLink;
			case self::PAGE:
				return new MenuPage;
			case self::GALLERY:
				return new MenuGallery;
			case self::CONTACT:
				return new MenuContact;
			case self::NEWS:
				return new MenuNews;
			case self::REVIEW:
				return new MenuReview;
			case self::FEEDBACK:
				return new MenuFeedback;
			default:
				return new static;
		}
	}

	public static function getTypeNames()
	{
		$names = [];
		foreach (self::$typeNames as $type => $name) {
			$object = self::instantiate(['type' => $type]);
			if ($object->isEnabled())
				$names[$type] = Yii::t('menu', $name);
		}

		return $names;
	}

	public static function getTypesWithUrl()
	{
		$types = [];
		foreach (self::$typeNames as $type => $name) {
			$object = self::instantiate(['type' => $type]);
			if ($object->isUrlNeeded())
				$types[] = $type;
		}

		return $types;
	}

	public static function getTypesWithAlias()
	{
		$types = [];
		foreach (self::$typeNames as $type => $name) {
			$object = self::instantiate(['type' => $type]);
			if ($object->isAliasNeeded())
				$types[] = $type;
		}

		return $types;
	}

	public static function getAliasListByType($type)
	{
		$object = self::instantiate(['type' => $type]);

		return $object->getAliasList();
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->active = true;
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
	 * @return static
	 */
	public static function findByAlias($alias) {
		$model = static::findOne(['alias' => $alias]);
		if ($model === null)
			$model = static::findOne(['id' => $alias]);

		return $model;
	}

	public function getTypeName()
	{
		return Yii::t('menu', self::$typeNames[$this->type]);
	}

	public function isEnabled()
	{
		return false;
	}

	public function isUrlNeeded()
	{
		return false;
	}

	public function isAliasNeeded()
	{
		return false;
	}

	public function getAliasList()
	{
		return [];
	}

	public function createUrl()
	{
		return '#';
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
