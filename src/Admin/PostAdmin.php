<?php

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof Post
          ? $object->getTitle()
          : 'Post';
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
          ->add('title', TextType::class)
          ->add('body', TextareaType::class, [
              'attr' => ['class' => 'ckeditor'],
          ])
          ->end()
          ->add('category', ModelType::class, [
              'class' => Category::class,
              'property' => 'name',
          ])
          ->end()
          ->add('tags', ModelAutocompleteType::class, [
              'property' => 'name',
              'multiple' => true,
          ])
          ->end()
          ->add('author', EntityType::class, [
              'class' => User::class,
              'choice_label' => 'fullname',
          ])
          ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
          ->add('title')
          ->add('category', null, [], EntityType::class, [
              'class' => Category::class,
              'choice_label' => 'name',
          ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
          ->addIdentifier('title')
          ->add('isPublished');
    }
}
