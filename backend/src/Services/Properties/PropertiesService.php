<?php
declare(strict_types=1);

namespace App\Services\Properties;

use App\Config\Connection;
use App\Contracts\Properties\PropertiesInterface;

class PropertiesService implements PropertiesInterface
{
    protected Connection $con;

    private string $table = 'properties';

    public function __construct(
        Connection $connection
    )
    {
        $this->con = $connection;
    }

    public function all($start,$perPage)
    {
        $query = "select $this->table.*, property_types.title as property_type, property_types.description as property_description
        from $this->table inner join property_types on $this->table.property_type_id=property_types.id
        limit :off,:lim";

        $st = $this->con->connect()->prepare($query);
        $st->bindValue(':off', $start, \PDO::PARAM_INT);
        $st->bindValue(':lim', $perPage, \PDO::PARAM_INT);
        try{
            $st->execute();
            $info = $st->fetchAll(\PDO::FETCH_ASSOC);
            return json_encode($info);;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function getTotalPages(){
        $query = "select count(*) as total from $this->table";
        return $this->con->connect()->query($query)->fetch();

    }

    public function searchAddresses($data) {
        $pattern = '%' . $data . '%';
        $query = "select address from $this->table where address like :pattern";
        $st = $this->con->connect()->prepare($query);
        $st->execute([':pattern' => $pattern]);
        $info = $st->fetchAll(\PDO::FETCH_NUM);
        $ar = [];
        foreach ($info as $key => $value){
            array_push($ar,$value[0]);
        }
        return ($ar);
    }

    public function findOne($data)
    {
        $query = "select * from $this->table where uuid = ?";
        $st = $this->con->connect()->prepare($query);
        try{
            $st->execute([$data]);
            return json_encode($st->fetch());
        }catch (\Exception $exception){
            return $exception->getMessage();
        }

    }

    public function storeMany($data)
    {
        $columnsArray = [
            'uuid', 'property_type_id','county','country', 'town',
            'description', 'address','image_full','image_thumbnail', 'latitude',
            'longitude', 'num_bedrooms','num_bathrooms','price', 'type',
            'created_at', 'updated_at'
        ];
        $duplicateCols = [
            'property_type_id','county','country', 'town',
            'description', 'address','image_full','image_thumbnail', 'latitude',
            'longitude', 'num_bedrooms','num_bathrooms','price', 'type',
            'created_at', 'updated_at'
        ];

        $columns = implode(', ', $columnsArray);
        $duplicateCols  = array_map(function ($value){
            return $value."=values(".$value.")";
        }, $duplicateCols);
        $duplicateStr = implode(', ',$duplicateCols);

        $qr = "INSERT INTO $this->table ($columns) VALUES ";
        $ln = sizeof($data);

        $values = array_fill(0,$ln, "(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $places = implode(', ', $values);
        $qr .= $places;
        $qr .= " ON DUPLICATE KEY UPDATE ". $duplicateStr;

        $st = $this->con->connect()->prepare($qr);

        $ar = [];

        foreach ($data as $info) {
            $ar= array_merge($ar, [
                $info['uuid'], $info['property_type_id'],
                $info['county'], $info['country'],
                $info['town'], $info['description'],
                $info['address'], $info['image_full'],
                $info['image_thumbnail'], $info['latitude'],
                $info['longitude'], $info['num_bedrooms'],
                $info['num_bathrooms'], $info['price'],
                $info['type'],
                $info['created_at'], $info['updated_at']
            ]);
        }
        try{
            $st->execute($ar);
            return $st->rowCount();
        }catch (\Exception $exception){
            return $exception->getMessage();
        }

        return true;
    }

    public function store($data)
    {

        $query = "insert into $this->table (
                   uuid,property_type_id, county,country,
                   town, description,address,
                   image_full, image_thumbnail,latitude,
                   longitude, num_bedrooms,num_bathrooms,
                   price, type
                   ) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $st = $this->con->connect()->prepare($query);
        try {
            $st->execute($data);
            if($st->rowCount() > 0) {
                return ['message' => true];
            }
        }catch (\Exception $e){
            return  ['message' => "Error while processing request.". $e->getMessage()];
        }

    }

    public function update($info, $id)
    {
        $query = "update $this->table SET 
                 property_type_id=?, county=? ,country=?,
                 town=?, description=? ,address=?,
                 image_full=?, image_thumbnail=? ,latitude=?,
                 longitude=?, num_bedrooms=? ,num_bathrooms=?,
                 price=?, type=? 
             where uuid = ?";
        $st = $this->con->connect()->prepare($query);
        try {
            $st->execute(
                [
                    $info['property_type_id'],
                    $info['county'], $info['country'],
                    $info['town'], $info['description'],
                    $info['address'], $info['image_full'],
                    $info['image_thumbnail'], $info['latitude'],
                    $info['longitude'], $info['num_bedrooms'],
                    $info['num_bathrooms'], $info['price'],
                    $info['type'],
                    $id
                ]);
                return  ['message' => 3];
        }catch (\Exception $e){
            return  ['message' => "Error while processing request.". $e->getMessage()];
        }
    }

    public function delete($data)
    {
        $query = "delete from $this->table where uuid = ?";
        $st = $this->con->connect()->prepare($query);
        $st->execute([$data]);
        if ($st->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function uploadPhoto($data) {

    }

    public function search($data)
    {
        if (is_array($data)) {
            $i = 1;
            $query = "select uuid,county, description, image_thumbnail,type from $this->table where ";
            $bindings = [];
            $c = 0;
            foreach ($data as $k => &$v) {

                if (!empty($v)) {
                    $bindings = array_merge_recursive($bindings, [":" . $k => "%$v%"]);
                    if ($i > 1) {
                        $query .= " and ";
                    }
                    $query .= " $k like :$k ";
                    $i++;
                    $c += 1;
                }
            }
            if ($c >0) {
                $st = $this->con->connect()->prepare($query);

                foreach ($bindings as $binding => &$val) {
                    $st->bindParam($binding, $val, \PDO::PARAM_STR);
                }
                $st->execute();
                return $st->fetchAll();
            }
            return [];
        }
    }

    private function allowedColumns(){
        return [
            'properties.county', 'properties.country', 'properties.town' , 'properties.description' ,
            'properties.address','properties.latitude', 'properties.longitude',
             'properties.num_bedrooms' , 'properties.num_bathrooms', 'properties.price',
            'properties.type', 'property_types.title', 'property_types.description'
        ];
    }


    public function searchHomePage($data)
    {
        $query = "select $this->table.*, property_types.title as property_type, property_types.description as property_description
        from $this->table inner join property_types on $this->table.property_type_id=property_types.id where";
        $allowedCols = $this->allowedColumns();
        $bindings = [];
        $i = 1;
        foreach ($allowedCols as &$allowedCol) {
            $bindings = array_merge_recursive($bindings, [":a$i" => "%$data%"]);
            if ($i > 1) {
                $query .= " or ";
            }
            $query .= " $allowedCol like :a$i ";
            $i++;
        }
        $st = $this->con->connect()->prepare($query);
        foreach ($bindings as $binding => &$val) {
            $st->bindParam($binding, $val, \PDO::PARAM_STR);
        }
        $st->execute();
        $info = $st->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($info);;
    }

    public function getLocations()
    {
        $query = "select longitude,latitude, county,address from properties";
        $st = $this->con->connect()->prepare($query);
        $st->execute();
        return json_encode($st->fetchAll(\PDO::FETCH_ASSOC));
    }

}