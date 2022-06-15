<?php 

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/admin")
     */
    public function index(){

        return $this->render("/admin/index.html.twig", [
            "authors" => $this->getDoctrine()->getRepository(Author::class)->findAll(),
            "posts" => $this->getDoctrine()->getRepository(Post::class)->findAll(),
        ]);
    }
}
