<?php

namespace app\parsers\auth;

use yii\authclient\ClientInterface;
use app\parsers\auth\ParserInterface;

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
