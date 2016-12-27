<?php

namespace cms\menu\common\models;

class MenuPage extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::PAGE;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return class_exists('cms\page\backend\Module');
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
		foreach (\cms\page\common\models\Page::find()->select(['alias', 'title'])->asArray()->all() as $row) {
			$items[$row['alias']] = $row['title'];
		}

		return $items;
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return ['/page/page/index', 'alias' => $this->alias];
	}

}
