<?php

namespace Tequilarapido\Testing\Concerns;

trait LocalFixtures
{
    public static function localFixturePath($fixture)
    {
        $realpath = realpath($fixture);

        if (!$realpath) {
            throw new \LogicException("Unable to find fixture path [$fixture]");
        }

        return $realpath;
    }

    public static function localRealFixturePath($fixture)
    {
        if ($file = realpath(self::fixturePath($fixture))) {
            return $file;
        }

        throw new \Exception("Cannot find fixture file $fixture");
    }

    public static function localFixtureContent($fixture)
    {
        return file_get_contents(static::realFixturePath($fixture));
    }

    public static function localFixtureJson($fixture)
    {
        return json_decode(static::fixtureContent($fixture), true);
    }
}
