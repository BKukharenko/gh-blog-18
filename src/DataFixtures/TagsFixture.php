<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TagsFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

      $this->loadTags($manager);

    }

  public function getTags(): array
  {
    return [
      'Dog',
      'Cat',
      'Elephant',
      'Rhino',
      'Wolf',
      'Landscape',
      'Wildlife',
      'Weather',
      'Sun',
      'Rain',
      'Building',
      'Urban',
      'Street',
      'Cherkasy',
      'Kiev',
      'Dnipro',
      'Kharkiv',
      'Lviv',
      'Blockchain',
      'Cloud-technology',
      'Software',
      'Information',
      'Science',
      'PHP',
      'JavaScript',
      'Symfony',
      'Ruby',
      'Backend',
    ];
  }

  private function loadTags(ObjectManager $manager)
  {
    foreach ($this->getTags() as $index => $name) {
      $tag = new Tag();
      $tag->setName($name);
      $manager->persist($tag);
      $this->addReference('tag-' . $name, $tag);
    }

    $manager->flush();
  }
}
