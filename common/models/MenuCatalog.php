<?php

namespace cms\menu\common\models;

class MenuCatalog extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::CATALOG;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return class_exists('cms\catalog\backend\Module');
	}

	/**
	 * @inheritdoc
	 */
	public function isAliasNeeded()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getAliasList()
	{
		$items = [];
		foreach (\cms\catalog\common\models\Category::find()->select(['alias', 'path'])->andWhere(['>', 'depth', 0])->orderBy(['lft' => SORT_ASC])->asArray()->all() as $row) {
			$items[$row['alias']] = $row['path'];
		}

		return $items;
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return ['/catalog/offer/index', 'alias' => $this->alias];
	}

}
