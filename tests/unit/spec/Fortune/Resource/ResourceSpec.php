<?php

namespace spec\Fortune\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;
use Fortune\Output\OutputInterface;

class ResourceSpec extends ObjectBehavior
{
    function let(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output)
    {
        $this->beConstructedWith($repository, $serializer, $output);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Resource\Resource');
    }

    function its_index_method_should_return_json_array_with_all_of_a_resource(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output)
    {
        $repository->findAll()->shouldBeCalled()->willReturn($resources = array('foo'));

        $serializer->serialize($resources)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 200)->shouldBeCalled()->willReturn('response');

        $this->index()->shouldReturn('response');
    }

    function its_show_method_should_return_a_single_json_resource_object(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');;

        $serializer->serialize($resource)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 200)->shouldBeCalled()->willReturn('response');

        $this->show(1)->shouldReturn('response');
    }

    function its_create_method_can_create_a_new_resource_and_return_its_json_serialization(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output)
    {
        $repository->create(['input'])->shouldBeCalled()->willReturn($resource = 'foo');

        $serializer->serialize($resource)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 201)->shouldBeCalled()->willReturn('response');

        $this->create(array('input'))->shouldReturn('response');
    }
}
