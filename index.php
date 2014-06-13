<?php

require_once( 'Db.php' );
require_once( 'Form.php' );
$db = new Db();

if(isset($_GET['json'])){
    if(isset($_GET['countryId'])){
        $cities = $db->get('cities', array('countryId' =>$_GET['countryId']));
        $response = json_encode($cities);
    }
    if( isset($_GET['login']) ){
        $response = (int)$db->get('users', array( 'login'=> $_GET['login'] ));
    }
    echo $response;
    exit;
}
$form = new Form();
if( isset( $_POST['login'] )
    AND isset( $_POST['password'] )
    AND isset( $_POST['phone'] )
    AND isset( $_POST['inviteId'] )){
    if( (int)$db->get( 'invites', array( 'id'=> $_POST['inviteId']+0, 'status'=>'0' ) ) ){
        $form->login = $_POST['login'];
        $form->password = $_POST['password'];
        $form->phone = $_POST['phone'];
        $form->inviteId = $_POST['inviteId'];
        $form->countryId = $_POST['countryId'];
        $form->cityId = $_POST['cityId'];

        $validate = $form->validate();
        if($validate != ""){
            echo $validate;exit;
        }
        if( $form->submit() ){
            echo "Регистрация прошла успешно";
        }
    }else{
        echo "Такого инвайта нет или он уже использован";
    }
}elseif($_GET['invite'] != null){
    if( (int)$db->get( 'invites', array( 'id'=> $_GET['invite']+0, 'status'=>'0' ) ) ){
        $form->draw($_GET['invite']);
    }else{
        echo "Такого инвайта нет или он уже использован";
    }
}else{
    echo "Такого инвайта нет или он уже использован";
}

?>