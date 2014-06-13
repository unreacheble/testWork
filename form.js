/**
 * Created with JetBrains PhpStorm.
 * User: unreacheble
 * Date: 03.06.14
 * Time: 19:08
 * To change this template use File | Settings | File Templates.
 */

function validate( input ){
    var errorMsg = "";
    if( input.name == "login"){

        if( input.value.length < 5 || input.value.length > 20 ){
            errorMsg = "Недопустимая длинна";
        }
        if(!input.value.match(/^[0-9a-z]+$/i)){
            errorMsg = "Недопустимые символы в логине";
        }
        var i = $.get( "index.php?json&",{ login: input.value }, function( data ) {
          if(data == "1"){
              errorMsg = "Логин занят";
              $(".loginErr").html(errorMsg);
          }
        });
        if( input.value == "" ){
            errorMsg = "Поле должно быть заполнено";
        }
            $(".loginErr").html(errorMsg);
    }
    if( input.name == "phone" ){
        if(!input.value.match(/^[0-9_\(\) +]+$/i) || input.value.length < 10 || input.value.length > 15){
            errorMsg = "Неверно указан номер";
        }
        if( input.value == "" ){
            errorMsg = "Поле должно быть заполнено";
        }

        $(".phoneErr").html(errorMsg);

    }
    if( input.name == "password2" ){
        if( input.value.length < 5 || input.value.length > 20 ){
            errorMsg = "Недопустимая длинна";
        }
        if(!input.value.match(/^[0-9a-z]+$/i)){
            errorMsg = "Недопустимые символы в пароле";
        }
        if(input.value != $("input[name='password']").val()){
            errorMsg = "Пароли не совпадают";
        }
        $(".passErr").html(errorMsg);
    }
    if( input.value == "" ){
        errorMsg = "Поле должно быть заполнено";
    }

    if( errorMsg != "" ){
        $("#submitBtn").attr('disabled', 'disabled');
    }else{
        $("#submitBtn").removeAttr('disabled');
    }
}

$(document).ready(function(){
    $("#countryId").change(function(){
        var options = "<option disabled selected>Город</option>";
        $.getJSON( "index.php",{json:"1",countryId: $("#countryId").val() }, function( data ) {
            $.each( data, function( key, val ) {
                options += "<option value = '" + val.id + "'>" + val.cityName + "</option>";
                });
            $("#cityId").html(options);

        });
    });
});


