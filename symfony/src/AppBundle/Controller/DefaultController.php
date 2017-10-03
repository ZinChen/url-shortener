<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/{shortUrl}", name="redirect_page", requirements={"shortUrl": ".{5,}"})
     */
    public function redirectAction($shortUrl)
    {
        $response = array();
        $repository = $this->getDoctrine()->getRepository(\AppBundle\Entity\ShortenUrl::class);
        $shortUrlEntity = $repository->getShortUrlInfo($shortUrl);

        $logger = $this->get('logger');

        if ($shortUrlEntity) {
            $shortUrlEntity->incrementUseCount();
            $logger->info(
                'Redirecting to url ' . $shortUrlEntity->getOriginalUrl() .
                ' from short url ' . $shortUrlEntity->getShortUrl() .
                ' ' . $shortUrlEntity->getUseCount() . ' time.'
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($shortUrlEntity);
            $em->flush();
        } else {
            throw $this->createNotFoundException('The url does not exist');
        }

        return $this->redirect($shortUrlEntity->getOriginalUrl());
    }
}
