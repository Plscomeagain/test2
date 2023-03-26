<?php
// проверка на цену (неотриц и не слишком большая)
class CouplesActions extends Actions
{
    public static function validatePrice($price)
    {
        if ($price < 0 || $price > 1000000) {
            return false;
        }
        if (!ctype_digit($price)) {
            return false;
        }
        return true;
    }

    public static function checkErrors(array $data , array $files){
        // $data = $_POST

        $errors = GeneralLogic::initErr();
        if(empty($files['img_path']['name'])){
            $errors = GeneralLogic::setErr('Нету картинки', $errors);
        }
        //Проверка на пустые поля
        if (!array_filter($data)) {
            $errors = GeneralLogic::setErr('Все поля пустые', $errors);
            return $errors;
        }
        //Проверка на пустые моля, когда есть не пустые
        foreach ($data as $key => $field){
            if (empty($field)){
                $str = 'Поле ' . $key . ' не заполнено';
                $errors = GeneralLogic::setErr($str, $errors);
                //Проверка валидности цены
            }
            if($key == 'item_cost' && !empty($field)){
                self::validatePrice($field) ? '' :  $errors = GeneralLogic::setErr('Некорректная цена', $errors);
            }
        }

        return $errors;
    }
    public static function saveErrValues($err, $data, $img_path){
        // $data = $_POST
        if(count($err) === 0){
            return true;
        }
        $values = [];
        $data['img_path'] = $img_path;
        foreach ($data as $key => $value) {
            $key = str_replace('item_', '',$key);
            $values[0][$key] = $value;
        }

        return  $values;
    }

}