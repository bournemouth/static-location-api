<?php

namespace BournemouthData\LocationApi;

use Silex\Application;

class LocationApiResource
{
	private $uri;
	private $name;
	private $description;

	public function __construct($uri, $name, $description = null)
	{
		$this->uri = $uri;
		$this->name = $name;
		$this->description = $description;
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
