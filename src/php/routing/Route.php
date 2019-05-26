<?php

use App\Controller\UserController;
use App\Controller\HomeController;
use App\Controller\CategoryController;
use App\Controller\ProductController;

class Route
{
    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');  // On retire les / inutils
        $this->callable = $callable;
    }

    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";
        if(!preg_match($regex, $url, $matches))
        {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    private function paramMatch($match)
    {
        if(isset($this->params[$match[1]]))
        {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    public function call()
    {
        if(is_string($this->callable))
        {
            $params = explode('#', $this->callable);
            //require_once "src/php/controllers/" . $params[0] . ".php";
            //$Controller = "App\\Controller\\" . $params[0];
            switch ($params[0])
            {
                case "HomeController":
                    $Controller = new HomeController();
                    break;
                case "User":
                    $Controller = new UserController();
                    break;
                case "Category":
                    $Controller = new CategoryController();
                    break;
                case "Product":
                    $Controller = new ProductController();
                    break;
                default:
                    $Controller = new HomeController();
            }
            return call_user_func_array([$Controller, $params[1]], $this->matches);
        }
        else
        {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    public function with($param, $regex)
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this; // On retourne tjrs l'objet pour enchainer les arguments
    }

    public function getUrl($params)
    {
        $path = $this->path;
        foreach($params as $k => $v)
        {
            $path = str_replace(":$k", $v, $path);
        }
        return $path;
    }

}