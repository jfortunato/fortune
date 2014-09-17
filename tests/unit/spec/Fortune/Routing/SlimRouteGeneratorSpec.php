<?php

namespace spec\Fortune\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slim\Slim;
use Fortune\Output\SlimOutput;
use Fortune\Configuration\Configuration;
use Fortune\Configuration\ResourceConfiguration;

class SlimRouteGeneratorSpec extends ObjectBehavior
{
    function let(Slim $slim, SlimOutput $output, Configuration $configuration)
    {
        $this->beConstructedWith($slim, $output, $configuration);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Routing\SlimRouteGenerator');
    }

    function it_should_generate_basic_routes($slim, $output, $configuration, ResourceConfiguration $config1)
    {
        $configuration->getResourceConfigurations()->shouldBeCalled()->willReturn(array($config1));

        $config1->getResource()->shouldBeCalled()->willReturn('foos');
        $config1->getParent()->willReturn(null);

        $slim->get('/foos', $this->mockOutput($output, 'index'))->shouldBeCalledTimes(1);
        $slim->get('/foos/:id', $this->mockOutput($output, 'show', [1]))->shouldBeCalledTimes(1);
        $slim->post('/foos', $this->mockOutput($output, 'create'))->shouldBeCalledTimes(1);
        $slim->put('/foos/:id', $this->mockOutput($output, 'update', [1]))->shouldBeCalledTimes(1);
        $slim->delete('/foos/:id', $this->mockOutput($output, 'delete', [1]))->shouldBeCalledTimes(1);

        $this->generateRoutes();
    }

    function it_should_generate_multiple_basic_routes($slim, $output, $configuration, ResourceConfiguration $config1, ResourceConfiguration $config2)
    {
        $configuration->getResourceConfigurations()->shouldBeCalled()->willReturn(array($config1, $config2));

        $config1->getResource()->shouldBeCalled()->willReturn('foos');
        $config1->getParent()->willReturn(null);
        $config2->getResource()->shouldBeCalled()->willReturn('bars');
        $config2->getParent()->willReturn(null);

        $slim->get('/foos', $this->mockOutput($output, 'index'))->shouldBeCalledTimes(1);
        $slim->get('/foos/:id', $this->mockOutput($output, 'show', [1]))->shouldBeCalledTimes(1);
        $slim->post('/foos', $this->mockOutput($output, 'create'))->shouldBeCalledTimes(1);
        $slim->put('/foos/:id', $this->mockOutput($output, 'update', [1]))->shouldBeCalledTimes(1);
        $slim->delete('/foos/:id', $this->mockOutput($output, 'delete', [1]))->shouldBeCalledTimes(1);

        $slim->get('/bars', $this->mockOutput($output, 'index'))->shouldBeCalledTimes(1);
        $slim->get('/bars/:id', $this->mockOutput($output, 'show', [1]))->shouldBeCalledTimes(1);
        $slim->post('/bars', $this->mockOutput($output, 'create'))->shouldBeCalledTimes(1);
        $slim->put('/bars/:id', $this->mockOutput($output, 'update', [1]))->shouldBeCalledTimes(1);
        $slim->delete('/bars/:id', $this->mockOutput($output, 'delete', [1]))->shouldBeCalledTimes(1);

        $this->generateRoutes();
    }

    function it_should_generate_route_with_parents($slim, $output, $configuration, ResourceConfiguration $config1, ResourceConfiguration $config2)
    {
        $configuration->getResourceConfigurations()->shouldBeCalled()->willReturn(array($config1));

        $config1->getResource()->shouldBeCalled()->willReturn('bars');
        $config1->getParent()->shouldBeCalled()->willReturn('foos');
        $configuration->resourceConfigurationFor('foos')->willReturn($config2);
        $config2->getParent()->willReturn(null);

        $slim->get('/foos/:foos_id/bars', $this->mockOutput($output, 'index', [1]))->shouldBeCalledTimes(1);
        $slim->get('/foos/:foos_id/bars/:id', $this->mockOutput($output, 'show', [1, 1]))->shouldBeCalledTimes(1);
        $slim->post('/foos/:foos_id/bars', $this->mockOutput($output, 'create', [1]))->shouldBeCalledTimes(1);
        $slim->put('/foos/:foos_id/bars/:id', $this->mockOutput($output, 'update', [1, 1]))->shouldBeCalledTimes(1);
        $slim->delete('/foos/:foos_id/bars/:id', $this->mockOutput($output, 'delete', [1, 1]))->shouldBeCalledTimes(1);

        $this->generateRoutes();
    }

    function it_should_generate_route_with_multi_level_parents($slim, $output, $configuration, ResourceConfiguration $config1, ResourceConfiguration $config2, ResourceConfiguration $config3)
    {
        $configuration->getResourceConfigurations()->shouldBeCalled()->willReturn(array($config1));

        $config1->getResource()->shouldBeCalled()->willReturn('boos');
        $config1->getParent()->shouldBeCalled()->willReturn('bars');
        $configuration->resourceConfigurationFor('bars')->willReturn($config2);
        $config2->getParent()->willReturn('foos');
        $configuration->resourceConfigurationFor('foos')->willReturn($config3);
        $config3->getParent()->willReturn(null);

        $slim->get('/foos/:foos_id/bars/:bars_id/boos', $this->mockOutput($output, 'index', [1, 1]))->shouldBeCalledTimes(1);
        $slim->get('/foos/:foos_id/bars/:bars_id/boos/:id', $this->mockOutput($output, 'show', [1, 1, 1]))->shouldBeCalledTimes(1);
        $slim->post('/foos/:foos_id/bars/:bars_id/boos', $this->mockOutput($output, 'create', [1, 1]))->shouldBeCalledTimes(1);
        $slim->put('/foos/:foos_id/bars/:bars_id/boos/:id', $this->mockOutput($output, 'update', [1, 1, 1]))->shouldBeCalledTimes(1);
        $slim->delete('/foos/:foos_id/bars/:bars_id/boos/:id', $this->mockOutput($output, 'delete', [1, 1, 1]))->shouldBeCalledTimes(1);

        $this->generateRoutes();
    }

    protected function mockOutput($output, $method, array $args = array())
    {
        return Argument::that(function ($closure) use ($output, $method, $args) {
            $output->$method()->shouldBeCalled()->withArguments($args);

            call_user_func_array($closure, $args);
            
            return true;
        });
    }
}
