<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PostsFixture extends Fixture {

  public function load(ObjectManager $manager) {
    $this->loadPosts($manager);
  }

  private function loadPosts (ObjectManager $manager) {
    foreach ($this->getPostData() as [$title, $content, $category]) {
      $post = new Post();
      $post->setTitle($title);
      $post->setBody($content);
      $post->setCategory(...$category);

      $manager->persist($post);
    }

    $manager->flush();
  }

  public function getPostTtile() {
    return [
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec arcu',
      'Donec pulvinar dapibus nisl, sit amet iaculis nibh mattis non.',
      'Ut sit amet leo eget sem accumsan feugiat in non.',
      'Suspendisse tempus enim nisl, at bibendum nunc dignissim id. In.',
      'Curabitur laoreet elit non libero vestibulum, at mattis ex vehicula.',
      'Vivamus vitae bibendum sem, dictum ornare mi.',
      'Pellentesque eu iaculis lacus. Nunc magna velit, semper id.',
      'Pellentesque sed velit accumsan, posuere sapien et, accumsan mauris.',
      'Integer in diam non urna suscipit iaculis. Mauris.',
      'Vestibulum finibus vestibulum diam nec accumsan. Nullam mollis.',
      'Slony napali na cheloveka i nichego ne sdelali',
      'Donec at diam eget turpis hendrerit elementum. Pellentesque.',
      'Sed blandit consequat ante eu gravida. Sed semper arcu metus, ac tincidunt.',
    ];
  }

  public function getPostContent() {
    $postContent = [
      'Fusce accumsan ante at interdum molestie. Donec elementum dictum nisi, ut 
      ornare magna. Nam in nisl sollicitudin, lacinia magna gravida, fringilla quam. 
      Nam eget mi sodales, pretium lectus pretium, pretium ante. Curabitur ultrices 
      vestibulum tortor, eget tempus nulla semper non. Aenean augue purus, accumsan 
      nec facilisis sed, efficitur quis quam. Proin vel tempus erat, in ultricies diam. 
      Phasellus ornare tortor augue, mollis tempor est commodo eu. Donec finibus maximus 
      quam, id dignissim ante pharetra ut. Aenean posuere egestas ligula, at rhoncus nulla 
      euismod ut. Nunc quis risus vitae justo viverra laoreet eget et neque. Quisque tristique 
      ante eget lectus dignissim, in cursus ex egestas. Etiam vel elementum risus, nec congue mi. 
      Curabitur pellentesque molestie eros, vel auctor lectus aliquam ut. Maecenas at felis malesuada, 
      hendrerit turpis at, elementum orci. Pellentesque nec libero leo.',

      'Eu turpis egestas pretium aenean. Nulla posuere sollicitudin aliquam ultrices sagittis. 
      Aliquam faucibus purus in massa. Magna eget est lorem ipsum. Nisi lacus sed viverra tellus 
      in. Tristique sollicitudin nibh sit amet commodo nulla. Amet massa vitae tortor condimentum 
      lacinia quis vel eros donec. Maecenas volutpat blandit aliquam etiam erat velit scelerisque. 
      Vel eros donec ac odio tempor orci. Elit ullamcorper dignissim cras tincidunt lobortis feugiat. 
      Volutpat est velit egestas dui. Amet volutpat consequat mauris nunc congue nisi vitae suscipit. 
      Consectetur a erat nam at lectus urna duis convallis convallis. Tortor pretium viverra suspendisse 
      potenti nullam ac tortor vitae. Eget est lorem ipsum dolor. Rutrum tellus pellentesque eu tincidunt. 
      Molestie at elementum eu facilisis.',

      'Integer vitae justo eget magna. Suspendisse faucibus interdum posuere lorem ipsum. 
      Sodales ut eu sem integer. Sed euismod nisi porta lorem mollis. Varius duis at consectetur 
      lorem donec massa. Magna etiam tempor orci eu lobortis. Turpis in eu mi bibendum. Sed 
      euismod nisi porta lorem mollis aliquam. Porta lorem mollis aliquam ut porttitor leo. 
      Vestibulum mattis ullamcorper velit sed ullamcorper. Dolor sit amet consectetur adipiscing 
      elit. Ultricies leo integer malesuada nunc vel risus commodo viverra maecenas. Dignissim 
      sodales ut eu sem integer vitae justo. Malesuada pellentesque elit eget gravida cum. Amet 
      nulla facilisi morbi tempus iaculis urna. Diam vulputate ut pharetra sit amet aliquam id 
      diam maecenas. Id cursus metus aliquam eleifend mi in nulla posuere. Interdum velit laoreet 
      id donec ultrices tincidunt.',
    ];

    $randKey = array_rand($postContent);
    return $postContent[$randKey];
  }


  private function getRandomCategory(): array
  {
    $categories = new CategoriesFixture();
    $categoryNames = $categories->getCategories();
    shuffle($categoryNames);
    $chosenCategory = \array_slice($categoryNames, 0, random_int(2, 4));
    return array_map(function ($categoryName) {
      return $this->getReference('category-'.$categoryName);
    }, $chosenCategory);
  }

  private function getPostData()
  {
    $posts = [];
    foreach ($this->getPostTtile() as $i => $title) {
      $posts[] = [
        $title,
        $this->getPostContent(),
        $this->getRandomCategory(),
      ];
    }
    return $posts;
  }
}
