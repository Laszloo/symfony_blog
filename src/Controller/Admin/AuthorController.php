<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController {


    /**
     * @Route("/admin/author", methods={"GET", "POST"})
     */
    public function new(Request $request) {

        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return $this->redirect("/admin");
        }

        return $this->render("/admin/author-new.html.twig", [
            "form" => $form->createView()
        ]);
    }



    /**
     * @Route("/admin/author/edit/{id<\d>}", methods={"GET", "POST"})
     */
    public function edit(Author $author, Request $request, int $id) {

        
        $form = $this->createForm(AuthorType::class, $author, [
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
     * @Route("/admin/author/delete/{id}", methods={"POST"})
     */
    public function delete(Request $request, int $id) {
        if ($target = $this->getDoctrine()->getRepository(Author::class)->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($target);
            $em->flush();
        }
        return $this->redirect("/admin");
    }
}
