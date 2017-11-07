<?php

namespace app\parsers\oauth;

use yii\authclient\ClientInterface;

abstract class Parser implements ParserInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;
    /**
     * @var array
     */
    protected $profile;
    /**
     * @var array
     */
    protected $token;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->profile = $client->getUserAttributes();
        $this->token = $client->getAccessToken()->getParams();
    }
}
