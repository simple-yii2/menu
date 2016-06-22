<?php

namespace menu\backend\assets;

use yii\web\AssetBundle;

class MenuFormAsset extends AssetBundle
{

	public $sourcePath = __DIR__ . '/menu-form';

	public $js = [
		'menu-form.js',
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
	];

}
