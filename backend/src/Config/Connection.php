<?php
declare(strict_types=1);

namespace App\Config;

use PDO;

class Connection
{

    public function construct()
    {
    }

    public function connect()
    {
            try {
                $con = new PDO("{$_ENV['db']}:host={$_ENV['host']}; dbname={$_ENV['db_name']}",
                    $_ENV['username'], $_ENV['password']
                );
                $con->setAttribute(PDO::FETCH_ASSOC, PDO::ATTR_DEFAULT_FETCH_MODE);
            }catch (PDOException $exception) {
                die ("Connection error: " . $exception->getMessage());
            }
            return $con;
    }
}