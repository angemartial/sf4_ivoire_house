<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldPassword',
        PasswordType::class, $this->getConfiguration("Ancien mot de passe","entrez l'ancien mot de passe"))
        ->add('newPassword',
        PasswordType::class, $this->getConfiguration("Nouveau mot de passe",
        "veuillez entrer un nouveau mot de passe"))        
        ->add('confirmPassword',
        PasswordType::class, $this->getConfiguration("Confirmation du mot de passe",
        "confirmer le mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
