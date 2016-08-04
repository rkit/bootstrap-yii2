<?php

namespace app\tests\unit\helpers;

use Yii;
use app\helpers\Http;

class HttpTest extends \Codeception\Test\Unit
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Bad request
     */
    public function testException400()
    {
        Http::exception(400);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Access is unauthorized
     */
    public function testException401()
    {
        Http::exception(401);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Access Denied
     */
    public function testException403()
    {
        Http::exception(403);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Page not found
     */
    public function testException404()
    {
        Http::exception(404);
    }
}
