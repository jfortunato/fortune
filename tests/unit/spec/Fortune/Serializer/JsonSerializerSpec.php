<?php

namespace spec\Fortune\Serializer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Configuration\ResourceConfiguration;

class JsonSerializerSpec extends ObjectBehavior
{
    function let(ResourceConfiguration $config)
    {
        $this->beConstructedWith($config);

        $config->getExcludedProperties()->willReturn([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Serializer\JsonSerializer');
    }

    function it_should_serialize_a_single_resource()
    {

        $data = ['id' => 1, 'name' => 'foo'];

        $this->serialize($data)->shouldReturn('{"id":1,"name":"foo"}');
    }

    function it_should_serialize_multiple_resources()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $serialized = '[{"id":1,"name":"foo"},{"id":2,"name":"bar"}]';
        $this->serialize($data)->shouldReturn($serialized);
    }

    function it_should_serialize_a_single_resource_with_excluded_properties($config)
    {
        $config->getExcludedProperties()->willReturn(['name']);

        $data = ['id' => 1, 'name' => 'foo'];

        $this->serialize($data)->shouldReturn('{"id":1}');
    }

    function it_should_serialize_multiple_resources_with_excluded_properties($config)
    {
        $config->getExcludedProperties()->willReturn(['name']);

        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $serialized = '[{"id":1},{"id":2}]';
        $this->serialize($data)->shouldReturn($serialized);
    }
}
