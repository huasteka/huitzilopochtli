<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{

    use Laravel\Lumen\Testing\DatabaseMigrations;
    use Laravel\Lumen\Testing\DatabaseTransactions;

    /**
     * Creates the application.
     *
     * @return Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function convertObjectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

}
