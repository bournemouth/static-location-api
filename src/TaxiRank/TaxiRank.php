<?php

namespace BournemouthData\TaxiRank;

class TaxiRank
{
	private $id;
	private $name;
	private $description;
	private $lat;
	private $lng;

	public function __construct($id, $name, $description, $lat, $lng)
    {
        $this->id = $id;
        $this->name = ucwords(strtolower($name));
        $this->description = $description;
        $this->lat = (double)$lat;
        $this->lng = (double)$lng;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function getName()
    {
        return $this->name;
    }
}
