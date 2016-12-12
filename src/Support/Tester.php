<?php

namespace Tequilarapido\Testing\Support;

use Tequilarapido\Testing\TestCase;

class Tester
{
    /** @var Tester  singleton instance. */
    protected static $instance;

    /** @var  TestCase Current test case */
    public $testCase;

    /**
     * Singleton. Cannot not new up.
     *
     * Tester constructor.
     */
    private function __construct()
    {
    }

    /**
     * Returns singleton instance.
     *
     * @return Tester
     */
    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }

    /**
     * Sets current test case
     *
     * @param TestCase $testCase
     *
     * @return Tester
     */
    public function setTestCase($testCase)
    {
        $this->testCase = $testCase;

        return $this;
    }


}
