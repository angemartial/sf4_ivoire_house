<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{   
   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 
                TextType::class, $this->getConfiguration("Titre","Entrez un titre"))

            ->add('slug', 
                TextType::class, $this->getConfiguration("Adresse web","tapez l'adresse web (automatique)", [
                    'required' => false
                ]))

             ->add('coverImage', 
                UrlType::class, $this->getConfiguration("Url de l'image","Entrez l'adresse de l'image"))

            ->add('introduction',
                TextType::class, $this->getConfiguration("Introduction","Donnez une description globale de l'appartement"))  

            ->add('content',
                TextareaType::class, $this->getConfiguration("Description","Entrez une description de votre appartement"))

            ->add('rooms', 
                IntegerType::class, $this->getConfiguration("Nombre de chambres","Entrez le nombre de chambres"))

            ->add('price', 
                MoneyType::class, $this->getConfiguration("Prix par nuit","Entrez un prix par nuit"))

            ->add('images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true

                ]                
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
