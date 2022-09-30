<?php

declare(strict_types=1);

namespace App\Contracts\Properties;

interface PropertiesInterface
{
    public function all($start,$perPage);
    public function findOne($data);
    public function store($data);
    public function update($data,$id);
    public function delete($data);
    public function uploadPhoto($data);
    public function search($data);
    public function searchAddresses($data);
}