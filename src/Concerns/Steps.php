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

    /**
     * Can be used to wrap arrangement in then() block.
     *
     * @param $callable
     * @return $this
     */
    public function having($callable)
    {
        if (is_callable($callable)) {
            call_user_func($callable);
        }

        return $this;
    }

    /**
     * Execute an artisan commande
     *
     * @param $command
     * @param array $parameters
     * @param null $outputBuffer
     * @return $this
     */
    public function console($command, $parameters = [], $outputBuffer = null)
    {
        \Artisan::call($command, $parameters, $outputBuffer);

        $this->consoleOutput = \Artisan::output();
        
        return $this;
    }
    
    public function seeInConsole($string)
    {
        $this->assertContains($string, $this->consoleOutput);

        return $this;
    }

    public function dontSeeInConsole($string)
    {
        $this->assertNotContaines($string, $this->consoleOutput);

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

    public function userWantTo($message)
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
