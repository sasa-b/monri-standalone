<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:37
 */

namespace SasaB\Monri\Client\Response;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Webmozart\Assert\Assert;

final class Deserializer
{
    private Serializer $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [new ObjectNormalizer(null, new KebabCaseToCamelCaseConverter())],
            [new XmlEncoder(), new JsonEncoder()]
        );
    }

    private function deserialize(string $data, string $class, string $format = 'xml')
    {
        Assert::classExists($class, 'Invalid class provided. %s does not exist');
        Assert::inArray($format, ['json', 'xml'], 'Invalid format provided. Expected xml or json. Got %s');

        return $this->serializer->deserialize($data, $class, $format, ['xml_root_node_name' => 'transaction']);
    }

    public function deserializeXml(string $data): Xml
    {
        $data = preg_replace('/type="\w+"/', '', $data);

        return $this->deserialize($data, Xml::class, 'xml');
    }
}
