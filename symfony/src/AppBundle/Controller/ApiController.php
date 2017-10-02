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

    //TODO: getParamsAction, getDetailAction

    /**
     * @Route("/params", name="params")
     * @Method({"GET"})
     */
    public function paramsAction()
    {
        return $this->json(array(
                'length' => $this->getParameter('short_url.length'),
                'use_digits' => $this->getParameter('short_url.use_digits'),
            ));
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
                'message' => 'short URL chars is not valid'
            );
        } elseif ($shortUrlGenerator->validateLength($shortUrl) === false) {
            $response = array(
                'status' => 'error',
                'message' => 'short URL length is not valid'
            );
        } else {
            $result = $repository->isShortUrlExists($shortUrl);

            $response = array(
                'status' => $result ? 'success' : 'error',
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
        $shortUrlGenerator = $this->get(ShortUrlGenerator::class);
        $requestContent = $request->getContent();
        $requestContent = json_decode($requestContent, true);
        $fullUrl = isset($requestContent['fullUrl']) ? $requestContent['fullUrl'] : null;
        $shortUrl = isset($requestContent['shortUrl']) ? $requestContent['shortUrl'] : null;

        if (is_null($fullUrl)) {
            $response = array(
                'status' => 'error',
                'message' => 'your URL is missing'
            );
        } elseif ($shortUrlGenerator->validateUrl($fullUrl) === false) {
            $response = array(
                'status' => 'error',
                'message' => 'your URL is not valid'
            );
        }

        if (!empty($response)) {
            return $this->json($response);
        }

        if ($shortUrl) {
            if ($shortUrlGenerator->validateChars($shortUrl) === false) {
                $response = array(
                    'status' => 'error',
                    'message' => 'short URL chars is not valid'
                );
            } elseif ($shortUrlGenerator->validateLength($shortUrl) === false) {
                $response = array(
                    'status' => 'error',
                    'message' => 'short URL length is not valid'
                );
            } elseif($repository->isShortUrlExists($shortUrl)) {
                $response = array(
                    'status' => 'error',
                    'message' => 'short URL is busy'
                );
            }
        }

        if (!empty($response)) {
            return $this->json($response);
        }

        if (is_null($shortUrl)) {
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
            'message' => 'short URL created sucessfully'
        );

        return $this->json($response);
    }
}