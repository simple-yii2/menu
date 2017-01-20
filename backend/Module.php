<?php

namespace cms\menu\backend;

use Yii;

use cms\components\BackendModule;

/**
 * Menu backend module
 */
class Module extends BackendModule
{

	/**
	 * @inheritdoc
	 */
	public static function moduleName()
	{
		return 'menu';
	}

	/**
	 * @inheritdoc
	 */
	protected static function cmsSecurity()
	{
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('Menu') === null) {
			//menu role
			$menu = $auth->createRole('Menu');
			$auth->add($menu);
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function cmsMenu($base)
	{
		if (!Yii::$app->user->can('Menu'))
			return [];

		return [
			['label' => Yii::t('menu', 'Menus'), 'url' => ["$base/menu/menu/index"]],
		];
	}

}
