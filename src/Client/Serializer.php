<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:37
 */

namespace Sco\Monri\Client;

use Sco\Monri\Client\Response\Xml;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Webmozart\Assert\Assert;

final class Serializer
{
    private SymfonySerializer $serializer;

    public function __construct()
    {
        $this->serializer = new SymfonySerializer(
            [new ObjectNormalizer(null, new KebabCaseToCamelCaseConverter())],
            [new XmlEncoder(), new JsonEncoder()]
        );
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     * @return T
     */
    private function deserialize(string $data, string $class, string $format, array $context): mixed
    {
        Assert::classExists($class, 'Invalid class provided. %s does not exist');
        Assert::inArray($format, ['json', 'xml'], 'Invalid format provided. Expected xml or json. Got %s');

        return $this->serializer->deserialize($data, $class, $format, $context);
    }

    public function deserializeXml(string $data, array $context = []): Xml
    {
        $data = (string) preg_replace('/type="\w+"/', '', $data);

        return $this->deserialize($data, Xml::class, 'xml', $context);
    }

    public function serializeXml(Xml $xml, array $context = []): string
    {
        return $this->serializer->serialize($xml->getBody(), 'xml', $context);
    }
}
