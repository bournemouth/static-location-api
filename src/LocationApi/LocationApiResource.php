<?php

namespace BournemouthData\LocationApi;

use Silex\Application;

class LocationApiResource
{
    private $uri;
    private $name;
    private $description;
    private $id;

    public function __construct($id, $uri, $name, $description = null)
    {
        $this->id = $id;
        $this->uri = $uri;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
