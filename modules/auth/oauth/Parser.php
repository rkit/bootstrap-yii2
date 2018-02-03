<?php

namespace app\modules\auth\oauth;

use yii\authclient\ClientInterface;

abstract class Parser implements ParserInterface
{
    /**
     * @var ClientInterface
     */
    protected $provider;
    /**
     * @var array
     */
    protected $attributes;
    /**
     * @var array
     */
    protected $tokens;

    public function __construct(ClientInterface $provider)
    {
        $this->provider = $provider;
        $this->attributes = $provider->getUserAttributes();
        $this->tokens = $provider->getAccessToken()->getParams();
    }
}
