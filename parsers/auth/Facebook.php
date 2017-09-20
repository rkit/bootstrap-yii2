<?php

namespace app\parsers\auth;

use yii\helpers\ArrayHelper;
use app\parsers\auth\Parser;

/**
 * Parser for Facebook OAuth
 *
 * @property ClientInterface $client
 * @property array $profile
 * @property array $token
 */
class Facebook extends Parser
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
            'profile_url' => ArrayHelper::getValue($this->profile, 'link'),
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
        $profileId = ArrayHelper::getValue($this->profile, 'id');
        $photoUrl = 'https://graph.facebook.com/' . $profileId . '/picture?width=500&redirect=false';
        $photoRes = json_decode(file_get_contents($photoUrl));

        if (is_object($photoRes) && isset($photoRes->data)) {
            $photo = $photoRes->data->url;
        } else {
            $photo = ArrayHelper::getValue($this->profile, 'picture.data.url', '');
        }

        return [
            'full_name' => trim(ArrayHelper::getValue($this->profile, 'name')),
            'birth_day' => '',
            'photo' => $photo
        ];
    }
}
