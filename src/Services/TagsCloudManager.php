<?php


namespace App\Services;


use App\Repository\TagRepository;

class TagsCloudManager
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param int $amount
     * @return array
     */
    public function retrieveMostPopular($amount = 10)
    {
        return $this->tagRepository->findByPopularity($amount);
    }
}