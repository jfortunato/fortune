<?php

namespace spec\Fortune\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;
use Fortune\Output\OutputInterface;
use Fortune\Validator\ResourceValidatorInterface;
use Fortune\Security\SecurityInterface;

class ResourceSpec extends ObjectBehavior
{
    function let(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output, ResourceValidatorInterface $validator, SecurityInterface $security)
    {
        $this->beConstructedWith($repository, $serializer, $output, $validator, $security);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Resource\Resource');
    }

    function its_index_method_should_return_json_array_with_all_of_a_resource($repository, $serializer, $output, $security)
    {
        $repository->getClassName()->willReturn('Foo\Bar');

        $security->isAllowed('Foo\Bar')->willReturn(true);

        $repository->findAll()->shouldBeCalled()->willReturn($resources = array('foo'));

        $serializer->serialize($resources)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 200)->shouldBeCalled()->willReturn('response');

        $this->index()->shouldReturn('response');
    }

    function its_show_method_should_return_a_single_json_resource_object($repository, $serializer, $output, $security)
    {
        $repository->getClassName()->willReturn('Foo\Bar');

        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');;

        $security->isAllowed('foo')->willReturn(true);

        $serializer->serialize($resource)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 200)->shouldBeCalled()->willReturn('response');

        $this->show(1)->shouldReturn('response');
    }

    function its_create_method_can_create_a_new_resource_and_return_its_json_serialization($repository, $serializer, $output, $validator, $security)
    {
        $repository->getClassName()->willReturn('Foo\Bar');

        $security->isAllowed('Foo\Bar')->willReturn(true);

        $validator->validate(['input'])->shouldBeCalled()->willReturn(true);

        $repository->create(['input'])->shouldBeCalled()->willReturn($resource = 'foo');

        $serializer->serialize($resource)->shouldBeCalled()->willReturn('serialized');

        $output->response('serialized', 201)->shouldBeCalled()->willReturn('response');

        $this->create(array('input'))->shouldReturn('response');
    }

    function its_update_method_can_update_existing_resource_and_doesnt_return_content($repository, $output, $validator, $security)
    {
        $repository->getClassName()->willReturn('Foo\Bar');

        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');

        $security->isAllowed('foo')->willReturn(true);

        $validator->validate(['input'])->shouldBeCalled()->willReturn(true);

        $repository->update(1, ['input'])->shouldBeCalled()->willReturn($resource = 'foo');

        $output->response(null, 204)->shouldBeCalled()->willReturn('response');;

        $this->update(1, ['input'])->shouldReturn('response');
    }

    function its_delete_method_can_delete_resource_and_doesnt_return_content($repository, $output, $security)
    {
        $repository->find(1)->willReturn('foo');

        $security->isAllowed('foo')->willReturn(true);

        $repository->delete(1)->shouldBeCalled();

        $output->response(null, 204)->shouldBeCalled()->willReturn('response');

        $this->delete(1);
    }

    function it_should_throw_404_when_resource_not_found($repository, $output, $security)
    {
        $repository->getClassName()->willReturn('Foo\Bar');

        $security->isAllowed('Foo\Bar')->willReturn(true);

        $repository->find(1)->shouldBeCalled()->willReturn(null);

        $output->response(null, 404)->shouldBeCalled()->willReturn('response');

        $this->show(1);
    }

    function it_should_throw_404_when_updating_resource_that_doesnt_exist($repository, $output, $security)
    {
        $repository->getClassName()->willReturn('Foo\Bar');

        $security->isAllowed('Foo\Bar')->willReturn(true);

        $repository->find(1)->shouldBeCalled()->willReturn(null);

        $output->response(null, 404)->shouldBeCalled()->willReturn('response');

        $this->update(1, ['input']);
    }

    function its_create_method_should_throw_400_with_bad_input($repository, $validator, $output, $security)
    {
        $repository->getClassName()->willReturn('Foo\Bar');

        $security->isAllowed('Foo\Bar')->willReturn(true);

        $validator->validate(['input'])->shouldBeCalled()->willReturn(false);

        $output->response(null, 400)->shouldBeCalled()->willReturn('response');

        $this->create(['input']);
    }

    function its_update_method_should_throw_400_with_bad_input($repository, $validator, $output, $security)
    {
        $security->isAllowed('foo')->willReturn(true);

        $repository->find(1)->shouldBeCalled()->willReturn($resource = 'foo');

        $validator->validate(['input'])->shouldBeCalled()->willReturn(false);

        $output->response(null, 400)->shouldBeCalled()->willReturn('response');

        $this->update(1, ['input']);
    }

    function its_index_method_should_deny_access_to_resource_with_security($repository, $security, $output)
    {
        $repository->getClassName()->shouldBeCalled()->willReturn('Foo\Bar');

        $security->isAllowed('Foo\Bar')->shouldBeCalled()->willReturn(false);

        $output->response(null, 403)->shouldBeCalled()->willReturn('response');

        $this->index();
    }

    function its_show_method_should_deny_access_to_resource_with_security($repository, $security, $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn('foo');

        $security->isAllowed('foo')->shouldBeCalled()->willReturn(false);

        $output->response(null, 403)->shouldBeCalled()->willReturn('response');

        $this->show(1);
    }

    function its_create_method_should_deny_access_to_resource_with_security($repository, $security, $output)
    {
        $repository->getClassName()->shouldBeCalled()->willReturn('Foo\Bar');

        $security->isAllowed('Foo\Bar')->shouldBeCalled()->willReturn(false);

        $output->response(null, 403)->shouldBeCalled()->willReturn('response');

        $this->create([]);
    }

    function its_update_method_should_deny_access_to_resource_with_security($repository, $security, $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn('foo');

        $security->isAllowed('foo')->shouldBeCalled()->willReturn(false);

        $output->response(null, 403)->shouldBeCalled()->willReturn('response');

        $this->update(1, []);
    }

    function its_delete_method_should_deny_access_to_resource_with_security($repository, $security, $output)
    {
        $repository->find(1)->shouldBeCalled()->willReturn('foo');

        $security->isAllowed('foo')->shouldBeCalled()->willReturn(false);

        $output->response(null, 403)->shouldBeCalled()->willReturn('response');

        $this->delete(1);
    }
}
