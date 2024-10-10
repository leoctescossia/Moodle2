<?php

    $servername = 'localhost';
    $dbname = 'escola';
    $username = 'root';
    $password = '';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn -> connect_errno){
        echo "Error";
    }
    else{
        //echo "Cadastro efetuado com sucesso";
    }


?>