<?php

use Tests\Support\Given\Given;
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
            if (!class_exists(Given::class)) {
                throw new Exception('You need to define a Given class in yout tests suite.');
            }

            $given = new GivenBuilder(new Given);
        }

        return $given;
    }
}

if (!function_exists('when')) {
    /** @return $this */
    function when()
    {
        return Tester::instance()->testCase;
    }
}
if (!function_exists('then')) {
    /** @return $this */
    function then()
    {
        return Tester::instance()->testCase;
    }
}



