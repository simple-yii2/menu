<?php

namespace mainmenu\backend;

use Yii;

use mainmenu\common\models\Mainmenu;

/**
 * Main menu backend module
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
		if ($auth->getRole('mainmenu') === null) {
			//mainmenu role
			$mainmenu = $auth->createRole('mainmenu');
			$auth->add($mainmenu);
		}

		//data
		$root = Mainmenu::find()->roots()->one();
		if ($root === null) {
			$root = new Mainmenu(['name' => 'Root']);
			$root->makeRoot();
		}
	}

	/**
	 * Adding translation to i18n
	 * @return void
	 */
	protected function addTranslation()
	{
		Yii::$app->i18n->translations['mainmenu'] = [
			'class'=>'yii\i18n\PhpMessageSource',
			'sourceLanguage'=>'en-US',
			'basePath'=>'@mainmenu/messages',
		];
	}

	/**
	 * Making main menu item of module
	 * @return array
	 */
	public function getMenuItem()
	{
		if (Yii::$app->user->can('mainmenu')) {
			return [
				['label' => Yii::t('mainmenu', 'Main menu'), 'url' => ['/mainmenu/mainmenu/index']],
			];
		}
		
		return [];
	}

}
