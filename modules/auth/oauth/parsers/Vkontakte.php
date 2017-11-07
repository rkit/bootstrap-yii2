<?php

namespace app\modules\auth\oauth\parsers;

use yii\helpers\ArrayHelper;
use app\modules\auth\oauth\Parser;

/**
 * Parser for Vkontakte OAuth
 *
 * @property ClientInterface $client
 * @property array $profile
 * @property array $token
 */
class Vkontakte extends Parser
{
    /**
     * Get email
     *
     * @return string|null
     */
    public function email(): ?string
    {
        return ArrayHelper::getValue($this->profile, 'email');
    }

    /**
     * Get token info
     *
     * @return array
     */
    public function tokenData(): array
    {
        return [
            'profile_id' => ArrayHelper::getValue($this->profile, 'id'),
            'profile_url' => 'https://vk.com/id' . ArrayHelper::getValue($this->profile, 'id'),
            'access_token' => ArrayHelper::getValue($this->token, 'access_token'),
            'access_token_secret' => ''
        ];
    }

    /**
     * Get profile info
     *
     * @return array
     */
    public function profileData(): array
    {
        $firstName = ArrayHelper::getValue($this->profile, 'first_name');
        $lastName = ArrayHelper::getValue($this->profile, 'last_name');

        return [
            'full_name' => trim($firstName . ' ' . $lastName),
            'photo' => str_replace('_50', '_400', ArrayHelper::getValue($this->profile, 'photo'))
        ];
    }
}
