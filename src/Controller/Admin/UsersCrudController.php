<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(true)
                ->setHelp('Sélectionner un ou plusieurs rôles.')
                ->renderExpanded(true)
                ->setRequired(true), // Le rôle est requis lors de la création
        ];

        // Champ de mot de passe (vide lors de l'édition)
        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = TextField::new('password', 'Mot de passe')
                ->setFormTypeOption('attr', ['type' => 'password'])
                ->setFormType(PasswordType::class)
                ->setRequired($pageName === Crud::PAGE_NEW); // Obligatoire uniquement lors de la création
        }

        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users) {
            $this->handlePassword($entityInstance);  // Hache le mot de passe lors de la création

            // Si les rôles sont vides, attribuer un rôle par défaut
            if (empty($entityInstance->getRoles())) {
                $entityInstance->setRoles(['ROLE_USER']); // ROLE_USER par défaut
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users) {
            $this->handlePassword($entityInstance);  // Hache le mot de passe lors de la mise à jour
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handlePassword(Users $user): void
    {
        $password = $user->getPassword();

        // Vérifier si le mot de passe a été défini (lors de la création ou modification)
        if ($password) {
            // Hacher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }
    }
}
