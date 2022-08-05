<?php

namespace gframe\phpmvc;

use PDO;

class Migrations extends Database
{
    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $appliedMigrations = $this->appliedMigrations();
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $toApplyMigration) {
            if ($toApplyMigration === '.' || $toApplyMigration === '..') {
                continue;
            }
            require_once(Application::$ROOT_DIR . "/migrations/$toApplyMigration");
            $fileName = pathinfo($toApplyMigration,PATHINFO_FILENAME);
            $classNameArray = explode('_', substr($fileName, 18));
            $classNameArray = array_map(fn($name) => ucfirst($name), $classNameArray);
            $className = implode('', $classNameArray);
            $instance = new $className();
            $this->log("Applying $toApplyMigration");
            $instance->up();
            $this->log("Applied $toApplyMigration");
            $toSaveMigrations[] = $toApplyMigration;
        }
        if(empty($toSaveMigrations)){
            $this->log("All migrations applied");
        }else{
            $this->saveMigrations($toSaveMigrations);
        }
    }

    public function log($message)
    {
        echo "[".date("Y-m-d h:i:s")."] ".$message.PHP_EOL;
    }
    public function appliedMigrations()
    {
        $SQL = "SELECT migration FROM migrations";
        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function saveMigrations($toSaveMigrations)
    {
        $toSaveMigrations = array_map(fn($toSaveMigration) => "('".$toSaveMigration."')",$toSaveMigrations);
        $subSQL = implode(",",$toSaveMigrations);
        Application::$app->database->pdo->exec("INSERT INTO migrations(migration) VALUES $subSQL");
    }

    private function createMigrationsTable()
    {
        Application::$app->database->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
           id INT AUTO_INCREMENT PRIMARY KEY,
           migration VARCHAR(255) NOT NULL,
           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )ENGINE=INNODB;  ");
    }

}