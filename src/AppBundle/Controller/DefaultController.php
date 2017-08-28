<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show")
     * @Method("GET")
     */
    public function showArticleAction(Article $article)
    {
        return $this->render('default/show_article.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/article/new", name="article_create")
     * @Method({"GET","POST"})
     */
    public function createArticleAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/article/{id}/delete", name="article_delete")
     * @Method("GET")
     */
    public function deleteArticleAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        if ($article->getAuthor() == $this->getUser()) {
            try {
                $em->remove($article);
                $em->flush();
                return $this->redirectToRoute('homepage');
            }
            catch (\Exception $e) {
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/article/{id}/edit", name="article_edit")
     * @Method({"GET","POST"})
     */
    public function editArticleAction(Article $article)
    {
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }
}
