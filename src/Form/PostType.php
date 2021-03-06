<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('body', TextareaType::class, [
              'attr' => ['class' => 'ckeditor'],
            ])
            ->add('category', EntityType::class, [
              'class' => Category::class,
              'choice_label' => 'name',
              'multiple' => false,
            ])
            ->add('tags', EntityType::class, [
              'class' => Tag::class,
              'choice_label' => 'name',
              'multiple' => true,
            ])
          ->add('Create Post', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'method' => 'POST',
        ]);
    }
}
