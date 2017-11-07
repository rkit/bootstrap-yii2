<?php

namespace app\parsers\oauth;

use yii\helpers\ArrayHelper;

/**
 * Parser for Twitter OAuth
 *
 * @property ClientInterface $client
 * @property array $profile
 * @property array $token
 */
class Twitter extends Parser
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
            'profile_url' => 'https://twitter.com/' . ArrayHelper::getValue($this->profile, 'screen_name'),
            'access_token' => ArrayHelper::getValue($this->token, 'oauth_token'),
            'access_token_secret' => ArrayHelper::getValue($this->token, 'oauth_token_secret')
        ];
    }

    /**
     * Get profile info
     *
     * @return array
     */
    public function profileData(): array
    {
        $photo = ArrayHelper::getValue($this->profile, 'profile_image_url');
        return [
            'full_name' => $this->profile['name'],
            'photo' => str_replace('_normal', '_400x400', $photo)
        ];
    }
}
