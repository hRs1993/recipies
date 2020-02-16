<?php


namespace App\Services;


class Slugger
{
    /**
     * Transforms strings "some value" to "some-value"
     * @param string $name
     * @return mixed
     */
    public function slugify(string $name)
    {
        return preg_replace('~[^\pL\d]+~u', '-', $name);
    }

    public function unslug(string $slugged)
    {
        return preg_replace('!\s+!', ' ', str_replace("-", " ", $slugged));
    }
}