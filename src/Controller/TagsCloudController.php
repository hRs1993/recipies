<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\TagsCloudManager;

class TagsCloudController extends AbstractController
{
    /**
     * @Route("/tags/cloud", name="tags_cloud")
     */
    public function cloud(TagsCloudManager $tagsCloudManager)
    {
        $tags = $tagsCloudManager->retrieveMostPopular();

        return $this->render('tags_cloud/cloud.html.twig', [
            'tags' => $tags
        ]);
    }
}
