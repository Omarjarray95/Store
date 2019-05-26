<?php

namespace App\Controller;

use App\Model\User as User;

class UserController extends Controller
{
    public function RenderLogin()
    {
        echo $this->twig->render('login.html.twig',
            ['error_message' => '']);
    }

    public function Login()
    {
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

        if ((strlen($_POST['username']) > 0) && (strlen($_POST['password']) > 0))
        {
            /*$query = $this->em->createQuery("SELECT u FROM App\Model\User u WHERE u.username = '". $_POST['username']
        . "' And u.password = '". $_POST['password'] . "'");*/
            $user = $this->em->getRepository('App\Model\User')->findOneBy(array('username' => $_POST['username'],
                'password' => $_POST['password']));
            if (isset($user))
            {
                session_start ();
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['loggedIn'] = true;
                header('location: /BookStore/home');
            }
            else
            {
                echo $this->twig->render('home.html.twig',
                    ['username' => 'Guest',
                        'loggedIn' => false,
                        'products' => $products,
                        'categories' => $categories,
                        'cart' => $_SESSION['cart'],
                        'error_message' => 'Invalid Credentials']);
            }
        }
        else
        {
            echo $this->twig->render('home.html.twig',
                ['username' => 'Guest',
                    'loggedIn' => false,
                    'products' => $products,
                    'categories' => $categories,
                    'cart' => $_SESSION['cart'],
                    'error_message' => 'Invalid Information']);
        }
    }

    public function Logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('location: /BookStore/home');
    }
}