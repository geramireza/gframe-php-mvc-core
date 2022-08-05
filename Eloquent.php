<?php

namespace gframe\phpmvc;

use App\Models\Model;

abstract class Eloquent
{

    abstract public static function table():string;
    abstract public function attributes():array;

    public function save()
    {
        $table = $this->table();
        $attributes = $this->attributes();
        $params = array_map(fn($attribute) => ":$attribute",$attributes);
        $stmt = self::prepare("INSERT INTO $table (".implode(',',$attributes).")
                VALUES (".implode(',',$params).");    
        ");
        foreach ($attributes as $attribute){
            $stmt->bindValue(":$attribute",$this->$attribute);
        }
        $stmt->execute();
        return true;

    }
    public static function prepare($sql){
        return Application::$app->database->pdo->prepare($sql);

    }

    public static function first($where)
    {
        $table = static::table();
        $attributes = array_keys($where);
        $subSQL = implode(" AND ",array_map(fn($attr) => "$attr = :$attr" ,$attributes));
        $sql = "SELECT * FROM $table WHERE $subSQL";
        $stmt = self::prepare($sql);
        foreach ($where as $key => $value){
            $stmt->bindValue(":$key",$value);
        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }
}