<?php 

namespace App\Controller\Frontend;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(){
        return $this->render("/frontend/index.html.twig", [
            "posts" => $this->getDoctrine()->getRepository(Post::class)->findAll()
        ]);
    }

    /**
     * @Route("/post/{url}", methods={"GET"})
     */
    public function post(Request $request, Post $post){
        
        return $this->render("/frontend/post.html.twig", [
            "post" => $post
        ]);
    }
}


?>