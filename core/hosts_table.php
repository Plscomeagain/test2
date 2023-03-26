<?php

class HostsTable extends Table
{

    public static function getItems()
    {
        $pdo = dbPdo::getPdo();
        $sql = "SELECT id,hostname FROM `hosts`";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRuTable(): string
    {
        return 'Ведущие';
    }

    public static function getItemById($id)
    {
        $pdo = dbPdo::getPdo();
        $sql = "SELECT id,hostname FROM `hosts`
				WHERE hosts.id = '$id'
		";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}