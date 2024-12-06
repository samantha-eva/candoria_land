<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersCrudController extends AbstractCrudController
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('username', 'Nom d\'utilisateur'),
            TextField::new('email', 'Email'),
            // Affiche uniquement le rôle principal dans le listing
            TextField::new('mainRole', 'Rôles')->onlyOnIndex(),
           BooleanField::new('isVerfified')->renderAsSwitch(false),
        ];
        //Ce bloc de code s'assure que les champs pour les rôles et le mot de passe sont ajoutés seulement lors de la création ou modification de lentité.

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            // Ajoute un champ "roles" qui permet de sélectionner un ou plusieurs rôles pour l'utilisateur.
            $fields[] = ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(true)  // Permet à l'utilisateur de sélectionner plusieurs rôles.
                ->renderExpanded(true) // Affiche les options sous forme de cases à cocher 
                ->setHelp('Sélectionner un ou plusieurs rôles.')  // Ajoute une aide visuelle sous le champ pour indiquer à l'utilisateur qu'il peut choisir un ou plusieurs rôles.
                ->setRequired(true);   // Définit que le champ est requis
        }

        // Ce bloc de code ajoute le champ pour le mot de passe uniquement lors de la création ou de la modification de l'entité.

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            // Ajoute un champ "password" pour saisir le mot de passe de l'utilisateur.
            $fields[] = TextField::new('password', 'Mot de passe')
                ->setFormTypeOption('attr', ['type' => 'password'])
                ->setFormType(PasswordType::class)
                ->setRequired($pageName === Crud::PAGE_NEW); // Obligatoire uniquement à la création
        }
        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users) { // Vérifie si l'entité passée est une instance de la classe Users.
            // Hacher le mot de passe de l'utilisateur (si nécessaire)
            $this->handlePassword($entityInstance);
            // Récupérer les rôles de l'utilisateur à partir de l'entité.
            $roles = $entityInstance->getRoles();
            //avant de persister l'entité dans la base de données.
            $entityInstance->setRoles($roles); 
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users) {
            // Hacher le mot de passe de l'utilisateur (si nécessaire)
            $this->handlePassword($entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handlePassword(Users $user): void
    {
        $password = $user->getPassword();

        if ($password) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }
    }



}
