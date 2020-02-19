<?php


namespace App\Services;


use App\Repository\TagRepository;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class TagsCloudManager
{
    /**
     * @var TagRepository
     */
    private $tagRepository;
    /**
     * @var CacheInterface
     */
    private $cache;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(TagRepository $tagRepository, LoggerInterface $logger, TagAwareCacheInterface $cache)
    {
        $this->tagRepository = $tagRepository;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param int $amount
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function retrieveMostPopular($amount = 10)
    {
        try {
            $tags = $this->cache->get("tags.most_popular.{$amount}", function (ItemInterface $item) use ($amount) {
                $item->expiresAfter(3600);
                $item->tag(['tags', 'tags.most_popular']);

                return $this->tagRepository->findByPopularity($amount);
            });
        } catch (\Exception $exception) {
            $this->logger->alert($exception->getMessage());

            return $this->tagRepository->findByPopularity($amount);
        }

        return $tags;
    }

    public function clearCache()
    {
        $this->cache->invalidateTags(['tags.most_popular']);
    }
}