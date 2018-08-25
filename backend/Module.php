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
    protected static function cmsSecurity()
    {
        $auth = Yii::$app->getAuthManager();
        if ($auth->getRole('Menu') === null) {
            //role
            $role = $auth->createRole('Menu');
            $auth->add($role);
        }
    }

    /**
     * @inheritdoc
     */
    public function cmsMenu()
    {
        if (!Yii::$app->user->can('Menu')) {
            return [];
        }

        return [
            'label' => Yii::t('menu', 'Menus'),
            'url' => ["/menu/menu/index"],
        ];
    }

}
