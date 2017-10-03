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
     * Get application parameters
     *
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
     * Get short url details
     *
     * @Route("/info/{shortUrl}", name="info")
     * @Method({"GET"})
     */
    public function infoAction($shortUrl)
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
            $info = $repository->getShortUrlInfo($shortUrl);
            $response = array(
                'status' => 'success',
                'busy' => count($info),
                'fullUrl' => $info->getOriginalUrl(),
                'shortUrl' => $info->getShortUrl(),
                'useCount' => $info->getUseCount(),
                'createDate' => $info->getCreateDate()->format('Y-m-d')
            );

            $logger = $this->get('logger');
            $logger->info(
                'Getting info for short URL ' . $info->getShortUrl() .
                ' with full URL' . $info->getOriginalUrl()
            );
        }

        return $this->json($response);
    }

    /**
     * Increment short url use counter
     *
     * @Route("/used/{shortUrl}", name="used")
     * @Method({"GET"})
     */
    public function usedAction($shortUrl)
    {
        $response = array();
        $repository = $this->getDoctrine()->getRepository(\AppBundle\Entity\ShortenUrl::class);
        $shortUrlEntity = $repository->getShortUrlInfo($shortUrl);
        if ($shortUrlEntity) {
            $shortUrlEntity->incrementUseCount();

            $logger = $this->get('logger');
            $logger->info(
                'Redirecting to URL ' . $shortUrlEntity->getOriginalUrl() .
                ' with short URL ' . $shortUrlEntity->getShortUrl() .
                ' ' . $shortUrlEntity->getUseCount() . ' time.'
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($shortUrlEntity);
            $em->flush();

            $response = array(
                'status' => 'success',
                'useCount' => $shortUrlEntity->getUseCount()
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'short URL not found'
            );
        }

        return $this->json($response);
    }

    /**
     * Create new short url
     *
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
            } elseif($repository->getShortUrlInfo($shortUrl)) {
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
            } while ($repository->getShortUrlInfo($shortUrl));
        }

        $em = $this->getDoctrine()->getManager();

        $shortenUrl = new ShortenUrl();
        $shortenUrl->setShortUrl($shortUrl);
        $shortenUrl->setOriginalUrl($fullUrl);
        $shortenUrl->setCreateDate(new \DateTime());

        $em->persist($shortenUrl);
        $em->flush();

        $logger = $this->get('logger');
        $logger->info(
            'Successfully created short URL ' . $shortenUrl->getShortUrl() .
            'for URL ' . $shortenUrl->getOriginalUrl()
        );

        $response = array(
            'status' => 'success',
            'short_url' => $shortUrl,
            'message' => 'short URL created successfully'
        );

        return $this->json($response);
    }
}