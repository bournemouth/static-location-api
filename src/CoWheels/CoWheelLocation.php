<?php

namespace BournemouthData\CoWheels;

class CoWheelLocation
{
    private $id;
    private $name;
    private $lat;
    private $lng;

    public function __construct($id, $name, $lat, $lng)
    {
        $this->id = $id;
        $this->name = $this->normalizeName($name);
        $this->lat = (double)$lat;
        $this->lng = (double)$lng;
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

    /**
     * @param string $name
     * @return string
     */
    private function normalizeName($name)
    {
        return str_replace('Bournemouth - ', '', $name);
    }
}
