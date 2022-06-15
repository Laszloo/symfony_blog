<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController {


    /**
     * @Route("/admin/post", methods={"GET", "POST"})
     */
    public function new(Request $request) {

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Post
             */
            $data = $form->getData();

            /**
             * @var UploadedFile
             */
            $image = $form["image"]->getData();
            $newname = time().".".$image->getClientOriginalExtension();
            $image->move( $this->getParameter("kernel.project_dir")."/public/upload/", $newname);

            $data->setImage($newname);
            $data->setUrl($this->formatUrl($data->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return $this->redirect("/admin");
        }

        return $this->render("/admin/post-new.html.twig", [
            "form" => $form->createView(),
            "authors" => $this->getDoctrine()->getRepository(Author::class)->findAll()
        ]);
    }



    /**
     * @Route("/admin/post/edit/{id<\d>}", methods={"GET", "POST"})
     */
    public function edit(Post $post, Request $request, int $id) {

        
        $form = $this->createForm(AuthorType::class, $post, [
            "action" => "/admin/author/edit/".$id,
            "method" => "POST"
        ]);

        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($data);
            $em->flush();
            return $this->redirect("/admin");
        } 

        return $this->render("/admin/author-edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/post/delete/{id}", methods={"POST"})
     */
    public function delete(Request $request, int $id) {
        /**
         * @var Post
         */
        if ($target = $this->getDoctrine()->getRepository(Post::class)->find($id)) {
            @unlink($this->getParameter("kernel.project_dir")."/public/upload/".$target->getImage());
            $em = $this->getDoctrine()->getManager();
            $em->remove($target);
            $em->flush();
        }
        return $this->redirect("/admin");
    }


    private function formatUrl($string, $separator = '-')
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array('&' => 'and', "'" => '', 'ő' => 'o');
        $string = mb_strtolower(trim($string), 'UTF-8');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
        $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        $string = trim($string, '-');
        return $string;
    }
}
