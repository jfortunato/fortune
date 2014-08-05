<?php

namespace spec\Fortune\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;
use Fortune\Output\OutputInterface;
use Fortune\Validator\ResourceValidatorInterface;

class ResourceSpec extends ObjectBehavior
{
    function let(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output, ResourceValidatorInterface $validator)
    {
        $this->beConstructedWith($repository, $serializer, $output, $validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Resource\Resource');
    }

    function its_index_method_should_return_json_array_with_all_of_a_resource($repository, $serializer, $output)
    {
        $repository->findAll()->shouldBeCalled()->willReturn($resources = array('foo'));

        $serializer->serialize($resources)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 200)->shouldBeCalled()->willReturn('response');

        $this->index()->shouldReturn('response');
    }

    function its_show_method_should_return_a_single_json_resource_object($repository, $serializer, $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');;

        $serializer->serialize($resource)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 200)->shouldBeCalled()->willReturn('response');

        $this->show(1)->shouldReturn('response');
    }

    function its_create_method_can_create_a_new_resource_and_return_its_json_serialization($repository, $serializer, $output, $validator)
    {
        $validator->validate(['input'])->shouldBeCalled()->willReturn(true);

        $repository->create(['input'])->shouldBeCalled()->willReturn($resource = 'foo');

        $serializer->serialize($resource)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 201)->shouldBeCalled()->willReturn('response');

        $this->create(array('input'))->shouldReturn('response');
    }

    function its_update_method_can_update_existing_resource_and_doesnt_return_content($repository, $output, $validator)
    {
        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');

        $validator->validate(['input'])->shouldBeCalled()->willReturn(true);

        $repository->update(1, ['input'])->shouldBeCalled()->willReturn($resource = 'foo');

        $output->response(null, 204)->shouldBeCalled()->willReturn('response');;

        $this->update(1, ['input'])->shouldReturn('response');
    }

    function its_delete_method_can_delete_resource_and_doesnt_return_content($repository, $output)
    {
        $repository->delete(1)->shouldBeCalled();

        $output->response(null, 204)->shouldBeCalled()->willReturn('response');

        $this->delete(1);
    }

    function it_should_throw_404_when_resource_not_found($repository, $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn(null);

        $output->response(null, 404)->shouldBeCalled()->willReturn('response');

        $this->show(1);
    }

    function it_should_throw_404_when_updating_resource_that_doesnt_exist($repository, $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn(null);

        $output->response(null, 404)->shouldBeCalled()->willReturn('response');

        $this->update(1, ['input']);
    }

    function its_create_method_should_throw_400_with_bad_input($validator, $output)
    {
        $validator->validate(['input'])->shouldBeCalled()->willReturn(false);

        $output->response(null, 400)->shouldBeCalled()->willReturn('response');

        $this->create(['input']);
    }

    function its_update_method_should_throw_400_with_bad_input($repository, $validator, $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');

        $validator->validate(['input'])->shouldBeCalled()->willReturn(false);

        $output->response(null, 400)->shouldBeCalled()->willReturn('response');

        $this->update(1, ['input']);
    }
}
