<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\ShortenUrl;
use AppBundle\Service\ShortUrlGenerator;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{

    /**
     * @Route("/", name="apitest")
     */
    public function indexAction(Request $request)
    {
        $response = array();
        $repository = $this->getDoctrine()->getRepository(\AppBundle\Entity\ShortenUrl::class);

        $shorts = $repository->createQueryBuilder('c')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $logger = $this->get('logger');
        $logger->info(var_export($shorts, true));

        return $this->json(array('result' => $shorts));
    }

    /**
     * @Route("/check/{shortUrl}", name="check")
     * @Method({"GET"})
     */
    public function checkAction($shortUrl)
    {
        $response = array();
        $repository = $this->getDoctrine()->getRepository(\AppBundle\Entity\ShortenUrl::class);
        $shortUrlGenerator = $this->get(ShortUrlGenerator::class);

        if ($shortUrlGenerator->validateChars($shortUrl) === false) {
            $response = array(
                'status' => 'error',
                'message' => 'short url chars is not valid'
            );
        } elseif ($shortUrlGenerator->validateLength($shortUrl) === false) {
            $response = array(
                'status' => 'error',
                'message' => 'short url length is not valid'
            );
        } else {
            $result = $repository->isShortUrlExists($shortUrl);

            $response = array(
                'status' => 'success',
                'busy' => $result
            );
        }

        return $this->json($response);
    }

    /**
     * @Route("/create", name="create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $response = array();
        $repository = $this->getDoctrine()->getRepository(\AppBundle\Entity\ShortenUrl::class);
        $fullUrl = $request->request->get('full_url');
        $shortUrl = $request->request->get('short_url');

        if (is_null($fullUrl)) {
            $response = array(
                'status' => 'error',
                'message' => 'full url is missing'
            );
        } elseif (!filter_var(idn_to_ascii($fullUrl), FILTER_VALIDATE_URL)) {
            $response = array(
                'status' => 'error',
                'message' => 'full url is not valid'
            );
        }

        if (!empty($response)) {
            return $this->json($response);
        }

        if ($shortUrl) {
            $shortUrlGenerator = $this->get(ShortUrlGenerator::class);
            if ($shortUrlGenerator->validateChars($shortUrl) === false) {
                $response = array(
                    'status' => 'error',
                    'message' => 'short url chars is not valid'
                );
            } elseif ($shortUrlGenerator->validateLength($shortUrl) === false) {
                $response = array(
                    'status' => 'error',
                    'message' => 'short url length is not valid'
                );
            } elseif($repository->isShortUrlExists($shortUrl)) {
                $response = array(
                    'status' => 'error',
                    'message' => 'short url is busy'
                );
            }
        }

        if (!empty($response)) {
            return $this->json($response);
        }

        if (is_null($shortUrl)) {
            $shortUrlGenerator = $this->get(ShortUrlGenerator::class);
            do {
                $shortUrl = $shortUrlGenerator->generate();
            } while ($repository->isShortUrlExists($shortUrl));
        }

        $em = $this->getDoctrine()->getManager();

        $shortenUrl = new ShortenUrl();
        $shortenUrl->setShortUrl($shortUrl);
        $shortenUrl->setOriginalUrl($fullUrl);
        $shortenUrl->setCreateDate(new \DateTime());

        $em->persist($shortenUrl);
        $em->flush();

        $response = array(
            'status' => 'success',
            'short_url' => $shortUrl,
            'message' => 'short url created sucessfully'
        );

        return $this->json($response);
    }
}