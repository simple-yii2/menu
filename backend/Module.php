<?php

namespace menu\backend;

use Yii;

use menu\common\models\Menu;

/**
 * Menu backend module
 */
class Module extends \yii\base\Module {

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->checkDatabase();
		$this->addTranslation();
	}

	/**
	 * Database checking
	 * @return void
	 */
	protected function checkDatabase()
	{
		//schema
		$db = Yii::$app->db;
		$filename = dirname(__DIR__) . '/schema/' . $db->driverName . '.sql';
		$sql = explode(';', file_get_contents($filename));
		foreach ($sql as $s) {
			if (trim($s) !== '')
				$db->createCommand($s)->execute();
		}

		//rbac
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('menu') === null) {
			//menu role
			$menu = $auth->createRole('menu');
			$auth->add($menu);
		}

		//data
		$root = Menu::find()->roots()->one();
		if ($root === null) {
			$root = new Menu(['name' => 'Root']);
			$root->makeRoot();
		}
	}

	/**
	 * Adding translation to i18n
	 * @return void
	 */
	protected function addTranslation()
	{
		Yii::$app->i18n->translations['menu'] = [
			'class'=>'yii\i18n\PhpMessageSource',
			'sourceLanguage'=>'en-US',
			'basePath'=>'@menu/messages',
		];
	}

	/**
	 * Making menu item of module
	 * @return array
	 */
	public function getMenuItem()
	{
		if (Yii::$app->user->can('menu')) {
			return [
				['label' => Yii::t('menu', 'Main menu'), 'url' => ['/menu/menu/index']],
			];
		}
		
		return [];
	}

}
