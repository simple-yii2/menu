<?php

namespace cms\menu\frontend\helpers;

use Yii;
use yii\helpers\ArrayHelper;

use cms\menu\common\models;

/**
 * Main menu helper
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

		$objects = array_merge([$root], $root->children()->all());

		$i = 0;
		$item = self::makeBranch($objects, $i, $activeOnly);

		return ArrayHelper::getValue($item, 'items', []);
	}

	/**
	 * Make menu branch
	 * @param models\Menu[] $objects 
	 * @param integer &$i 
	 * @param boolean $activeOnly 
	 * @return array
	 */
	private static function makeBranch($objects, &$i, $activeOnly)
	{
		$object = $objects[$i];

		$result = [
			'label' => $object->name,
			'url' => $object->createUrl(),
		];

		$items = [];
		while (($i < sizeof($objects) - 1) && $objects[$i + 1]->depth > $object->depth) {
			$i++;
			$o = $objects[$i];

			$item = self::makeBranch($objects, $i, $activeOnly);

			if (!$activeOnly || $o->active)
				$items[] = $item;
		}
		if (!empty($items))
			$result['items'] = $items;

		return $result;
	}

}
