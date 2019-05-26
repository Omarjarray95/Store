<?php

namespace App\Controller;

use App\Model\Category as Category;
use Doctrine\ORM\OptimisticLockException;

class CategoryController extends Controller
{
    public function RenderAdd()
    {
        session_start();

        echo $this->twig->render('addCategory.html.twig',
            ['error_message' => '',
                'loggedIn' => $_SESSION['loggedIn']]);
    }

    public function Add()
    {
        session_start();

        if (strlen($_POST['name']) > 0)
        {
            $category = new Category();
            $category->setName($_POST['name']);
            $category->setDescription($_POST['description']);
            $this->em->persist($category);
            try
            {
                $this->em->flush();
                header('location: /BookStore/listCategories');
            }
            catch (OptimisticLockException $e)
            {
                echo $this->twig->render('addCategory.html.twig',
                    ['error_message' => 'Server Problem ...',
                    'loggedIn' => $_SESSION['loggedIn']]);
            }
        }
        else
        {
            echo $this->twig->render('addCategory.html.twig',
                ['error_message' => 'Invalid Information',
                    'loggedIn' => $_SESSION['loggedIn']]);
        }
    }

    public function ListAll()
    {
        session_start();

        $categories = $this->em->getRepository('App\Model\Category')->findBy([], ['name' => 'ASC']);

        echo $this->twig->render('allCategories.html.twig',
            ['categories' => $categories,
                'loggedIn' => $_SESSION['loggedIn']]);
    }

    public function RenderUpdate($id)
    {
        session_start();

        $category = $this->em->getRepository('App\Model\Category')->find($id);

        echo $this->twig->render('updateCategory.html.twig',
            ['category' => $category,
                'error_message' => '',
                'loggedIn' => $_SESSION['loggedIn']]);
    }

    public function Update($id)
    {
        session_start();

        if (strlen($_POST['name']) > 0)
        {
            $category = $this->em->getRepository('App\Model\Category')->find($id);
            $category->setName($_POST['name']);
            $category->setDescription($_POST['description']);
            $this->em->merge($category);
            try
            {
                $this->em->flush();
                header('location: /BookStore/listCategories');
            }
            catch (OptimisticLockException $e)
            {
                echo $this->twig->render('updateCategory.html.twig',
                    ['error_message' => 'Server Problem ...',
                        'loggedIn' => $_SESSION['loggedIn']]);
            }
        }
        else
        {
            echo $this->twig->render('updateCategory.html.twig',
                ['error_message' => 'Invalid Information',
                    'loggedIn' => $_SESSION['loggedIn']]);
        }
    }

    public function Delete($id)
    {
        session_start();

        $category = $this->em->getRepository('App\Model\Category')->find($id);
        $this->em->remove($category);
        try
        {
            $this->em->flush();
            header('location: /BookStore/listCategories');
        }
        catch (OptimisticLockException $e)
        {
            header('location: /BookStore/listCategories');
        }
    }
}