<?php

namespace App\Form;

use App\Entity\Commandes;
use App\Entity\Transporteurs;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // Récupération de l'utilisateur connecté

       
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'attr' => [
                'placeholder' => 'Entrez votre nom',
            ],
            'data' => $user->getNom()
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom',
            'required' => true,
            'attr' => [
                'placeholder' => 'Entrez votre prénom',
            ],
            'data' => $user->getPrenom()
        ])
        ->add('email', TextType::class, [
            'label' => 'Adresse e-mail',
            'required' => true,
            'attr' => [
                'placeholder' => 'Entrez votre adresse e-mail',
            ],
            'data' => $user->getEmail()
        ])
        ->add('transporteurs', EntityType::class,[
            'label' => false,
            'required'=> true,
            'class' => Transporteurs::class,
            'expanded' => true,
        ])
        ->add('adresses', ChoiceType::class, [
            'label' =>false,
            'required' => true,
            'choices' => $user->getAdresses(), // Adresses de l'utilisateur
            'choice_label' => function ($adresse) {
                return $adresse->getRue() . ', ' . $adresse->getVille() . ', ' . $adresse->getCodePostal() . ', ' . $adresse->getPays();
            },
            'expanded' => true, // Affichage sous forme de boutons radio
            'multiple' => false, // Une seule adresse peut être sélectionnée
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('user');
    }
}