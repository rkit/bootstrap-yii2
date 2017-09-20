<?php

namespace app\parsers\auth;

interface ParserInterface
{
    public function email();
    public function tokenData();
    public function profileData();
}
