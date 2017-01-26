<?php

namespace Tequilarapido\Testing\Concerns;

trait CommonAsserts
{
    /**
     * Can be used to wrap phpunit asserts and continue chaining.
     *
     * @param $callable
     * @return $this
     */
    public function verify($callable)
    {
        if (is_callable($callable)) {
            call_user_func($callable);
        }

        return $this;
    }

    /**
     * ->verify() alias.
     *
     * @param $callable
     * @return $this
     */
    public function andVerify($callable)
    {
        return $this->verify($callable);
    }

    /**
     * Assert page locale.
     *
     * @param $expectedLocale
     * @param string $message
     * @return $this
     */
    public function seePageLocaleIs($expectedLocale, $message = '')
    {
        $this->assertEquals($expectedLocale, $this->crawler()->filter('html')->attr('lang'), $message);

        return $this;
    }

    /**
     * Assert false wrapper.
     *
     * @param $actual
     * @param string $message
     * @return $this
     */
    public function assertIsFalse($actual, $message = '')
    {
        $this->assertFalse($actual, $message);

        return $this;
    }


    /**
     * Assert true wrapper.
     *
     * @param $actual
     * @param string $message
     * @return $this
     */
    public function assertIsTrue($actual, $message = '')
    {
        $this->assertTrue($actual, $message);

        return $this;
    }

    /**
     * Assert equals wrapper.
     *
     * @param $actual
     * @param $expected
     * @param string $message
     * @return $this
     */
    public function assertIsEqual($actual, $expected, $message = '')
    {
        $this->assertEqual($actual, $expected, $message);

        return $this;
    }

    /**
     * See escaped text.
     *
     * @param $text
     * @return mixed
     */
    public function seeEscaped($text)
    {
        return $this->see(htmlentities($text));
    }

    /**
     * Asserts session has errors.
     *
     * @return $this
     */
    public function assertErrorsInSession()
    {
        $this->assertSessionHas('errors');

        return $this;
    }
}
