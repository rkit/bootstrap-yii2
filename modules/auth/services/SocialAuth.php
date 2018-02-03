<?php

namespace app\modules\auth\services;

use yii\authclient\ClientInterface;
use app\modules\auth\oauth\ParserInterface;
use app\models\entity\{User, UserProvider};

/**
 * SocialAuth handles successful authentication
 */
class SocialAuth
{
    /**
     * @var ParserInterface
     */
    private $parser;
    /**
     * @var ClientInterface
     */
    private $provider;
    /**
     * @var int
     */
    private $providerType;

    /**
     * @param ParserInterface $parser
     * @param ClientInterface $provider
     */
    public function __construct(ParserInterface $parser, ClientInterface $provider)
    {
        $this->parser = $parser;
        $this->provider = $provider;
        $this->providerType = UserProvider::getTypeByName($provider->id);
    }

    public function getUser(): User
    {
        $user = null;

        $existUserProvider = UserProvider::find()
            ->provider($this->providerType, $this->parser->profileId())
            ->one();

        if ($existUserProvider) {
            $user = $existUserProvider->user;

            // if exist then update access tokens
            $existUserProvider->setAttributes($this->providerAttributes(), false);
            $existUserProvider->save();
        }

        if (!is_object($user)) {
            $user = new User();

            $user->email = $this->parser->email();
            $user->setProfile($this->profileAttributes());
            $user->setProviders($this->providerAttributes());
        }

        return $user;
    }

    private function profileAttributes()
    {
        return [
            'full_name' => $this->parser->fullName(),
            'photo' => $this->parser->photo(),
        ];
    }

    private function providerAttributes()
    {
        return [
            'type' => $this->providerType,
            'profile_id' => $this->parser->profileId(),
            'profile_url' => $this->parser->profileUrl(),
            'access_token' => $this->parser->accessToken(),
            'access_token_secret' => $this->parser->accessTokenSecret(),
        ];
    }
}
