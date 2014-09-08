<?php

return array(
    'dogs'      => array(
        'entity'             => 'Fortune\Test\Entity\Dog',
        'validator'          => 'Fortune\Test\Validator\DogValidator',
        'parent'             => '',
        'access_control'     => array(
            'authentication'    => $requiresAuthentication,
            'role'              => $requiresRole,
            'owner'             => $requiresOwner,
        ),
    ),
    'puppies'   => array(
        'entity'             => 'Fortune\Test\Entity\Puppy',
        'validator'          => 'Fortune\Test\Validator\PuppyValidator',
        'parent'             => 'dogs',
    ),
);
