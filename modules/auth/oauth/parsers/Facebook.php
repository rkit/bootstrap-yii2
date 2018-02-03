<?php

namespace app\modules\auth\oauth\parsers;

use yii\helpers\ArrayHelper;
use app\modules\auth\oauth\Parser;

/**
 * Parser for Facebook OAuth
 */
class Facebook extends Parser
{
    public function email(): ?string
    {
        return ArrayHelper::getValue($this->attributes, 'email', null);
    }

    public function fullName(): string
    {
        return trim(ArrayHelper::getValue($this->attributes, 'name', ''));
    }

    public function photo(): string
    {
        $photo = ArrayHelper::getValue($this->attributes, 'picture.data.url', '');
    
        $url = 'https://graph.facebook.com/' . $this->profileId() . '/picture?width=500&redirect=false';
        $res = @json_decode(@file_get_contents($url));

        if (is_object($res) && isset($res->data)) {
            $photo = $res->data->url;
        }

        return $photo;
    }

    public function profileId(): string
    {
        return ArrayHelper::getValue($this->attributes, 'id', '');
    }

    public function profileUrl(): string
    {
        return ArrayHelper::getValue($this->attributes, 'link', '');
    }

    public function accessToken(): string
    {
        return ArrayHelper::getValue($this->tokens, 'access_token', '');
    }

    public function accessTokenSecret(): string
    {
        return '';
    }
}
