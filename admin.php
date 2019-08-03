<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;

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

?>