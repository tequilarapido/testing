<?php

namespace Tequilarapido\Testing\Support;

use App\Tests\Support\Providers\GivenProvider;

class GivenBuilder
{
    /** @var GivenProviderInterface */
    private $provider;

    /** @var  array */
    private $steps;

    /** @var mixed */
    private $lastResultStep = null;

    /**
     * GivenBuilder constructor.
     *
     * @param GivenProviderInterface $provider
     */
    public function __construct(GivenProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return GivenProviderInterface | GivenProvider
     */
    public function provider()
    {
        return $this->provider;
    }

    public function dump()
    {
        dd([
            'steps' => $this->steps,
            'world' => $this->testCase()->world
        ]);
    }

    public function __call($name, $arguments)
    {
        $this->steps[] = $name;

        // And : just chaining
        if ('and' === $name) {
            $this->lastResultStep = null;

            // and(Closure) : Is there any callback to execute
            if (!empty($arguments[0]) && is_callable($arguments[0])) {
                call_user_func($arguments[0]);
            }

            return $this;
        }

        // As : try to set a world property with the given name
        // with the given last step result.
        if ('as' === $name) {
            $this->setWorldProperty($arguments[0], $this->lastResultStep);
            $this->lastResultStep = null;

            return $this;
        }

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

        throw new \Exception("[Given] Unable to guess what to do with $method.  (Unfound on the GivenProvider or the Testcase).");
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
}