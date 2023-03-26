<?php



abstract class Actions
{
    public static function add()
    {
        if (empty($_POST)) {
            return ;
        }
        $className = static::getTableName() . 'Table';
        $isError = False;
        $callColumn = "{$className}::getAllColumn";
        $callCreate = "{$className}::create";
        $columns = $callColumn();
        $columns = array_splice($columns, 1, count($columns) - 1);// удаляет выбранные элементы из массива и заменяет их новыми элементами.
        $values = [];


        // цикл чтобы достать имена столбцов из таблицы, чтобы склеить их потом с item_(item_img_path....и тп
        for ($i = 0; $i < count($columns); $i++) {

            $name = 'item_' . $columns[$i];
            // чтобы пропускал картинку
            if (is_int(strpos($name, 'img_path'))) {
                $values[$columns[$i]] = $_FILES['item_img_path']['name'];
                continue;
            }
            // проверка на id
            if (strpos($name, 'id') !== false) {
                $number = GeneralLogic::validateId($_POST[$name]);
                if ($number == '') {
                    $isError = True ;
                }
                $values[$columns[$i]] = $number;
            } else {
                // проверка на имя
                $string = GeneralLogic::validateName($_POST[$name]);
                if ($string == '') {
                    $isError = True ;
                }
                $values[$columns[$i]] = $string;
            }

        }
        if(static::CheckTable() === false){
            $isError = True;
        }

        if($isError){return $values;}
        $callCreate($values);

        return true;

    }

    public static function edit()
    {
        if(empty($_POST)){
            return;
        }
        $className = static::getTableName() . 'Table'; // забирает название таблицы из абстрактного класса table
        $callColumn = "{$className}::getAllColumn"; // забирает все столбцы из таблицы из абстрактного класс table
        $callEdit = "{$className}::edit"; // обращается к едиту из абстрактного класса table
        $columns = $callColumn();
        // тот же самый смысл что и в создании(create)
        for ($i = 0; $i < count($columns); $i++) {
            $name = 'item_' . $columns[$i];
            if (is_int(strpos($name, 'img_path'))) {
                $values[$columns[$i]] = $_FILES['item_img_path']['name'];
                continue;
            }
            if (strpos($name, 'id') !== false) {
                $number = isset($_POST[$name]) ? GeneralLogic::validateId($_POST[$name]) : '';
                if ($number == '') {
                    return false;
                }
                $values[$columns[$i]] = $number;
            } else {
                $string = GeneralLogic::validateName($_POST[$name]);
                $values[$columns[$i]] = $string;
            }
        }
        $callEdit($values);

        return true;

    }

    // возвращает имя класса заменяя в конце Actions на пустую строку
    private static function getTableName(): string
    {
        $modelName = get_called_class();

        return str_replace('Actions', '', $modelName);

    }

    private static function CheckTable()
    {
        $result = True;
        $className = static::getTableName() ;
        if($className === 'Couples')
        {
            if(!(CouplesActions::validatePrice($_POST['item_cost']) && GeneralLogic::checkImg($_FILES['item_img_path'])))
            {
                $result = False;
            }
        }
        return $result;

    }

}