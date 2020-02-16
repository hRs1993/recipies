<?php


namespace App\Tests\Services;


use App\Services\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testSlugify()
    {
        $testCases = [
          'some value' => 'some-value',
          'some/value' => 'some-value'
        ];
        $sluggger = new Slugger();

        foreach ($testCases as $input => $output)
        {
            $this->assertEquals($output, $sluggger->slugify($input));
        }
    }

    public function testUnslug()
    {
        $testCases = [
            'some-value' => 'some value',
            'some--value' => 'some value'
        ];
        $sluggger = new Slugger();

        foreach ($testCases as $input => $output)
        {
            $this->assertEquals($output, $sluggger->unslug($input));
        }
    }
}