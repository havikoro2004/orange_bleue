<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('short_desc',TextType::class,[
                'required'=>false
            ])
            ->add('full_desc',TextareaType::class,[
                'required'=>false
            ])
            ->add('imageFile',VichFileType::class,[
                'required'=>false
            ])
            ->add('url',UrlType::class,[
                'required'=>false
            ])
            ->add('dpo',TextType::class,[
                'required'=>false
            ])
            ->add('technical_contact',EmailType::class)
            ->add('commercial_contact',EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
