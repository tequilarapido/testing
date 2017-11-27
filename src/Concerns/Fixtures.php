<?php

namespace Tequilarapido\Testing\Concerns;

trait Fixtures
{
    public static function fixturePath($fixture)
    {
        $path = base_path("/tests/_fixtures/$fixture") ;
        $realpath = realpath($path);

        if (!$realpath) {
            throw new \LogicException("Unable to find fixture path [$path]");
        }

        return $realpath;
    }

    public static function realFixturePath($fixture)
    {
        if ($file = realpath(self::fixturePath($fixture))) {
            return $file;
        }

        throw new \Exception("Cannot find fixture file $fixture");
    }

    public static function fixtureContent($fixture)
    {
        return file_get_contents(static::realFixturePath($fixture));
    }

    public static function fixtureJson($fixture)
    {
        return json_decode(static::fixtureContent($fixture), true);
    }
}
