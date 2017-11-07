<?php

namespace app\modules\auth\services;

use yii\authclient\ClientInterface;
use app\models\entity\{User, UserProvider};

/**
 * SocialAuth handles successful authentication
 */
class SocialAuth
{
    /**
     * @var int
     */
    private $provider;
    /**
     * @var int
     */
    private $providerId;
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var array
     */
    public $parsers;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->provider = UserProvider::getTypeByName($client->id);
        $this->providerId = $client->getUserAttributes()['id'];
    }

    public function prepareUser(): ?User
    {
        $user = null;
        $parser = $this->parser();

        $profileData = $parser->profileData();
        $tokenData = ['type' => $this->provider] + $parser->tokenData();

        if ($provider = UserProvider::find()->provider($this->provider, $this->providerId)->one()) {
            $user = $provider->user;

            // if exist then update access tokens
            $provider->setAttributes($tokenData);
            $provider->save();
        }

        if (!is_object($user)) {
            $user = new User();

            $user->email = $parser->email();
            $user->setProfile($profileData);
            $user->setProviders($tokenData);
        }

        return $user;
    }

    private function parser()
    {
        $parserClass = $this->parsers[$this->client->id];
        return new $parserClass($this->client);
    }
}
