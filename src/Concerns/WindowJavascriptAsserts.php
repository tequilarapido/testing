<?php

namespace Tequilarapido\Testing\Concerns;

trait WindowJavascriptAsserts
{
    public function seeJavascriptVariable($var)
    {
        $this->assertIsTrue(
            !is_null($this->jsExtractVar($var)),
            "Unable to find javascript variable [$var]"
        );

        return $this;
    }

    public function decodeJavascriptVariable($var)
    {
        $raw = $this->jsExtractVar($var);

        // First, assert that we have the variable
        $this->assertIsTrue(
            !is_null($raw),
            "Unable to find javascript variable [$var]"
        );

        return json_decode($raw);
    }

    private function jsExtractVar($var)
    {
        $regex = '/^' . preg_quote($var, '/') . ' = (.*?);$/ms';

        if (preg_match($regex, $this->response->getContent(), $matches)) {
            if (!empty($matches[1])) {
                return $matches[1];
            }
        }

        return null;
    }

}