<?php

namespace Fortune\Resource;

interface ResourceInterface
{
    public function all();
    public function single($id);
    public function create(array $input);
    public function update($id, array $input);
    public function delete($id);
    public function passesSecurity($entity = null);
    public function passesValidation(array $input);
}
