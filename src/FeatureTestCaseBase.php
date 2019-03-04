<?php

namespace Tequilarapido\Testing;

use Laravel\BrowserKitTesting\Concerns\InteractsWithSession;
use Laravel\BrowserKitTesting\Concerns\MakesHttpRequests;
use Laravel\BrowserKitTesting\TestCase;
use Tequilarapido\Testing\Concerns\CommonAsserts;
use Tequilarapido\Testing\Concerns\DatabaseCustomSetup;
use Tequilarapido\Testing\Concerns\Debug;
use Tequilarapido\Testing\Concerns\Steps;
use Tequilarapido\Testing\Support\Tester;

abstract class FeatureTestCaseBase extends TestCase
{
    use MakesHttpRequests,
        InteractsWithSession,
        DatabaseCustomSetup,
        CommonAsserts,
        Steps,
        Debug;

    /**
     * List of seeders classes to after migrating the database.
     *
     * @var array
     */
    public $useSeeders = [];

    /**
     * List of models classes to truncate after each transaction rollback.
     *
     * @var array
     */
    public $truncateModels = [];

    /**
     * Application base url.
     *
     * @var string
     */
    public $baseUrl;

    /**
     * Store the test world / context.
     *
     * Used internally by given() feature.
     *
     * @var array
     */
    public $world = [];

    /**
     * {@inheritdoc}
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->baseUrl = env('APP_URL');

        Tester::instance()->setTestCase($this);

        $this->world = [];

        $this->setupDatabase();
    }

    /**
     * Access world as test properties.
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->world[$name])) {
            return $this->world[$name];
        }
    }
}
