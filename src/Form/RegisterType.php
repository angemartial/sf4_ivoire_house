<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegisterType extends ApplicationType
{
    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',
            TextType::class, $this->getConfiguration("Prénom","Entrez votre prénom"))
            ->add('lastName',
            TextType::class, $this->getConfiguration("Nom","Entrez votre nom"))
            ->add('email',
            EmailType::class, $this->getConfiguration("Email","Entrez votre email"))
            ->add('picture',
            UrlType::class, $this->getConfiguration("Photo de profil","importez une photo de profil"))
            ->add('hash',
            PasswordType::class, $this->getConfiguration("Mot de passe","entrez un mot de passe"))
            ->add('confirmPassword',
            PasswordType::class, $this->getConfiguration("Confirmation de mot de passe",
            "veuillez confirmer le mot de passe"))
            ->add('introduction',
            TextType::class, $this->getConfiguration("Intrduction","Présentez vous en quelques mots.."))
            ->add('description',
            TextareaType::class, $this->getConfiguration("Description","Présentez vous en détaille"))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
