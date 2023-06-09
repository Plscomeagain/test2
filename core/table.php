<?php

/**
 * введеные значения полей, которые стоят в порядке, как $columns, поэтому мы должны ее исп. а не
 * $ars
 * $args - ассоц. массив, где ключи это название полей. ["id"=>1, "name"=>"Alex",'group_id' => 1]
 * $count - счетчик правильных колоннок
 * $columns - ['id','name', 'group_id']
 * $columns - Выкинул 'id'
 */
abstract class Table
{
    // 3 абстрактных метода которые реализуются в классах, наследуемых от этого
    abstract static function getRuTable(): string;

    abstract public static function getItemById($id);

    abstract public static function getItems();
    // создание записи
    public static function create($args)
    {
        $columns = static::getAllColumn();

        $columns = array_splice($columns, 1, count($columns) - 1);
        $count = 0;

        $values = [];
        foreach ($columns as $val) {
            foreach ($args as $key => $value) {
                if ($val == $key) {
                    $count++;
                    $values[] = $value;
                }
            }
        }
        if ($count != count($columns)) {
            return "Поля не совпадают";
        }
        $pdo = dbPdo::getPdo();

        $table = static::getTableName(); // получаем название таблицы
        $columnString = implode(',', $columns); // обозначаем столбцы
        $valueString = implode(',', array_fill(0, count($values), '?')); // обозначаем чем заполнять будем(инпутами)
        $stmt = $pdo->prepare("INSERT INTO $table ({$columnString}) VALUES ({$valueString})");
        $stmt->execute(array_values($values));

        return $stmt->fetchColumn();
    }

    public static function edit($args)
    {
        $columns = static::getAllColumn();

        $columns = array_splice($columns, 1, count($columns) - 1);
        $count = 0;

        $args['id'] ? $id = $args['id'] : null;
        $values = [];
        foreach ($columns as $val) {
            foreach ($args as $key => $value) {
                if ($val == $key) {
                    $count++;
                    $values[] = $value;
                }
            }
        }
        if ($count != count($columns)) {
            return "Поля не совпадают";
        }
        $pdo = dbPdo::getPdo();

        $table = static::getTableName();
        $columnString = [];
        foreach ($columns as $val) {
            $columnString[] = $val . " = ?";
        }
        $columnString = implode(',', $columnString);

        $stmt = $pdo->prepare("UPDATE $table SET {$columnString} WHERE `id` = '$id'"); // логика таже что и с созданием только не нужно указывать values
        $stmt->execute(array_values($values));

        return $stmt->fetchColumn();
    }

    public static function del($id)
    {
        $pdo = dbPdo::getPdo();
        $table = static::getTableName();
        $stmt = $pdo->prepare("DELETE FROM $table WHERE `id` = '$id'");
        $stmt->execute();
    }

    public static function getTableName(): string
    {
        $modelName = get_called_class(); // возвращает название класса

        $modelName = str_replace('Table', '', $modelName); // заменяет слово Table  конце на пустую строку


        return strtolower($modelName);

    }
    // получение всех столбцов из таблиц
    public static function getAllColumn()
    {
        $pdo = dbPdo::getPdo();
        $table = static::getTableName();
        $stmt = $pdo->query("SELECT * FROM $table");
        $res = $stmt->fetchAll(PDO::FETCH_CLASS);
        $columns = [];
        foreach ($res as $k => $v) {
            foreach ($v as $key => $value) {
                $columns[] = $key;
            }
            break;
        }
        return $columns;
    }

}