<?php

use Tests\Support\Providers\GivenProvider;
use Tequilarapido\Testing\Support\GivenBuilder;
use Tequilarapido\Testing\Support\Tester;

if (!function_exists('given')) {
    /**
     * @return GivenBuilder
     * @throws Exception
     */
    function given()
    {
        static $given;

        if (!$given) {
            if (!class_exists(GivenProvider::class)) {
                throw new Exception('You need to define a GivenProvider class.');
            }

            $given = new GivenBuilder(new GivenProvider);
        }

        return $given;
    }
}

if (!function_exists('when')) {
    /** @return \Tests\Feature\FeatureTestCase */
    function when()
    {
        return Tester::instance()->testCase;
    }
}
if (!function_exists('then')) {
    /** @return \Tests\Feature\FeatureTestCase */
    function then()
    {
        return Tester::instance()->testCase;
    }
}



