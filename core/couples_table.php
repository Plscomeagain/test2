<?php

class CouplesTable extends Table
{

    public static function getItems()
    {
        $pdo = dbPdo::getPdo();
        $sql = "SELECT couples.id, img_path, full_name_m, 	full_name_w, couples.id_host,  description, cost, hosts.hostname as host_name FROM `couples`
				inner join `hosts` on couples.id_host = hosts.id
		";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRuTable(): string
    {
        return 'Пары';
    }

    public static function getItemById($id)
    {
        $pdo = dbPdo::getPdo();
        $sql = "SELECT couples.id, img_path, full_name_m, 	full_name_w, couples.id_host,  description, cost, hosts.hostname as host_name FROM `couples`
				inner join `hosts` on couples.id_host = hosts.id
				WHERE couples.id = '$id'
		";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getItemsByFilter($id){
        $pdo = dbPdo::getPdo();
        $sql = "SELECT couples.id, img_path, full_name_m, 	full_name_w, couples.id_host,  description, cost, hosts.hostname as host_name FROM `couples`
				inner join `hosts` on couples.id_host = hosts.id
                WHERE couples.id_host = '$id'
		";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}