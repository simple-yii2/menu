<?php

namespace cms\menu\frontend\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use cms\menu\common\models;

/**
 * Main menu helper
 */
class Menu
{

	/**
	 * @var array Path info of current request
	 */
	private static $_pathInfo;

	/**
	 * Get main menu items
	 * @param string $alias 
	 * @param boolean $activeOnly 
	 * @return array
	 */
	public static function getItems($alias, &$breadcrumbs = [], $activeOnly = true)
	{
		$root = models\Menu::findByAlias($alias);
		if ($root === null)
			return [];

		if ($activeOnly && !$root->active)
			return [];

		$objects = array_merge([$root], $root->children()->all());

		$i = 0;
		$a = false;
		$item = self::makeBranch($objects, $i, $a, $activeOnly);

		return ArrayHelper::getValue($item, 'items', []);
	}

	/**
	 * Make menu branch
	 * @param models\Menu[] $objects 
	 * @param integer &$i 
	 * @param boolean &$isActive Is true if branch is currently selected
	 * @param boolean $activeOnly Only object with active = true
	 * @return array
	 */
	private static function makeBranch($objects, &$i, &$isActive, $activeOnly)
	{
		$object = $objects[$i];
		$url = $object->createUrl();

		if ($object->type == models\Menu::DIVIDER) 
			return '<li role="separator" class="divider"></li>';

		$result = [
			'label' => $object->name,
			'url' => $url,
		];

		$a = false;
		$items = [];
		while (($i < sizeof($objects) - 1) && $objects[$i + 1]->depth > $object->depth) {
			$i++;
			$o = $objects[$i];

			$item = self::makeBranch($objects, $i, $a, $activeOnly);

			if (!$activeOnly || $o->active)
				$items[] = $item;
		}

		$isItemActive = self::isActive($url);
		if ($a || $isItemActive) {
			$result['active'] = true;

			$isActive = true;
		}

		if (!empty($items))
			$result['items'] = $items;

		return $result;
	}

	/**
	 * Function is determine that [[url]] is corresponds to current request
	 * @param string||array $url 
	 * @return boolean
	 */
	private static function isActive($url)
	{
		if (self::$_pathInfo === null)
			self::$_pathInfo = self::getPathInfo(Yii::$app->getRequest()->absoluteUrl);

		$pathInfo = self::$_pathInfo;
		$urlInfo = self::getPathInfo($url);

		if ($urlInfo['host'] !== null && $urlInfo['host'] !== $pathInfo['host'])
			return false;

		if ($urlInfo['path'] !== $pathInfo['path'])
			return false;

		return empty(array_diff_assoc($urlInfo['params'], $pathInfo['params']));
	}

	/**
	 * Parsing url to path info
	 * @param string||array $url 
	 * @return array
	 */
	private static function getPathInfo($url)
	{
		$info = parse_url(Url::to($url));

		parse_str(ArrayHelper::getValue($info, 'query', ''), $params);

		return [
			'host' => ArrayHelper::getValue($info, 'host'),
			'path' => ArrayHelper::getValue($info, 'path'),
			'params' => $params,
		];
	}

}
