<?php

namespace app\services;

use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\UserProvider;

/**
 * SocialAuth handles successful authentication
 */
class SocialAuth
{
    /**
     * @var int
     */
    private $type;
    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $email;
    /**
     * @var bool
     */
    private $isVerified = false;
    /**
     * @var bool
     */
    private $isExist = false;
    /**
     * @var ClientInterface
     */
    private $client;

    public function execute(ClientInterface $client): SocialAuth
    {
        $this->client = $client;
        $this->type = UserProvider::getTypeByName($client->id);

        $user = $this->findUserByProvider();
        if ($user) {
            $this->isExist = true;
        } else {
            $profile = $this->client->getUserAttributes();
            $this->email = ArrayHelper::getValue($profile, 'email');
            $this->isVerified = ArrayHelper::getValue($profile, 'verified', false);

            if ($this->isVerified && !empty($this->email)) {
                $user = User::find()->email($this->email)->one();
            }

            if (!$user) {
                $user = new User();
                $user->setProfile($this->parseProfile());
            }

            if ($this->isVerified) {
                $user->setConfirmed();
            }
        }

        $user->setProviders($this->parseProvider());
        $this->user = $user;

        return $this;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function isExist(): bool
    {
        return $this->isExist;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Find user by provider
     *
     * @return app\models\User|null
     */
    private function findUserByProvider(): ?User
    {
        $profile = $this->client->getUserAttributes();
        $id = ArrayHelper::getValue($profile, 'id');
        if ($provider = UserProvider::find()->provider($this->type, $id)->one()) {
            $user = $provider->user;
            $provider->setAttributes($this->parseProvider());
            $provider->save();

            return $user;
        }
        return null;
    }

    /**
     * Parse provider
     *
     * @return array
     */
    private function parseProvider(): array
    {
        $profile = $this->client->getUserAttributes();
        $token = $this->client->getAccessToken()->getParams();

        $data = [];
        switch ($this->type) {
            case UserProvider::TYPE_FACEBOOK:
                $data = $this->parseProviderFacebook($profile, $token);
                break;

            case UserProvider::TYPE_VKONTAKTE:
                $data = $this->parseProviderVkontakte($profile, $token);
                break;

            case UserProvider::TYPE_TWITTER:
                $data = $this->parseProviderTwitter($profile, $token);
                break;
        }
        $data['type'] = $this->type;
        return $data;
    }

    /**
     * Parse profile
     *
     * @return array
     */
    private function parseProfile(): array
    {
        $profile = $this->client->getUserAttributes();

        $data = [];
        switch ($this->type) {
            case UserProvider::TYPE_FACEBOOK:
                $data = $this->parseProfileFacebook($profile);
                break;

            case UserProvider::TYPE_VKONTAKTE:
                $data = $this->parseProfileVkontakte($profile);
                break;

            case UserProvider::TYPE_TWITTER:
                $data = $this->parseProfileTwitter($profile);
                break;
        }
        return $data;
    }

    /**
     * Prepare provider attributes for facebook
     *
     * @param array $profile
     * @param array $token
     * @return array
     */
    private function parseProviderFacebook(array $profile, array $token): array
    {
        return [
            'profile_id' => ArrayHelper::getValue($profile, 'id'),
            'profile_url' => ArrayHelper::getValue($profile, 'link'),
            'access_token' => ArrayHelper::getValue($token, 'access_token'),
            'access_token_secret' => ''
        ];
    }

    /**
     * Prepare provider attributes for vkontakte
     *
     * @param array $profile
     * @param array $token
     * @return array
     */
    private function parseProviderVkontakte(array $profile, array $token): array
    {
        return [
            'profile_id' => ArrayHelper::getValue($profile, 'id'),
            'profile_url' => 'https://vk.com/id' . ArrayHelper::getValue($profile, 'id'),
            'access_token' => ArrayHelper::getValue($token, 'access_token'),
            'access_token_secret' => ''
        ];
    }

    /**
     * Prepare provider attributes for twitter
     *
     * @param array $profile
     * @param array $token
     * @return array
     */
    private function parseProviderTwitter(array $profile, array $token): array
    {
        return [
            'profile_id' => ArrayHelper::getValue($profile, 'id'),
            'profile_url' => 'https://twitter.com/' . ArrayHelper::getValue($profile, 'screen_name'),
            'access_token' => ArrayHelper::getValue($token, 'oauth_token'),
            'access_token_secret' => ArrayHelper::getValue($token, 'oauth_token_secret')
        ];
    }

    /**
     * Prepare profile attributes for facebook
     *
     * @param array $profile
     * @return array
     */
    private function parseProfileFacebook(array $profile): array
    {
        return [
            'full_name' => trim(ArrayHelper::getValue($profile, 'name')),
            'birth_day' => '',
            'photo' => ArrayHelper::getValue($profile, 'picture.data.url', '')
        ];
    }

    /**
     * Prepare profile attributes for vkontakte
     *
     * @param array $profile
     * @return array
     */
    private function parseProfileVkontakte(array $profile): array
    {
        $firstName = ArrayHelper::getValue($profile, 'first_name');
        $lastName = ArrayHelper::getValue($profile, 'last_name');
        $birthDay = date_create_from_format('d.m.Y', ArrayHelper::getValue($profile, 'bdate'));
        return [
            'full_name' => trim($firstName . ' ' . $lastName),
            'birth_day' => date_format($birthDay, 'Y-m-d'),
            'photo' => str_replace('_50', '_400', ArrayHelper::getValue($profile, 'photo'))
        ];
    }

    /**
     * Prepare profile attributes for twitter
     *
     * @param array $profile
     * @return array
     */
    private function parseProfileTwitter(array $profile): array
    {
        $photo = ArrayHelper::getValue($profile, 'profile_image_url');
        return [
            'full_name' => $profile['name'],
            'photo' => str_replace('_normal', '_400x400', $photo)
        ];
    }
}
