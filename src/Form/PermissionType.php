<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Permission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('read_resa',CheckboxType::class,[
                'required'=>false,
                'label'=>'Lire les réservations'

            ])
            ->add('edit_resa',CheckboxType::class,[
                'required'=>false,
                'label'=>'Modifier les réservations'
            ])
            ->add('remove_resa',CheckboxType::class,[
                'required'=>false,
                'label'=>'Supprimer les réservations'
            ])
            ->add('read_payment',CheckboxType::class,[
                'required'=>false,
                'label'=>'Lire les paiements'
            ])
            ->add('edit_payment',CheckboxType::class,[
                'required'=>false,
                'label'=>'Modifier les paiements'
            ])
            ->add('manage_drink',CheckboxType::class,[
                'required'=>false,
                'label'=>'Gérer les boissons'
            ])
            ->add('add_sub',CheckboxType::class,[
                'required'=>false,
                'label'=>'Inscrire les abonnés'
            ])
            ->add('edit_sub',CheckboxType::class,[
                'required'=>false,
                'label'=>'Modifier les abonnés'
            ])
            ->add('remove_sub',CheckboxType::class,[
                'required'=>false,
                'label'=>'Supprimer les abonnés'
            ])
            ->add('manage_schedules',CheckboxType::class,[
                'required'=>false,
                'label'=>'Gérer les horaires'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Permission::class,
        ]);
    }
}
