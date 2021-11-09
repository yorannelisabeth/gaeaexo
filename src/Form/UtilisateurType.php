<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
            ->add('nom',TextType::class,["label" => "nom"])
            ->add('prenom',TextType::class, ["label" => "prenom"])
            ->add('email',TextType::class, ["label" => "email"])
            ->add('adresse',TextType::class, ["label" => "adresse"])
            ->add('tel',TextType::class, ["label" => "tel"])
            ->add("enregistrer" , SubmitType::class, [
                "attr" => [
                "class" => "btn btn-secondary"
                ] 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
