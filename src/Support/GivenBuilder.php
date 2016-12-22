<?php

namespace Tequilarapido\Testing\Support;


class GivenBuilder
{
    private $provider;

    /** @var  array */
    private $steps;

    /** @var mixed */
    private $lastResultStep = null;

    /**
     * GivenBuilder constructor.
     *
     * @param $provider
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Return provider
     *
     * @return mixed
     */
    public function provider()
    {
        return $this->provider;
    }

    /**
     * Set last result as property on the test case.
     *
     * @param $propertyName
     * @return $this
     */
    public function as($propertyName)
    {
        $this->setWorldProperty($propertyName, $this->lastResultStep);

        $this->lastResultStep = null;

        return $this;
    }

    /**
     * Execute a callback.
     *
     * @param null $callback
     * @return GivenBuilder
     */
    public function having($callback = null)
    {
        return $this->callback($callback);
    }

    /**
     * Execute a callback,.
     *
     * @param null $callback
     * @return GivenBuilder
     */
    public function and($callback = null)
    {
        return $this->callback($callback);
    }

    /**
     * Dump the builder steps and world (dynamic properties).
     */
    public function dump()
    {
        dd([
            'steps' => $this->steps,
            'world' => $this->testCase()->world,
        ]);
    }

    /**
     * Forward method to the Given or the TestCase.
     *
     * @param $name
     * @param $arguments
     * @return $this
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $this->steps[] = $name;

        $method = $this->extractMethod($name);

        // Try to delegate to the provider, and store the result as the last step result
        if (method_exists($this->provider, $method)) {
            $this->lastResultStep = call_user_func_array([$this->provider, $method], $arguments);

            return $this;
        }

        // try to delegate to the test case
        if (method_exists($testCase = Tester::instance()->testCase, $method)) {
            call_user_func_array([$testCase, $method], $arguments);

            return $this;
        }

        throw new \Exception("[Given] Unable to guess what to do with $method.  (Unfound on the Given or the Testcase).");
    }

    private function testCase()
    {
        return Tester::instance()->testCase;
    }

    private function setWorldProperty($key, $value)
    {
        $this->testCase()->world[$key] = $value;
    }

    private function extractMethod($name)
    {
        // Starts with and : andABC -> aBC
        if (substr($name, 0, 3) === 'and') {
            return lcfirst(str_replace('and', '', $name));
        }

        return $name;
    }

    private function callback($callback = null)
    {
        $this->lastResultStep = null;

        if (!empty($callback) && is_callable($callback)) {
            call_user_func($callback);
        }

        return $this;
    }
}
