<?php
/**
 * Created by JetBrains PhpStorm.
 * User: unreacheble
 * Date: 05.06.14
 * Time: 12:41
 * To change this template use File | Settings | File Templates.
 */

require_once( 'Db.php' );
require_once( 'Form.php' );
$db = new Db();

$users = $db->get('users');
if(is_array($users) AND count($users) > 0){
    foreach($db->get('cities') AS $v){
        $cities[$v['id']] = $v['cityName'];
    }
    foreach($db->get('countries') AS $v){
        $countries[$v['id']] = $v['country_name'];
    }
}
foreach($users AS $user){
    echo "Логин: " . $user['login'] . " Пароль " . $user['password'] . " Страна: " . $countries[$user['countryId']] . " Город " . $cities[$user['cityId']] ."<br>";
}

$invites = $db->get('invites');
foreach($invites AS $invite){
    echo "ID " . $invite['id'] . " Статус ";
    echo $invite['statis'] ? ' Свободен ' : " Зарегистрирован ";
    echo isset($invite['date_status']) ? date('d-m-Y', $invite['date_status']) : '' ;
    echo "<br>";
}

?>