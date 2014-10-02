<?php

namespace spec\Fortune;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Validator\ResourceValidatorInterface;
use Fortune\Security\SecurityInterface;
use Fortune\Resource\Creator\ResourceCreator;
use Fortune\Configuration\Configuration;
use Fortune\ResourceFactory;
use Fortune\Configuration\ResourceConfiguration;

class ResourceSpec extends ObjectBehavior
{
    function let(ResourceRepositoryInterface $repository, ResourceValidatorInterface $validator, SecurityInterface $security, ResourceFactory $resourceFactory, ResourceConfiguration $config)
    {
        $this->beConstructedWith($repository, $validator, $security, $resourceFactory, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Resource');
    }

    function it_can_get_all_entities($repository)
    {
        $repository->findAll()->shouldBeCalled()->willReturn($entities = array('foo'));

        $this->all()->shouldReturn($entities);
    }

    function it_can_get_single_entity($repository)
    {
        $repository->find(1)->shouldBeCalled()->willReturn($entity = new \StdClass);

        $this->single(1)->shouldReturn($entity);
    }

    function it_can_update_entity($repository)
    {
        $repository->update(1, ['input'])->shouldBeCalled()->willReturn($entity = new \StdClass);

        $this->update(1, ['input'])->shouldReturn($entity);
    }

    function it_can_delete_entity($repository)
    {
        $repository->delete(1)->shouldBeCalled()->willReturn(null);

        $this->delete(1)->shouldReturn(null);
    }

    function it_can_check_security($security, $config)
    {
        $config->getResource()->willReturn('foo');

        $security->isAllowed('index', 'foo', null)->shouldBeCalled()->willReturn(true);
        $this->passesSecurity('index', null)->shouldReturn(true);

        $security->isAllowed('index', 'foo', null)->shouldBeCalled()->willReturn(false);
        $this->passesSecurity('index', null)->shouldReturn(false);
    }

    function it_can_check_validation($validator)
    {
        $validator->validate(['input'], [])->shouldBeCalled()->willReturn(true);
        $this->passesValidation(['input'])->shouldReturn(true);

        $validator->validate(['input'], [])->shouldBeCalled()->willReturn(false);
        $this->passesValidation(['input'])->shouldReturn(false);
    }
}
