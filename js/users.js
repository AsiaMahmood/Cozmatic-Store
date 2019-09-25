$(function () {

    'use strict';

    // Hide placeholder on focus 
    $('[placeholder]').focus( function () {

        $(this).attr( 'data-text' , $(this).attr('placeholder'));

        $(this).attr('placeholder','');
 
    }).blur(function() {
        
        $(this).attr('placeholder' , $(this).attr('data-text'));

    });

});

function signUp() {
    let element = document.getElementById("container_SignIn");
    element.classList.add("myStyle");
}

function signIn() {
    let element = document.getElementById("container_SignIn");
    element.classList.remove("myStyle");
}