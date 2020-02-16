<?php


namespace App\ElasticaBundle\Repository;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested;
use FOS\ElasticaBundle\Repository;

class RecipeRepository extends Repository
{
    public function findByIngredients (string $search)
    {
        $query = new Query();

        $outerBool = new BoolQuery();
        $outerBool->addShould(new Match('name', $search));
        $outerBool->addShould(new Match('description', $search));

        $innerBool = new BoolQuery();
        $innerBool->addShould(
            new Match('ingredients.name', $search)
        );

        $nested = new Nested();
        $nested->setPath('ingredients');
        $nested->setQuery($innerBool);

        $outerBool->addShould($nested);

        $query->setQuery($outerBool);

        return $this->find($query);
    }
}