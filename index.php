<?php

require_once("vendor/autoload.php");
use Statickidz\GoogleTranslate;

if(isset($_GET['pt']))
        echo pt_en($_GET['pt']);
if(isset($_GET['en']))
    echo en_pt($_GET['en']);
if(isset($_GET['msg']))
{
    $texto = get_msg($_GET['msg']);
    echo $texto['texto']."\n\nAutor: ".$texto['nome']."\n";  
}
if(isset($_GET['msg_en']))
{
    $texto = get_msg($_GET['msg_en']);
    echo pt_en($texto['texto'])."\n\nAutor: ".$texto['nome']."\n";  
}

    
function get_msg($tema){
    $texto = select("select * from autor join texto on autor.idautor = texto.idautor where texto.texto like '%".$tema."%' ;");
    $pos = rand(0,(count($texto)-1));
    return $texto[$pos];

}
    
function sent($source,$target,$text){
    $trans = new GoogleTranslate();
    return $trans->translate($source, $target, $text);        
}

function pt_en($text){
    return sent('pt_br','en',$text)."\n";
}

function en_pt($text){
    return sent('en','pt_br',$text)."\n";
}

function start(){
    return new \PDO(
        "mysql:dbname=frase".";host=localhost",
        "root",
        "root"
    );
}

 function setParams($statement, $parameters = array())
{
    foreach ($parameters as $key => $value) {
        bindParam($statement, $key, $value);
    }
}

 function bindParam($statement, $key, $value)
{
    $statement->bindParam($key, $value);
}

 function query($rawQuery, $params = array())
{
    $conn = start();
    $conn->exec("set names utf8");
    $stmt = $conn->prepare($rawQuery);		
    setParams($stmt, $params);
    $stmt->execute();
}

 function select($rawQuery, $params = array())
{
    $conn = start();
    $conn->exec("set names utf8");
    $stmt = $conn->prepare($rawQuery);		
    setParams($stmt, $params);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

?>