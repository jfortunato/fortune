<?php

namespace spec\Fortune\Output\Driver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Serializer\SerializerInterface;
use Fortune\Resource\ResourceInterface;

class SimpleOutputSpec extends ObjectBehavior
{
    function let(SerializerInterface $serializer, ResourceInterface $resource)
    {
        $this->beConstructedWith($serializer, $resource);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Output\Driver\SimpleOutput');
    }

    function it_should_return_all_of_serialized_resource($serializer, $resource)
    {
        $resource->passesSecurity(Argument::any())->willReturn(true);

        $resource->all()->shouldBeCalled()->willReturn($entities = array('foo'));

        $serializer->serialize($entities)->shouldBeCalled()->willReturn('response');

        $this->index()->shouldReturn('response');
    }

    function it_should_return_a_single_serialized_resource($serializer, $resource)
    {
        $resource->single(1)->shouldBeCalled()->willReturn($entity = new \StdClass);

        $resource->passesSecurity(Argument::any())->willReturn(true);

        $serializer->serialize($entity)->shouldBeCalled()->willReturn('response');

        $this->show(1)->shouldReturn('response');
    }

    function it_should_return_newly_created_resource($serializer, $resource)
    {
        $resource->passesSecurity(Argument::any())->willReturn(true);

        $resource->passesValidation(['input'])->shouldBeCalled()->willReturn(true);

        $resource->create(['input'])->shouldBeCalled()->willReturn($entity = new \StdClass);

        $serializer->serialize($entity)->shouldBeCalled()->willReturn('response');

        $this->create(['input'])->shouldReturn('response');
    }

    function it_shouldnt_return_content_when_updating_resource($serializer, $resource)
    {
        $resource->single(1)->shouldBeCalled()->willReturn($entity = new \StdClass);

        $resource->passesSecurity(Argument::any())->willReturn(true);

        $resource->passesValidation(['input'])->shouldBeCalled()->willReturn(true);

        $resource->update(1, ['input'])->shouldBeCalled()->willReturn('response');

        $serializer->serialize(null)->shouldBeCalled()->willReturn('empty response');

        $this->update(1, ['input'])->shouldReturn('empty response');
    }

    function it_shouldnt_return_content_when_deleting_resource($serializer, $resource)
    {
        $resource->single(1)->shouldBeCalled()->willReturn($entity = new \StdClass);

        $resource->passesSecurity(Argument::any())->willReturn(true);

        $resource->delete(1)->shouldBeCalled();

        $serializer->serialize(null)->shouldBeCalled()->willReturn('empty response');

        $this->delete(1)->shouldReturn('empty response');
    }

    function it_should_throw_404_when_resource_not_found($serializer, $resource)
    {
        $resource->single(1)->willReturn(null);

        $resource->passesSecurity(Argument::any())->willReturn(true);

        $serializer->serialize(['error' => 'Resource Not Found'])->shouldBeCalled()->willReturn('error serialization');


        $this->show(1)->shouldReturn('error serialization');
        $this->update(1, ['input'])->shouldReturn('error serialization');
        $this->delete(1)->shouldReturn('error serialization');

        $this->getStatusCode()->shouldReturn(404);
    }

    function it_should_throw_400_with_bad_input($serializer, $resource)
    {
        $resource->single(1)->willReturn(new \StdClass);

        $resource->passesSecurity(Argument::any())->willReturn(true);

        $resource->passesValidation(['input'])->willReturn(false);

        $serializer->serialize(['error' => 'Bad Input'])->shouldBeCalled()->willReturn('error serialization');

        $this->create(['input'])->shouldReturn('error serialization');
        $this->update(1, ['input'])->shouldReturn('error serialization');

        $this->getStatusCode()->shouldReturn(400);
    }

    function it_should_throw_403_when_resource_has_security($serializer, $resource)
    {
        $resource->single(1)->willReturn(new \StdClass);

        $resource->passesSecurity(Argument::any())->shouldBeCalled()->willReturn(false);

        $serializer->serialize(['error' => 'Access Denied'])->shouldBeCalled()->willReturn('error serialization');

        $this->index()->shouldReturn('error serialization');
        $this->show(1)->shouldReturn('error serialization');
        $this->create(['input'])->shouldReturn('error serialization');
        $this->update(1, ['input'])->shouldReturn('error serialization');
        $this->delete(1)->shouldReturn('error serialization');

        $this->getStatusCode()->shouldReturn(403);
    }
}
