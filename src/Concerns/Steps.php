<?php

namespace Tequilarapido\Testing\Concerns;

trait Steps
{
    public function am($message)
    {
        // nothing , just for test readability
        return $this;
    }

    public function wantTo($message)
    {
        // nothing , just for test readability
        return $this;
    }

    public function expect($message)
    {
        // nothing , just for test readability
        return $this;
    }

    public function I($message)
    {
        // nothing , just for test readability
        return $this;
    }

    public function toBeImplemented($message = 'To be implemented.')
    {
        $this->markTestIncomplete($message);
    }

    public function toBeCompleted($message = 'To be completed.')
    {
        $this->markTestIncomplete($message);
    }
}