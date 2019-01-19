<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoriesFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
    }

    public function getCategories(): array
    {
        return [
          'Animals',
          'Nature',
          'Architecture',
          'Cities',
          'Technologies',
          'IT',
        ];
    }

    private function loadCategories(ObjectManager $manager)
    {
        foreach ($this->getCategories() as $index => $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $this->addReference('category-' . $name, $category);
        }

        $manager->flush();
    }
}
