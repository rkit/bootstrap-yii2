<?php

namespace app\parsers\oauth;

interface ParserInterface
{
    public function email();
    public function tokenData();
    public function profileData();
}
