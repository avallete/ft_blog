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
        $error = null;
        $request = $this->container->get('request_stack')->getCurrentRequest();
        if ($article->getAuthor() != $this->getUser())
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        if ( $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            if (empty($request->get('_title')))
                $error = "Title can't be empty";
            if (empty($request->get('_description')))
                $error = "Description can't be empty";
            if ($error == null) {
                try {
                    $article->setTitle($request->get('_title'));
                    $article->setDescription($request->get('_description'));
                    $em->persist($article);
                    $em->flush();
                }
                catch (\Exception $e) {
                    return $this->render('default/edit_article.html.twig', ['article' => $article, 'error' => $e->getMessage()]);
                }
                return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
            }
        }
        return $this->render('default/edit_article.html.twig', ['article' => $article, 'error' => $error]);
    }
}
