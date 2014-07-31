<?php

namespace spec\Fortune\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;

class ResourceSpec extends ObjectBehavior
{
    function let(ResourceRepositoryInterface $repository, SerializerInterface $serializer)
    {
        $this->beConstructedWith($repository, $serializer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Resource\Resource');
    }

    function its_index_method_should_return_json_array_with_all_of_a_resource(ResourceRepositoryInterface $repository, SerializerInterface $serializer)
    {
        $repository->findAll()->shouldBeCalled()->willReturn($resources = array('foo'));

        $serializer->serialize($resources)->shouldBeCalled()->willReturn('foo');

        $this->index()->shouldReturn('foo');
    }

    function its_show_method_should_return_a_single_json_resource_object(ResourceRepositoryInterface $repository, SerializerInterface $serializer)
    {
        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');;

        $serializer->serialize($resource)->shouldBeCalled()->willReturn('foo');

        $this->show(1)->shouldReturn('foo');
    }
}
