<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use app\components\BaseActive;

/**
 * This is the model class for table "user_provider"
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $profile_id
 * @property string $profile_url
 * @property string $access_token
 * @property string $access_token_secret
 */
class UserProvider extends BaseActive
{
    const TYPE_TWITTER   = 1;
    const TYPE_FACEBOOK  = 2;
    const TYPE_VKONTAKTE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'profile_id', 'profile_url', 'access_token', 'access_token_secret'], 'safe'],
        ];
    }

    /**
     * Get types
     *
     * @param string $type
     * @return array|int
     */
    public static function getTypes($type = null)
    {
        $types = [
            self::TYPE_TWITTER => 'twitter',
            self::TYPE_FACEBOOK => 'facebook',
            self::TYPE_VKONTAKTE => 'vkontakte',
        ];

        if ($type) {
            return array_flip($types)[$type];
        }

        return $types;
    }

    /**
     * Get type by name
     *
     * @param string $name
     * @param int
     */
    public static function getTypeByName($name)
    {
        return array_flip(self::getTypes())[$name];
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function getTypeName()
    {
        $types = $this->getTypes();
        return isset($types[$this->type]) ? ucfirst($types[$this->type]) : '';
    }

    /**
     * Parse provider
     *
     * @param int $type Type of provider
     * @param array $data Data from social network
     * @return array
     */
    public static function parseProvider($type, $data)
    {
        switch ($type) {
            case self::TYPE_FACEBOOK:
                return self::parseProviderFacebook($data);

            case self::TYPE_VKONTAKTE:
                return self::parseProviderVkontakte($data);

            case self::TYPE_TWITTER:
                return self::parseProviderTwitter($data);
        }

        return [];
    }

    /**
     * Parse profile
     *
     * @param int $type Type of provider
     * @param array $data Data from social network
     * @return array
     */
    public static function parseProfile($type, $data)
    {
        switch ($type) {
            case self::TYPE_FACEBOOK:
                return self::parseProfileFacebook($data);

            case self::TYPE_VKONTAKTE:
                return self::parseProfileVkontakte($data);

            case self::TYPE_TWITTER:
                return self::parseProfileTwitter($data);
        }

        return [];
    }

    /**
     * Prepare provider attributes for facebook
     *
     * @param array $data Data from social network
     * @return array
     */
    private static function parseProviderFacebook($data)
    {
        return [
            'type' => self::TYPE_FACEBOOK,
            'profile_id' => $data['profile']['id'],
            'profile_url' => $data['profile']['link'],
            'access_token' => $data['token']['access_token'],
            'access_token_secret' => ''
        ];
    }

    /**
     * Prepare provider attributes for vkontakte
     *
     * @param array $data Data from social network
     * @return array
     */
    private static function parseProviderVkontakte($data)
    {
        return [
            'type' => self::TYPE_VKONTAKTE,
            'profile_id' => $data['profile']['id'],
            'profile_url' => 'https://vk.com/id' . $data['profile']['id'],
            'access_token' => $data['token']['access_token'],
            'access_token_secret' => ''
        ];
    }

    /**
     * Prepare provider attributes for twitter
     *
     * @param array $data Data from social network
     * @return array
     */
    private static function parseProviderTwitter($data)
    {
        return [
            'type' => self::TYPE_TWITTER,
            'profile_id' => $data['profile']['id'],
            'profile_url' => 'https://twitter.com/' . $data['profile']['screen_name'],
            'access_token' => $data['token']['oauth_token'],
            'access_token_secret' => $data['token']['oauth_token_secret']
        ];
    }

    /**
     * Prepare profile attributes for facebook
     *
     * @param array $data Data from social network
     * @return array
     */
    private static function parseProfileFacebook($data)
    {
        return [
            'full_name' => trim($data['profile']['name']),
            'birth_day' => '—',
            'photo' => ArrayHelper::getValue($data, 'profile.picture.data.url', '')
        ];
    }

    /**
     * Prepare profile attributes for vkontakte
     *
     * @param array $data Data from social network
     * @return array
     */
    private static function parseProfileVkontakte($data)
    {
        return [
            'full_name' => trim($data['profile']['first_name'] . ' ' . $data['profile']['last_name']),
            'birth_day' => date_format(date_create_from_format('d.m.Y', $data['profile']['bdate']), 'Y-m-d'),
            'photo' => str_replace('_50', '_400', $data['profile']['photo'])
        ];
    }

    /**
     * Prepare profile attributes for twitter
     *
     * @param array $data Data from social network
     * @return array
     */
    private static function parseProfileTwitter($data)
    {
        return [
            'full_name' => $data['profile']['name'],
            'photo' => str_replace('_normal', '_400x400', $data['profile']['profile_image_url'])
        ];
    }
}
