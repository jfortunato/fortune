<?php

namespace Fortune\Serializer;

use Fortune\Configuration\ResourceConfiguration;

class JsonSerializer implements SerializerInterface
{
    protected $config;

    public function __construct(ResourceConfiguration $config)
    {
        $this->config = $config;
    }

    public function serialize(array $data = null)
    {
        // remove any excluded data
        $this->removeExcluded($data);

        return json_encode($data);
    }

    protected function removeExcluded(array &$resource = null)
    {
        if ($resource) {
            foreach ($resource as $key => &$value) {
                if (is_array($value)) {
                    $this->removeExcluded($value);
                } else {
                    if (in_array($key, $this->config->getExcludedProperties())) {
                        unset($resource[$key]);
                    }
                }
            }
        }
    }
}
