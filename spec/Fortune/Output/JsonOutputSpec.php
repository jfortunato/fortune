<?php

namespace spec\Fortune\Output;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Output\HeaderInterface;


class JsonOutputSpec extends ObjectBehavior
{
    function let(HeaderInterface $header)
    {
        $this->beConstructedWith($header);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Output\JsonOutput');
    }

    function it_can_prepare_some_string_input_into_json_object()
    {
        $this->prepare("Foo")->shouldReturn('{"Foo":""}');
        $this->prepare("Bar")->shouldReturn('{"Bar":""}');
        $this->prepare("1")->shouldReturn('{"1":""}');
    }

    function it_can_prepare_single_level_array_into_json_object_with_keys_and_values()
    {
        $this->prepare(["Foo" => "Bar"])->shouldReturn('{"Foo":"Bar"}');
        $this->prepare(["1" => "Foo"])->shouldReturn('{"1":"Foo"}');
        $this->prepare(["Foo" => "1"])->shouldReturn('{"Foo":"1"}');
    }

    function it_can_prepare_multi_level_array_into_json_array()
    {
        $this->prepare([["Foo" => "Bar"], ["Baz" => "Bing"]])->shouldReturn('[{"Foo":"Bar"},{"Baz":"Bing"}]');
    }

    function it_can_return_empty_array_with_no_input()
    {
        $this->prepare()->shouldReturn('[]');
    }

    function it_sets_a_json_content_type_header_on_prepare(HeaderInterface $header)
    {
        $header->setJsonContentType()->shouldBeCalled();

        $this->prepare();
    }
}
