<?php

namespace eluhr\userAuthToken\models;

use DateTimeImmutable;
use eluhr\userAuthToken\interfaces\TokenInterface;
use eluhr\userAuthToken\models\query\TokenQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property string $token
 * @property string $user_id
 * @property string $expires_at
 * @property string $created_at
 *
 * @property-read IdentityInterface $user
 */
class Token extends ActiveRecord implements TokenInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_auth_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'token',
            'filter',
            'filter' => 'trim'
        ];
        $rules[] = [
            [
                'token',
                'user_id',
                'expires_at'
            ],
            'required'
        ];
        $rules[] = [
            'token',
            'unique'
        ];
        $rules[] = [
            'token',
            'string',
            'max' => 128
        ];
        $rules[] = [
            'expires_at',
            'date',
            'format' => 'php:Y-m-d H:i:s',
            'min' => (new DateTimeImmutable())->format('Y-m-d H:i:s')
        ];
        $rules[] = [
            'created_at',
            'date',
            'format' => 'php:Y-m-d H:i:s'
        ];
        $rules[] = [
            'user_id',
            'exist',
            'targetClass' => Yii::$app->getUser()->identityClass,
            'targetAttribute' => 'id'
        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            // always set created_at on insert. Do not allow to set it manually
            $this->setAttribute('created_at', (new DateTimeImmutable())->format('Y-m-d H:i:s'));
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['token'] = Yii::t('eluhr.user-auth-token', 'Token');
        $attributeLabels['user_id'] = Yii::t('eluhr.user-auth-token', 'User ID');
        $attributeLabels['expires_at'] = Yii::t('eluhr.user-auth-token', 'Expires At');
        $attributeLabels['created_at'] = Yii::t('eluhr.user-auth-token', 'Created At');
        return $attributeLabels;
    }

    /**
     * @inheritdoc
     *
     * @return TokenQuery
     */
    public static function find()
    {
        return new TokenQuery(static::class);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(Yii::$app->getUser()->identityClass, ['id' => 'user_id']);
    }


    /**
     * @inheritdoc
     */
    public static function findValidToken(string $token): ?Token
    {
        $model = static::find()->token($token)->notIsExpired()->one();
        // check if entry exists and is valid
        if ($model && $model->isValid()) {
            return $model;
        }
        return null;
    }

    /**
     * This method should be overwritten in child classes
     *
     * @inheritdoc
     */
    public function isValid(): bool
    {
        return true;
    }

}
