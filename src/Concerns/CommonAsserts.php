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
     * Asserts that a backend form validation has failed.
     *
     * @return $this
     */
    public function seeBackendValidationHasFailed()
    {
        $this->see(trans('presto::backend.common.validation_failed'));
        
        return $this;
    }

    /**
     * Asserts that a backend form validation has failed.
     *
     * @return $this
     */
    public function dontSeeBackendValidationHasFailed()
    {
        $this->dontSee(trans('presto::backend.common.validation_failed'));

        return $this;
    }
}
