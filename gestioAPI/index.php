<?php
require 'flight/Flight.php';

//Registre enllaç a base de dades per a realitzar les connexions
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=em1hzw3c_gestio','em1hzw3c_admgest','@X1kh0122'), function($db){
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

//Llistar l'empresa actual
Flight::route('GET /empresa', function(){
    $db=Flight::db();    
    $emp = $db->prepare('SELECT empresa.*, poblacio.poblacio AS pob FROM empresa INNER JOIN poblacio ON empresa.poblacio = poblacio.cp LIMIT 1');
    $emp->execute();
    $e = $emp->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($e);
    $db=NULL;
});

//Llistar totes les poblacions
Flight::route('GET /poblacions', function(){
    $db=Flight::db();    
    $pob = $db->prepare('SELECT * FROM poblacio');
    $pob->execute();
    $p = $pob->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($p);
    $db=NULL;
});

//Llistar una població
Flight::route('GET /poblacio/@poblacio:[0-9]{5}', function($poblacio){
    $db=Flight::db();    
    $pob = $db->prepare('SELECT * FROM poblacio WHERE cp=:poblacio LIMIT 1');
    $pob->bindParam(':poblacio', $poblacio, PDO::PARAM_INT);
    $pob->execute();
    $p = $pob->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($p);
    $db=NULL;
});

//Insertar una població
Flight::route('/poblacio/@cp:[0-9]{5}/@poblacio', function($cp,$poblacio){
    $db=Flight::db();
    try{
        $pob = $db->prepare("INSERT INTO poblacio (cp,poblacio) VALUES (:cp,:poblacio)");
        $pob->bindParam(':cp', $cp, PDO::PARAM_INT);
        $pob->bindParam(':poblacio', $poblacio);
        $pob->execute();
        echo 1;
    } catch (Exception $ex) {
        echo 'Error<br>'.$ex;
    }
    $db=NULL;
});

//Llistar els clients
Flight::route('GET /clients', function(){
    $db=Flight::db();    
    $cli = $db->prepare('SELECT clients.*, poblacio.poblacio AS pob FROM clients INNER JOIN poblacio ON clients.poblacio = poblacio.cp');
    $cli->execute();
    $c = $cli->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($c);
    $db=NULL;
});

//Mostrar un client per ID
Flight::route('GET /client/@id:[0-9]', function($id){
    $db=Flight::db();    
    $cli = $db->prepare('SELECT clients.*, poblacio.poblacio AS pob FROM clients INNER JOIN poblacio ON clients.poblacio = poblacio.cp WHERE id=:id LIMIT 1');
    $cli->bindParam(':id',$id, PDO::PARAM_INT);
    $cli->execute();
    $c = $cli->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($c);
    $db=NULL;
});

//Llistar el personal de l'empresa
Flight::route('GET /personal', function(){
    $db=Flight::db();    
    $per = $db->prepare('SELECT personal.*, poblacio.poblacio AS pob FROM personal INNER JOIN poblacio ON personal.poblacio = poblacio.cp');
    $per->execute();
    $p = $per->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($p);
    $db=NULL;
});

//Mostrar un empleat per ID
Flight::route('GET /personal/@id:[0-9]', function($id){
    $db=Flight::db();    
    $per = $db->prepare('SELECT personal.*, poblacio.poblacio AS pob FROM personal INNER JOIN poblacio ON personal.poblacio = poblacio.cp WHERE id=:id LIMIT 1');
    $per->bindParam(':id',$id, PDO::PARAM_INT);
    $per->execute();
    $p = $per->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($p);
    $db=NULL;
});

Flight::start();