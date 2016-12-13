<?php

use App\Tests\Integration\IntegrationTestCase;
use App\Tests\Support\Providers\GivenProvider;
use Tequilarapido\Testing\Support\GivenBuilder;
use Tequilarapido\Testing\Support\Tester;

if (!function_exists('given')) {
    function given()
    {
        if (!class_exists(GivenProvider::class)) {
            throw new Exception('You need to define a GivenProvider class.');
        }

        return new GivenBuilder(new GivenProvider);
    }
}

if (!function_exists('when')) {
    /** @return IntegrationTestCase */
    function when()
    {
        return Tester::instance()->testCase;
    }
}
if (!function_exists('then')) {
    /** @return IntegrationTestCase */
    function then()
    {
        return Tester::instance()->testCase;
    }
}



