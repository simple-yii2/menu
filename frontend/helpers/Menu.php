<?php

namespace cms\menu\frontend\helpers;

use Yii;

use cms\menu\common\models;

/**
 * Main menu helper.
 */
class Menu
{

	/**
	 * Get main menu items
	 * @param string $alias 
	 * @param boolean $activeOnly 
	 * @return array
	 */
	public static function getItems($alias, $activeOnly = true)
	{
		$root = models\Menu::findByAlias($alias);
		if ($root === null)
			return [];

		if ($activeOnly && !$root->active)
			return [];

		$query = $root->children();
		if ($activeOnly)
			$query->andWhere(['active' => true]);

		$children = $query->all();

		$result = [];
		for ($i = 0; $i < sizeof($children); $i++)
			$result[] = self::makeBranch($children, $i);

		return $result;
	}

	/**
	 * Make menu branch
	 * @param models\Menu[] $children 
	 * @param integer &$i 
	 * @return array
	 */
	private static function makeBranch($children, &$i)
	{
		$child = $children[$i];
		$result = [
			'label' => $child->name,
			'url' => $child->createUrl(),
		];

		$items = [];
		while (($i < sizeof($children) - 1) && $children[$i + 1]->depth > $child->depth) {
			$i++;
			$items[] = self::makeBranch($children, $i);
		}
		if (!empty($items))
			$result['items'] = $items;

		return $result;
	}

}
