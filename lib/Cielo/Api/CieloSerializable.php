<?php

interface Cielo_Api_CieloSerializable extends \JsonSerializable
{
    public function populate(\stdClass $data);
}