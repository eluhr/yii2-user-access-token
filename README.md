# Yii2 User Auth Token

This extension provides a simple way to authenticate users via a token. It is easily extendable and can be used with any
user model.

### Installation

```bash
composer require eluhr/yii2-user-auth-token
```

### Configuration

In your console part of your config file add this:

```php
<?php
return [
    'controllerMap' => [
        'migrate' => [
            'migrationPath' => [
                '@vendor/eluhr/yii2-user-auth-token/src/migrations'
            ]
        ]
    ]
]
```

### Example usage

In your user model you can do this:

```php
<?php

namespace app\models;

use eluhr\userAuthToken\models\Token;
use yii\base\NotSupportedException;
use yii\filters\auth\QueryParamAuth;
use yii\web\IdentityInterface;

class User extends yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @param $token
     * @param string $type
     *
     * @throws NotSupportedException
     * @return IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($type === QueryParamAuth::class) {
            $token = Token::findValidToken($token); // Change this to your token model if needed
            if ($token instanceof Token) {
                return $token->user;
            }
            return null;
        }

        return parent::findIdentityByAccessToken($token, $type);
    }
}
```

In your controller:

```php
<?php

namespace eluhr\userAuthToken\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;

class ExampleController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => ['@']
                ]
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        return 'Hello World';
    }

}
```
