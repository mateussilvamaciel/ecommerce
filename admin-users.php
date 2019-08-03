<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
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
?>