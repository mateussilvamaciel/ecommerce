<?php 
session_start();
require_once("vendor/autoload.php");
require_once("functions.php");

use \Hcode\Model\User;

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Hcode\Page();

	$page->setTpl("index");

});

$app->get('/admin', function() {

	User::verifyLogin();
    
	$page = new Hcode\PageAdmin();

	$page->setTpl("index");

});

$app->get('/admin/login/', function(){

	$page = new Hcode\PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("login");

});

$app->post('/admin/login', function(){

	User::login(post('deslogin'), post('despassword'));

	header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function(){

	User::logout();

	header("Location: /admin/login");
	exit;

});
/////////////////////////////////////////////////Criando as rotas
///listar todos os usuarios
$app->get("/admin/users", function(){

	User::verifyLogin();

	$users = User::listAll();

	$page = new Hcode\PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
	));

});
//cadastrar os usuarios
$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new Hcode\PageAdmin();

	$page->setTpl("users-create");

});
//fazendo o editar
$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new Hcode\PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});
//deletar do sistema
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});
//Esse é o editar
$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new Hcode\PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});
//rota para salvar(esse é o cadastrar)
$app->post("/admin/users/create", function(){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	//$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		//"cost"=>12

 	//]);

	$user->setData($_POST);

	$user->save();

	var_dump($user);

	header("Location: /admin/users");
	exit;


});

$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;
	
});
///////////////////////////////////////////esqueci a senha criando as rotas
//rota do forgot
$app->get("/admin/forgot", function(){

	$page = new Hcode\PageAdmin([
	"header"=>false,
	"footer"=>false

	]);

	$page->setTpl("forgot");

});
//rota que envia o formulario
$app->post("/admin/forgot", function(){

	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/send");
	exit;

});

$app->get("/admin/forgot/sent", function(){

	$page = new Hcode\PageAdmin([
	"header"=>false,
	"footer"=>false

	]);

	$page->setTpl("forgot-send");

});

$app->get("/admin/forgot/reset", function())
{
	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new Hcode\PageAdmin([
	"header"=>false,
	"footer"=>false

	]);

	$page->setTpl("forgot-reset", array(
	"name"=>$user["desperson"],
	"code"=>$_GET["code"]
	));

});

$app->post("/admin/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setFogotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);


	$page = new Hcode\PageAdmin([
	"header"=>false,
	"footer"=>false

	]);

	$page->setTpl("forgot-reset-success");


});

$app->run();


 ?>