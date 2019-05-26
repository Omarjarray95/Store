<?php

namespace App\Controller;

use App\Model\Product as Product;
use App\Model\Promotion as Promotion;
use Doctrine\ORM\OptimisticLockException;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ProductController extends Controller
{
    public function RenderAdd()
    {
        session_start();

        $categories = $this->em->getRepository('App\Model\Category')->findBy([], ['name' => 'ASC']);

        echo $this->twig->render('addProduct.html.twig',
            ['categories' => $categories,
                'error_message' => '',
                'loggedIn' => $_SESSION['loggedIn'],
                    'cart' => $_SESSION['cart']]);
    }

    public function Add()
    {
        session_start();

        $categories = $this->em->getRepository('App\Model\Category')->findBy([], ['name' => 'ASC']);

        if (strlen($_POST['designation']) > 0)
        {
            $product = new Product();
            $product->setDesignation($_POST['designation']);
            $product->setQuantity($_POST['quantity']);
            $product->setPrice($_POST['price']);
            if (isset($_POST['available']))
            {
                $product->setAvailable(true);
            }
            if (strlen($_POST['category']) > 0)
            {
                $category = $this->em->getRepository('App\Model\Category')->find($_POST['category']);
                $product->setCategory($category);
            }
            if (strlen($_FILES['photo']['tmp_name']) > 0)
            {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], 'C:\\wamp\\www\\BookStore\\src\\public\\images\\'
                    .$_FILES['photo']['name']))
                {
                    $product->setPhoto($_FILES['photo']['name']);
                }
                else
                {
                    echo $this->twig->render('addProduct.html.twig',
                        ['categories' => $categories,
                            'error_message' => 'Could Not Upload Your Photo ...',
                            'loggedIn' => $_SESSION['loggedIn'],
                            'cart' => $_SESSION['cart']]);
                }
            }
            $this->em->persist($product);
            try
            {
                $this->em->flush();
                header('location: /BookStore/home');
            }
            catch (OptimisticLockException $e)
            {
                echo $this->twig->render('addProduct.html.twig',
                    ['categories' => $categories,
                        'error_message' => 'Server Problem ...',
                        'loggedIn' => $_SESSION['loggedIn'],
                        'cart' => $_SESSION['cart']]);
            }
        }
        else
        {
            echo $this->twig->render('addProduct.html.twig',
                ['categories' => $categories,
                    'error_message' => 'Invalid Information',
                    'loggedIn' => $_SESSION['loggedIn'],
                    'cart' => $_SESSION['cart']]);
        }
    }

    public function RenderUpdate($id)
    {
        session_start();

        $product = $this->em->getRepository('App\Model\Product')->find($id);

        $categories = $this->em->getRepository('App\Model\Category')->findBy([], ['name' => 'ASC']);

        echo $this->twig->render('updateProduct.html.twig',
            ['categories' => $categories,
                'product' => $product,
                'error_message' => '',
                'loggedIn' => $_SESSION['loggedIn'],
                'cart' => $_SESSION['cart']]);
    }

    public function Update($id)
    {
        session_start();

        $categories = $this->em->getRepository('App\Model\Category')->findBy([], ['name' => 'ASC']);

        if (strlen($_POST['designation']) > 0)
        {
            $product = $this->em->getRepository('App\Model\Product')->find($id);
            $product->setDesignation($_POST['designation']);
            $product->setQuantity($_POST['quantity']);
            $product->setPrice($_POST['price']);
            if (isset($_POST['available']))
            {
                $product->setAvailable(true);
            }
            if (strlen($_POST['category']) > 0)
            {
                $category = $this->em->getRepository('App\Model\Category')->find($_POST['category']);
                $product->setCategory($category);
            }
            if (strlen($_FILES['photo']['tmp_name']) > 0)
            {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], 'C:\\wamp\\www\\BookStore\\src\\public\\images\\'
                    .$_FILES['photo']['name']))
                {
                    $product->setPhoto($_FILES['photo']['name']);
                }
                else
                {
                    echo $this->twig->render('updateProduct.html.twig',
                        ['categories' => $categories,
                            'error_message' => 'Could Not Upload Your Photo ...',
                            'loggedIn' => $_SESSION['loggedIn'],
                            'cart' => $_SESSION['cart']]);
                }
            }
            $this->em->persist($product);
            try
            {
                $this->em->flush();
                header('location: /BookStore/home');
            }
            catch (OptimisticLockException $e)
            {
                echo $this->twig->render('updateProduct.html.twig',
                    ['categories' => $categories,
                        'error_message' => 'Server Problem ...',
                        'loggedIn' => $_SESSION['loggedIn'],
                        'cart' => $_SESSION['cart']]);
            }
        }
        else
        {
            echo $this->twig->render('updateProduct.html.twig',
                ['categories' => $categories,
                    'error_message' => 'Invalid Information',
                    'loggedIn' => $_SESSION['loggedIn'],
                    'cart' => $_SESSION['cart']]);
        }
    }

    public function Delete($id)
    {
        session_start();

        $product = $this->em->getRepository('App\Model\Product')->find($id);
        $this->em->remove($product);
        try
        {
            $this->em->flush();
            header('location: /BookStore/home');
        }
        catch (OptimisticLockException $e)
        {
            header('location: /BookStore/home');
        }
    }

    public function RenderAddPromotion($id)
    {
        session_start();

        echo $this->twig->render('addPromotion.html.twig',
            ['id' => $id,
                'error_message' => '',
                'loggedIn' => $_SESSION['loggedIn'],
                'cart' => $_SESSION['cart']]);
    }

    public function AddPromotion($id)
    {
        session_start();

        if (($_POST['promotion'] > 0) && isset($_POST['startingDate']) && isset($_POST['endDate']))
        {
            $product = $this->em->getRepository('App\Model\Product')->find($id);
            $promotion = new Promotion();
            $promotion->setPromotion($_POST['promotion']);
            $promotion->setStartingDate(new \DateTime($_POST['startingDate']));
            $promotion->setEndDate(new \DateTime($_POST['endDate']));
            $this->em->persist($promotion);
            $product->setInPromotion($promotion);
            $this->em->merge($product);
            try
            {
                $this->em->flush();
                header('location: /BookStore/home');
            }
            catch (OptimisticLockException $e)
            {
                echo $this->twig->render('addPromotion.html.twig',
                    ['error_message' => 'Server Problem ...',
                        'loggedIn' => $_SESSION['loggedIn'],
                        'cart' => $_SESSION['cart']]);
            }
        }
        else
        {
            echo $this->twig->render('addPromotion.html.twig',
                ['error_message' => 'Invalid Information',
                    'loggedIn' => $_SESSION['loggedIn'],
                    'cart' => $_SESSION['cart']]);
        }
    }

    public function DeletePromotion($id)
    {
        session_start();

        $product = $this->em->getRepository('App\Model\Product')->find($id);
        $product->setInPromotion(null);
        $this->em->merge($product);
        try
        {
            $this->em->flush();
            header('location: /BookStore/home');
        }
        catch (OptimisticLockException $e)
        {
            header('location: /BookStore/home');
        }
    }

    public function ListSearch()
    {
        session_start();

        if (strlen($_POST['product']))
        {
            $products = $this->em->getRepository('App\Model\Product')
                ->findBy([], ['designation' => 'ASC']);

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
                            header('location: /BookStore/home');
                        }
                    }
                }
            }

            $products = $this->em->createQuery("SELECT p FROM App\Model\Product p WHERE p.designation LIKE :keyword".
            " ORDER BY p.designation")
                ->setParameter('keyword', '%'.$_POST['product'].'%')->getResult();

            echo $this->twig->render('searchProducts.html.twig',
                ['products' => $products,
                    'cart' => $_SESSION['cart'],
                    'loggedIn' => $_SESSION['loggedIn']]);
        }
        else
        {
            header('location: /BookStore/home');
        }
    }

    public function ListPromotedProducts()
    {
        session_start();

        $products = $this->em->getRepository('App\Model\Product')
            ->findBy([], ['designation' => 'ASC']);

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
                        header('location: /BookStore/home');
                    }
                }
            }
        }

        $qb = $this->em->createQueryBuilder();
        $products = $qb->select('product')->from('App\Model\Product','product')
            ->where($qb->expr()->isNotNull('product.inPromotion'))->getQuery()->getResult();

        echo $this->twig->render('searchProducts.html.twig',
            ['products' => $products,
                'loggedIn' => $_SESSION['loggedIn'],
                'cart' => $_SESSION['cart']]);
    }

    public function ListCategoryProducts($id)
    {
        session_start();

        $products = $this->em->getRepository('App\Model\Product')
            ->findBy([], ['designation' => 'ASC']);

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
                        header('location: /BookStore/home');
                    }
                }
            }
        }

        $products = $this->em->createQuery("SELECT p FROM App\Model\Product p WHERE p.category = :code".
            " ORDER BY p.designation")
            ->setParameter('code', $id)->getResult();

        echo $this->twig->render('searchProducts.html.twig',
            ['products' => $products,
                'loggedIn' => $_SESSION['loggedIn'],
                'cart' => $_SESSION['cart']]);
    }

    public function RenderAddToCart($id)
    {
        session_start();

        $product = $this->em->getRepository('App\Model\Product')->find($id);

        echo $this->twig->render('addToCart.html.twig',
            ['product' => $product,
                'error_message' => '',
                'loggedIn' => $_SESSION['loggedIn'],
                'cart' => $_SESSION['cart']]);
    }

    public function AddToCart($id)
    {
        session_start();

        $prod = $this->em->getRepository('App\Model\Product')->find($id);

        if ((intval($_POST['quantity']) > 0) && (intval($_POST['quantity']) < 10))
        {
            if (!isset($_SESSION['cart']) && !isset($_SESSION['products']))
            {
                $_SESSION['cart'] = 0;
                $_SESSION['products'] = [];
            }

            foreach ($_SESSION['products'] as $k => $val)
            {
                if (json_decode($val)->id == $id)
                {
                    unset($_SESSION['products'][$k]);
                }
            }

            $product = null;
            $product->id = $id;
            $product->quantity = intval($_POST['quantity']);
            $_SESSION['cart'] = array_push($_SESSION['products'], json_encode($product));
            header('location: /BookStore/home');
        }
        else
        {
            $product = $this->em->getRepository('App\Model\Product')->find($id);

            echo $this->twig->render('addToCart.html.twig',
                ['product' => $product,
                    'error_message' => 'Invalid Quantity (Must Be Less Than 10 Units)',
                    'loggedIn' => $_SESSION['loggedIn'],
                    'cart' => $_SESSION['cart']]);
        }
    }

    public function ResetCart()
    {
        session_start();

        if (!isset($_SESSION['cart']) && !isset($_SESSION['products']))
        {
            $_SESSION['cart'] = 0;
            $_SESSION['products'] = [];
        }

        $_SESSION['cart'] = 0;
        $_SESSION['products'] = [];

        header('location: /BookStore/home');
    }

    public function ListCart()
    {
        session_start();
        $products = [];
        $total = 0;

        foreach ($_SESSION['products'] as $k => $val)
        {
            $product = $this->em->getRepository('App\Model\Product')->find(intval(json_decode($val)->id));
            $total = $total + $product->getPrice() * json_decode($val)->quantity;
            array_push($products, array($product, json_decode($val)->quantity,
                $product->getPrice() * json_decode($val)->quantity));
        }

        echo $this->twig->render('listCart.html.twig',
            ['products' => $products,
                'total' => $total,
                'loggedIn' => $_SESSION['loggedIn'],
                'cart' => $_SESSION['cart']]);
    }

    public function DeleteFromCart($id)
    {
        session_start();

        foreach ($_SESSION['products'] as $k => $val)
        {
            if (json_decode($val)->id == $id)
            {
                unset($_SESSION['products'][$k]);
            }
        }

        $_SESSION['cart'] = count($_SESSION['products']);

        header('location: /BookStore/listCart');
    }

    public function Order($id, $quantity)
    {
        session_start();

        $mail = new PHPMailer(true);

        try
        {
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'Omar.jarray@esprit.tn';                     // SMTP username
            $mail->Password   = 'loulou95';                               // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('Omar.jarray@esprit.tn');
            $mail->addAddress($_POST['email']);

            $random = mt_rand(100000, 999999);

            $mail->isHTML(false);                                  // Set email format to HTML
            $mail->Subject = 'Order Success';
            $mail->Body    = "Your Order Has Been Saved, Here Is Your Confirmation Code : ".$random;

            $mail->send();

            $product = $this->em->getRepository('App\Model\Product')->find($id);
            $product->setQuantity($product->getQuantity() - $quantity);
            $this->em->merge($product);
            try
            {
                $this->em->flush();
                header('location: /BookStore/home');
            }
            catch (OptimisticLockException $e)
            {
                echo "Server Problem ...";
            }
        }
        catch (Exception $e)
        {
            echo "Mail Could Not Be Sent. Error: {$mail->ErrorInfo}";
        }
    }
}