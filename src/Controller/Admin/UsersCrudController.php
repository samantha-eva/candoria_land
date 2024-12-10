<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UsersCrudController extends AbstractCrudController
{

    private UserPasswordHasherInterface $passwordHasher;
    private SendEmailService $sendEmailService;
    private UrlGeneratorInterface $urlGenerator;
    private JWTService $jwt;


    public function __construct(UserPasswordHasherInterface $passwordHasher, SendEmailService $sendEmailService, UrlGeneratorInterface $urlGenerator, JWTService $jwt)
    {
        $this->passwordHasher = $passwordHasher;
        $this->sendEmailService = $sendEmailService;
        $this->urlGenerator = $urlGenerator;
        $this->jwt =$jwt;
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
            TextField::new('email', 'Email')->setFormType(EmailType::class),
            // Affiche uniquement le rôle principal dans le listing
            TextField::new('mainRole', 'Rôles')->onlyOnIndex()
        ];
     
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



        if ($pageName === Crud::PAGE_EDIT) {
            $fields[] = TextField::new('oldPassword', 'Ancien mot de passe')
                ->setFormType(PasswordType::class)
                ->setRequired(false)
                ->setHelp('Saisissez votre ancien mot de passe pour en changer.');
    
            $fields[] = TextField::new('newPassword', 'Nouveau mot de passe')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOption('type', PasswordType::class)
                ->setFormTypeOption('invalid_message', 'Les mots de passe doivent correspondre')
                ->setFormTypeOption('first_options', ['label' => 'Nouveau mot de passe'])
                ->setFormTypeOption('second_options', ['label' => 'Confirmation du nouveau mot de passe'])
                ->setRequired(false)
                ->setHelp('Laissez vide si vous ne souhaitez pas modifier le mot de passe.');
        }

        if ($pageName === Crud::PAGE_NEW) {
            $fields[] = TextField::new('password', 'Mot de passe')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOption('type', PasswordType::class)
                ->setFormTypeOption('invalid_message', 'Les mots de passe doivent correspondre')
                ->setFormTypeOption('first_options', ['label' => 'Mot de passe'])
                ->setFormTypeOption('second_options', ['label' => 'Confirmation du mot de passe'])
                ->setRequired(true);
        }
            // Ajoutez isVerified uniquement dans la vue d'index ou show
        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = BooleanField::new('isVerified', 'Compte vérifié')
                ->renderAsSwitch(false);
        }
    
        
        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        if ($entityInstance instanceof Users) { 

            // Hacher le mot de passe de l'utilisateur (si nécessaire)
            $this->handlePassword($entityInstance);

            // Récupérer les rôles de l'utilisateur à partir de l'entité.
            $roles = $entityInstance->getRoles();
            //avant de persister l'entité dans la base de données.
            $entityInstance->setRoles($roles);
            
            // Persister l'utilisateur avant de générer le token pour avoir l'ID de l'utilisateur
            parent::persistEntity($entityManager, $entityInstance);

            // Générer le token après que l'utilisateur soit persisté
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // L'ID de l'utilisateur est maintenant disponible
            $payload = [
                'user_id' => $entityInstance->getId()
            ];

            // Utilisation de votre service JWT pour générer le token
            $token = $this->jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            $user = $entityInstance;

            // Envoyer l'e-mail d'activation
            $this->sendEmailService->send(
                'no-reply@example.com', 
                $entityInstance->getEmail(), 
                'Activation de votre compte', 
                'register',
                compact('user', 'token')
            );
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
       
        if ($entityInstance instanceof Users) {

            $oldPassword = $entityInstance->getOldPassword();
            $newPassword = $entityInstance->getNewPassword();
    
            // Si un nouveau mot de passe est fourni
            if ($newPassword) {
                // Vérifiez que l'ancien mot de passe est également fourni
                if (!$oldPassword) {
                    throw new \Exception('Vous devez fournir l\'ancien mot de passe pour changer votre mot de passe.');
                }

                // Vérifiez que l'ancien mot de passe est correct
                if (!$this->passwordHasher->isPasswordValid($entityInstance, $oldPassword)) {
                    throw new \Exception('L\'ancien mot de passe est incorrect.');
                }

                // Hachez et mettez à jour le nouveau mot de passe
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $newPassword);
                $entityInstance->setPassword($hashedPassword);
            }
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
