<?php

namespace BournemouthData;

interface TransportRepositoryInterface
{
    public function fetchAll();

    public function fetchById($id);
}