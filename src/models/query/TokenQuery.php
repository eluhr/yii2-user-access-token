<?php

namespace eluhr\userAccessToken\models\query;

use eluhr\userAccessToken\helpers\DateHelper;
use eluhr\userAccessToken\models\Token;
use yii\db\ActiveQuery;

/**
 * @see \eluhr\userAccessToken\models\Token
 *
 * @method Token[]|array all($db = null)
 * @method Token|null one($db = null)
 */
class TokenQuery extends ActiveQuery
{
    /**
     * Filter query by token
     *
     * @param string $token
     *
     * @return TokenQuery
     *
     */
    public function token(string $token): TokenQuery
    {
        return $this->andWhere(['token' => $token]);
    }

    /**
     * Check if token is expired based on current time
     *
     * @return TokenQuery
     */
    public function notIsExpired(): TokenQuery
    {
        return $this->andWhere(['>', 'expires_at', DateHelper::now()]);
    }
}
