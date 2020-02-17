<?php


namespace App\Form\DataTransformers;


use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class TagsDataTransformer implements DataTransformerInterface
{
    const SEPARATOR = ', ';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($tags)
    {
        if (!$tags || !count($tags)) return '';

        $tagNames = array_reduce($tags, function ($storage, $tag) {
            $storage[] = $tag->getName();
            return $storage;
        });

        return implode(self::SEPARATOR, $tagNames);
    }

    public function reverseTransform($tagNames)
    {
        if (!$tagNames) return [];

        $tagsAdded = false;
        $tags = explode(',', $tagNames);
        array_walk($tags, function (&$tagName) use ($tagsAdded) {
           $tagName = trim($tagName);
           $tag = $this->entityManager->getRepository(Tag::class)
                                      ->findOneBy(['name' => $tagName]);
           if (!$tag) {
               $tag = new Tag();
               $tag->setName($tagName);
               $this->entityManager->persist($tag);
               $tagsAdded = true;
           }

           $tagName = $tag;
        });

        if ($tagsAdded) {
            $this->entityManager->flush();
        }

        return $tags;
    }
}