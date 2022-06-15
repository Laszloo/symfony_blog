<?php 

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", methods={"GET"})
     */
    public function index(){
        return $this->render("/frontend/contact.html.twig");
    }

    /**
     * @Route("/contact", methods={"POST"})
     */
    public function post(){
        return $this->render("/frontend/contact.html.twig");
    }
}


?>