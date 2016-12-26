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
	 * @var string Route to page module
	 */
	private static $_pageRoute = '/page/page/index';

	/**
	 * @var string Route to gallery module
	 */
	private static $_galleryRoute = '/gallery/gallery/index';

	/**
	 * @var string Route to contact module
	 */
	private static $_contactsRoute = '/contact/contact/index';

	/**
	 * @var string Route to news module
	 */
	private static $_newsRoute = '/news/news/index';

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
			'url' => self::createUrl($child),
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

	/**
	 * Create item url
	 * @param models\Menu $item 
	 * @return string|array
	 */
	public static function createUrl($item)
	{
		switch ($item->type) {
			case models\Menu::TYPE_LINK:
				$url = $item->url;
				break;

			case models\Menu::TYPE_PAGE:
				$url = [self::getPageRoute(), 'alias' => $item->alias];
				break;

			case models\Menu::TYPE_GALLERY:
				$url = [self::getGalleryRoute(), 'alias' => $item->alias];
				break;
			
			case models\Menu::TYPE_CONTACTS:
				$url = [self::getContactsRoute()];
				break;
			
			case models\Menu::TYPE_NEWS:
				$url = [self::getNewsRoute()];
				break;
			
			default:
				$url = '#';
				break;
		}

		return $url;
	}

	/**
	 * Get route to page module
	 * @return string
	 */
	public static function getPageRoute()
	{
		if (self::$_pageRoute === null)
			self::prepareRoutes();
		
		return self::$_pageRoute;
	}

	/**
	 * Get route to gallery module
	 * @return string
	 */
	public static function getGalleryRoute()
	{
		if (self::$_galleryRoute === null)
			self::prepareRoutes();
		
		return self::$_galleryRoute;
	}

	/**
	 * Get route to contacts module
	 * @return string
	 */
	public static function getContactsRoute()
	{
		if (self::$_contactsRoute === null)
			self::prepareRoutes();
		
		return self::$_contactsRoute;
	}

	/**
	 * Get route to news module
	 * @return string
	 */
	public static function getNewsRoute()
	{
		if (self::$_newsRoute === null)
			self::prepareRoutes();
		
		return self::$_newsRoute;
	}

	/**
	 * Determine routes from application module config
	 * @return void
	 */
	private static function prepareRoutes()
	{
		foreach (Yii::$app->getModules(false) as $name => $module) {
			if (is_string($module)) {
				$className = $module;
			} elseif (is_array($module)) {
				$className = $module['class'];
			} else {
				$className = $module::className();
			}

			if ($className == 'cms\page\frontend\Module')
				self::$_pageRoute = '/' . $name . '/page/index';

			if ($className == 'cms\gallery\frontend\Module')
				self::$_galleryRoute = '/' . $name . '/gallery/index';

			if ($className == 'cms\contact\frontend\Module')
				self::$_contactsRoute = '/' . $name . '/contact/index';

			if ($className == 'cms\news\frontend\Module')
				self::$_newsRoute = '/' . $name . '/news/index';
		}
	}

}
