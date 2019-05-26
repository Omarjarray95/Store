<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Index()
    {
        session_start ();

        if (!isset($_SESSION['cart']) && !isset($_SESSION['products']))
        {
            $_SESSION['cart'] = 0;
            $_SESSION['products'] = [];
        }

        $products = $this->em->getRepository('App\Model\Product')
            ->findBy([], ['designation' => 'ASC']);

        $categories = $this->em->getRepository('App\Model\Category')
            ->findBy([], ['name' => 'ASC']);

        foreach ($products as $product)
        {
            if ($product->getInPromotion() != null)
            {
                if ($product->getInPromotion()->getStartingDate() > new \DateTime()
                    || $product->getInPromotion()->getEndDate() < new \DateTime())
                {
                    $product->setInPromotion(null);
                    $this->em->merge($product);
                    try
                    {
                        $this->em->flush();
                    }
                    catch (OptimisticLockException $e)
                    {
                        var_dump($e->getMessage());
                    }
                }
            }
        }

        $products = $this->em->getRepository('App\Model\Product')
        ->findBy([], ['designation' => 'ASC']);

        if (isset($_SESSION['username']) && isset($_SESSION['loggedIn']))
        {
            echo $this->twig->render('home.html.twig',
                ['username' => $_SESSION['username'],
                    'loggedIn' => $_SESSION['loggedIn'],
                    'products' => $products,
                    'categories' => $categories,
                    'cart' => $_SESSION['cart']]);
        }
        else
        {
            echo $this->twig->render('home.html.twig',
                ['username' => 'Guest',
                    'loggedIn' => false,
                    'products' => $products,
                    'categories' => $categories,
                    'cart' => $_SESSION['cart']]);
        }
    }
}