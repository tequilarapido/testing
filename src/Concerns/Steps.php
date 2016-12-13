<?php

namespace Tequilarapido\Testing\Concerns;

trait Steps
{
    /**
     * Can be used to wrap actions in when() block.
     *
     * @param $callable
     * @return $this
     */
    public function actions($callable)
    {
        if (is_callable($callable)) {
            call_user_func($callable);
        }

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
}