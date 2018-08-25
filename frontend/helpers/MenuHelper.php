<?php

namespace cms\menu\frontend\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use cms\menu\common\models;

/**
 * Menu helper
 */
class MenuHelper
{

	/**
	 * @var array Path info of current request
	 */
	private static $_pathInfo;

	/**
	 * @var models\Menu curently selected menu item 
	 */
	private static $_activeItem;

	/**
	 * Get main menu items
	 * @param string $alias 
	 * @param boolean $activeOnly 
	 * @param array $breadcrumbs original application breadcrumbs
	 * @return array
	 */
	public static function getItems($alias, $activeOnly = true, &$breadcrumbs = null)
	{
		$root = models\Menu::findByAlias($alias);
		if ($root === null)
			return [];

		if ($activeOnly && !$root->active)
			return [];

		$objects = array_merge([$root], $root->children()->all());

		self::$_activeItem = null;

		$i = 0;
		$a = false;
		$item = self::makeBranch($objects, $i, $a, $activeOnly);

		$breadcrumbs = self::makeBreadcrumbs($breadcrumbs);

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

			if ($isItemActive)
				self::$_activeItem = $object;

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
		if (self::$_activeItem !== null)
			return false;

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

	/**
	 * Making breadcrumbs for currently selected item
	 * 
	 * If item is not selected or it's link item, breadcrumbs returns as is.
	 * Otherwise first items of original breadcrumbs replaces with parents of menu item.
	 * 
	 * @param array $breadcrumbs original application breadcrumbs 
	 * @return array
	 */
	private static function makeBreadcrumbs($breadcrumbs)
	{
		if ($breadcrumbs === null)
			$breadcrumbs = ArrayHelper::getValue(Yii::$app->params, 'breadcrumbs', []);

		$object = self::$_activeItem;

		if ($object === null)
			return $breadcrumbs;

		if ($object instanceof models\MenuLink)
			return $breadcrumbs;

		array_shift($breadcrumbs);

		$parents = [];
		$isFirst = true;
		foreach ($object->parents()->all() as $parent) {
			if ($isFirst) {
				$isFirst = false;
				continue;
			}

			$item = ['label' => $parent->name];
			$url = $parent->createUrl();
			if ($url != '#')
				$item['url'] = $url;

			$parents[] = $item;
		}

		$item = ['label' => $object->name];
		$url = $parent->createUrl();
		if (!empty($breadcrumbs) && $url != '#')
			$item['url'] = $url;
		$parents[] = $item;

		return array_merge($parents, $breadcrumbs);
	}

}
