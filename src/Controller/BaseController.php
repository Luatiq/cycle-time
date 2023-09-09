<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class BaseController extends AbstractController
{
    public function getData(Request $request): ParameterBag
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            return new ParameterBag(
                json_decode($request->getContent(), true)
            );
        }

        return $request->request;
    }

    /**
     * @throws ExceptionInterface
     */
    public function serializeEntity(
        mixed $data,
        array $ignoredAttributes = []
    ): array
    {
        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];

        $serializer = new Serializer($normalizers, $encoders);

        $context = new ObjectNormalizerContextBuilder();
        $context = $context
            ->withIgnoredAttributes($ignoredAttributes)
            ->toArray();

        return $serializer->normalize(
            $data,
            JsonEncoder::FORMAT,
            $context
        );
    }
}