<?php

use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->get('/docinterne', 'Auth::login');
$routes->post('/docinterne/auth/authenticate', 'Auth::authenticate');
$routes->group('docinterne', ['filter' => 'auth'], static function ($routes) {
	$routes->get('admin', 'Admin::accueiladmin'); 
	$routes->get('utilisateur', 'Utilisateur::accueiluser');
	$routes->get('dossiers/create', 'Dossier::create');
	$routes->post('dossiers/store', 'Dossier::store');
	$routes->get('utilisateurs/create', 'Utilisateur::create');
	$routes->post('utilisateurs/store', 'Utilisateur::store');
	$routes->get('utilisateurs/edit/(:num)', 'Utilisateur::edit/$1');
	$routes->post('utilisateurs/update/(:num)', 'Utilisateur::update/$1');
	$routes->post('utilisateurs/delete/(:num)', 'Utilisateur::delete/$1');
	$routes->get('departements', 'Departement::index');
	$routes->get('documents/list/(:num)', 'Document::list/$1');
	$routes->post('documents/store', 'Document::store');
	$routes->get('documents/download/(:num)', 'Document::download/$1');
	$routes->get('documents/preview/(:num)', 'Document::preview/$1');
	$routes->get('documents/pagePermission/(:num)', 'Document::pagePermission/$1');
	$routes->post('documents/pagePermission/(:num)', 'Document::savePermission/$1');
	$routes->get('departements/create', 'Departement::create');
	$routes->post('departements/store', 'Departement::store');
	$routes->get('departements/edit/(:num)', 'Departement::edit/$1');
	$routes->post('departements/update/(:num)', 'Departement::update/$1');
	$routes->post('departements/delete/(:num)', 'Departement::delete/$1');
	$routes->post('logout', 'Auth::logout');
});

