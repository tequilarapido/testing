<?php

namespace Tequilarapido\Testing\Concerns;

trait CommonAsserts
{
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
}
