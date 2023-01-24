<?php

namespace eluhr\userAccessToken\interfaces;

use eluhr\userAccessToken\models\Token;

interface TokenInterface
{
    /**
     * Find a valid and not expired token
     *
     * @param string $token
     *
     * @return Token|null
     */
    public static function findValidToken(string $token): ?Token;

    /**
     * Check if token is valid.
     *
     * @return bool
     */
    public function isValid(): bool;
}
