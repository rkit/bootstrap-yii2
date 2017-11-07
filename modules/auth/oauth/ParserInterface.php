<?php

namespace app\modules\auth\oauth;

interface ParserInterface
{
    public function email();
    public function tokenData();
    public function profileData();
}
