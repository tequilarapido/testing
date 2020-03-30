<?php

namespace Tequilarapido\Testing\Concerns;

use PHPUnit\TextUI\Configuration\PHPUnit;
use Laravel\BrowserKitTesting\Concerns\MakesHttpRequests as MakesHttpRequestBrowserKit;

trait MakesHttpRequests
{
    use MakesHttpRequestBrowserKit;

    /**
     * Validate and return the decoded response JSON.
     *
     * @return array
     */
    protected function decodeResponseJson()
    {
        $decodedResponse = json_decode($this->response->getContent(), true);

        if (is_null($decodedResponse) || $decodedResponse === false) {
            $this->fail("JSON was not returned from [{$this->currentUri}].");
        }

        return $decodedResponse;
    }
}
