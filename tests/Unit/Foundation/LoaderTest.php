<?php

namespace Yard\Tests\Unit\Foundation;

use WP_Mock;
use Yard\Foundation\Loader;
use Yard\Tests\Unit\TestCase;

class LoaderTest extends TestCase
{
    public function setUp()
    {
        WP_Mock::setUp();
    }

    public function tearDown()
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_adds_actions_to_the_loader_actions()
    {
        $loader = Loader::getInstance();

        $loader->addAction('test-hook', $this, 'test', 10, 1);

        $this->assertClassHasAttribute('actions', Loader::class);
        $this->assertAttributeCount(1, 'actions', $loader);

        $loader->addAction('test-hook-2', $this, 'test', 10, 1);
        $loader->addAction('test-hook-3', $this, 'test', 10, 1);
        $this->assertAttributeCount(3, 'actions', $loader);
    }

    /** @test */
    public function it_adds_filters_to_the_loader_actions()
    {
        $loader = Loader::getInstance();

        $loader->addFilter('test-hook', $this, 'test', 10, 1);

        $this->assertClassHasAttribute('filters', Loader::class);
        $this->assertAttributeCount(1, 'filters', $loader);

        $loader->addFilter('test-hook-2', $this, 'test', 10, 1);
        $loader->addFilter('test-hook-3', $this, 'test', 10, 1);
        $this->assertAttributeCount(3, 'filters', $loader);
    }

    /** @test */
    public function it_registers_the_hooks_correctly()
    {
        $loader = Loader::getInstance();

        $loader->addAction('test-action', $this, 'test', 10, 1);
        $loader->addFilter('test-filter', $this, 'test', 10, 1);

        WP_Mock::expectActionAdded('test-action', [$this, 'test'], 10, 1);
        WP_Mock::expectFilterAdded('test-filter', [$this, 'test'], 10, 1);

        $loader->register();

        $this->assertTrue(true);
    }
}
