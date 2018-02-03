<?php

namespace app\modules\auth\oauth;

interface ParserInterface
{
    /**
     * @return null|string
     */
    public function email(): ?string;
    /**
     * @return string
     */
    public function fullName(): string;
    /**
     * @return string
     */
    public function photo(): string;
    /**
     * @return string
     */
    public function profileId(): string;
    /**
     * @return string
     */
    public function profileUrl(): string;
    /**
     * @return string
     */
    public function accessToken(): string;
    /**
     * @return string
     */
    public function accessTokenSecret(): string;
}
