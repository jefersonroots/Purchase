<?php

@$login = $_POST['login'];
@$senha = md5($_POST['senha']); 
@$entrar = $_POST['entrar'];
    //Criar a conexao
    $conn = mysqli_connect("110.100.2.9","root", "root", "tesouraria");
    if(!$conn){
        die("Falha na conexao: " . mysqli_connect_error());
    }else{
        //echo "Conexao realizada com sucesso";
    }      
?> 