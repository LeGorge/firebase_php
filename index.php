<?php

require_once "./vendor/autoload.php";
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database\RuleSet;

class Pessoa {
    public $nome;
    public $idade;

    public function __construct($nome, $idade){
        $this->nome = $nome;
        $this->idade = $idade;
    }
}

$firebase = (new Factory)
    ->withServiceAccount('./secret/mercado-livre-fe014-46df39487f1a.json')
    // The following line is optional if the project id in your credentials file
    // is identical to the subdomain of your Firebase project. If you need it,
    // make sure to replace the URL with the URL of your project.
    // ->withDatabaseUri('https://my-project.firebaseio.com')
    ->create();

$database = $firebase->getDatabase();
$ruleSet = RuleSet::private();
$database->updateRules($ruleSet);
        
$ref = $database->getReference('users');
//INSERT

//insert without key
$user1 = new Pessoa('teste1', 1);
$user1 = json_decode(json_encode($user1), true); //converts object to array
$pushRef = $ref->push($user1);

//insert with key
$user2 = new Pessoa('teste2', 2);
$pushRef = $ref->push();
$id = $pushRef->getKey();

$ref->update(
    [
        $id => $user2
    ]
);


//GET

//get all users
$snapshot = $ref->getSnapshot();
$users = $snapshot->getValue();
// die(print_r($users));

foreach($users as $key => $value){
    $obj = json_decode(json_encode($value),false); //converts array to object
    echo $key.': '.$obj->nome.' - '.$obj->idade.'/n';
}

//get specific user
$snapshot = $ref->getChild($id);
$value = $snapshot->getValue();
$obj = json_decode(json_encode($value),false); //converts array to object
echo $id.': '.$obj->nome.' - '.$obj->idade.'/n';

//UPDATE
$snapshot = $ref->getChild($id);
$snapshot->getChild('nome')->set('NOVO NOME');

// REMOVE
$snapshot = $ref->getChild($id);
$snapshot->remove();

?>