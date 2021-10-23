<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Faker\Generator;

trait Fakable
{
    private ?Generator $faker = null;

    protected function faker(): Generator
    {
        if (null === $this->faker) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
