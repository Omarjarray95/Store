<?php

require_once 'init.php';
require_once "vendor/autoload.php";

$router = new App($_GET['url']);

$router->get('/home', "HomeController#Index");

$router->get('/login', 'User#RenderLogin');

$router->post('/login', 'User#Login');

$router->get('/logout', 'User#Logout');

$router->get('/addCategories', 'Category#RenderAdd');

$router->post('/addCategory', 'Category#Add');

$router->get('/listCategories', 'Category#ListAll');

$router->get('/updateCategories/:id', 'Category#RenderUpdate')->with('id', '[0-9]+');

$router->post('/updateCategory/:id', 'Category#Update')->with('id', '[0-9]+');

$router->get('/deleteCategory/:id', 'Category#Delete')->with('id', '[0-9]+');

$router->get('/addProducts', 'Product#RenderAdd');

$router->post('/addProduct', 'Product#Add');

$router->get('/updateProducts/:id', 'Product#RenderUpdate')->with('id', '[0-9]+');

$router->post('/updateProduct/:id', 'Product#Update')->with('id', '[0-9]+');

$router->get('/deleteProduct/:id', 'Product#Delete')->with('id', '[0-9]+');

$router->get('/addPromotions/:id', 'Product#RenderAddPromotion')->with('id', '[0-9]+');

$router->post('/addPromotion/:id', 'Product#AddPromotion')->with('id', '[0-9]+');

$router->get('/deletePromotion/:id', 'Product#DeletePromotion')->with('id', '[0-9]+');

$router->post('/searchProducts', 'Product#ListSearch');

$router->get('/listPromotedProducts', 'Product#ListPromotedProducts');

$router->get('/listCategoryProducts/:id', 'Product#ListCategoryProducts')->with('id', '[0-9]+');

$router->get('/addToCart/:id', 'Product#RenderAddToCart')->with('id', '[0-9]+');

$router->get('/resetCart', 'Product#ResetCart');

$router->post('/addProductToCart/:id', 'Product#AddToCart')->with('id', '[0-9]+');

$router->get('/listCart', 'Product#ListCart');

$router->get('/deleteFromCart/:id', 'Product#DeleteFromCart')->with('id', '[0-9]+');

$router->post('/orderFromCart/:id/:quantity', 'Product#Order')->with('id', '[0-9]+')
    ->with('quantity', '[0-9]+');

$router->run();