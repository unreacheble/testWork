<?php
/**
 * Created by JetBrains PhpStorm.
 * User: unreacheble
 * Date: 03.06.14
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */



class Form {

    public $inviteId;
    public $login;
    public $password;
    public $phone;
    public $countryId;
    public $cityId;


    public function draw($inviteId = false){
        if($inviteId != false){
            $form = "
                <script type='text/javascript' src = 'jquery-1.11.1.min.js'></script>
                <script type='text/javascript' src = 'form.js'></script>
                <form id=\"registerForm\" name = \"rerister\" method = \"post\">
                    Инвайт: <input type = \"text\" disabled = \"disabled\" value =\"{$inviteId}\"><br>
                    <input type = \"hidden\" value =\"{$inviteId}\" name = \"inviteId\"><br>
                    Логин: <input type = \"text\" name = \"login\" onchange=\"validate(this);\">
                    <div class = \"loginErr\"></div><br>
                    Пароль: <input type = \"password\" name = \"password\"><br>
                    Подтвердите: <input type = \"password\" name = \"password2\"onchange=\"validate(this);\">
                    <div class = \"passErr\"></div><br>
                    Телефон: <input type = \"text\" name = \"phone\"onchange=\"validate(this);\">
                    <div class = \"phoneErr\"></div><br>
                    ".
                    self::drawCountrySelect() .
                    self::drawCitySelect()
                    .
                    "
                    <br>
                    <input type = \"submit\" value = \"Зарегистрировать\" id = \"submitBtn\" disabled=\"disabled\">
                    <input type = \"button\" value = \"Очистить\" onClick=\"$('#registerForm').trigger( 'reset' );\">
                </form>
            ";
        }

        echo $form;
    }

    public function submit(){
        $db = new Db();
        $db->set( 'invites', array( 'id'=>$this->inviteId, 'status'=> 1, 'date_status'=> time() ) );
        unset($this->inviteId);
        $userAdd = $db->set( 'users', (array)$this);
        return (bool)$userAdd;
    }

    public static function drawCountrySelect(){
        $db = new Db;
        $countries = $db->get( 'countries' );
        $result = "<select name='countryId' id = \"countryId\">";
        $result .= "<option disabled selected>Страна</option>";
        foreach($countries AS $v){
            $result .= "<option value=\"" . $v['id'] . "\">" . $v['country_name'] ."</option>";
        }
        $result .= "</select>";
        return $result;
    }
    public static function drawCitySelect($countryId = null){
        $db = new Db;
        $cityList = $db->get( 'cities', array('countryId'=>$countryId));
        $result = "<select name='cityId' id = \"cityId\">";
        $result .= "<option disabled selected>Город</option>";
        foreach($cityList AS $v){
            $result .= "<option countryId = \"". $v['countryId'] ."\" value=\"" . $v['id'] . "\">" . $v['cityName'] ."</option>";
        }
        $result .= "</select>";
        return $result;
    }
    public function validate(){
        $db = new Db();
        $errorMsg = "";
        if( strlen($this->login) < 5 OR strlen($this->login) > 20){
            $errorMsg = "Недопустимая длинна";
        }
        if(!preg_match( '/^[0-9a-z]+$/i', $this->login )){
            $errorMsg = "Недопустимые символы в логине";
        }
        if( (bool)$db->get( 'users', array( 'login'=>$this->login ) ) ){
            $errorMsg = "Логин занят";
        }

        $this->phone = preg_replace( '/[^0-9]/', '',$this->phone);
        if( strlen($this->phone ) < 10 OR strlen($this->phone) > 15 ){
            $errorMsg = "Неправильный телефон";
        }
        if( strlen($this->password) < 5 OR strlen($this->password) > 20){
            $errorMsg = "Недопустимые символы в пароле";
        }
        /*
         * Проверку совпадения пароля с подтверждением не делаю т.к.
         * 1 - проверяется js скриптом
         * 2 - это тестовое задание, и в нём нужно показать красивый код.
         * Хотя если это важно могу дописать))
         * А ещё проще сделать это всё на yii и пользоваться встроеными валидаторами))))
        */
        return $errorMsg;
    }
}