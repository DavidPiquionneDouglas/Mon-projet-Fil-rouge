<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email')->setLabel('Adresse électronique'),
            TextField::new('matricule')->setLabel('Matricule'),
            TextField::new('firstName')->setLabel('Prénom'),
            TextField::new('lastName')->setLabel('Nom'),
            DateField::new('birthday')->setLabel('Date de naissance'),
            TextField::new('telephone')->setLabel('Téléphone'),
            TextField::new('service')->setLabel('Service'),
            TextField::new('speciality')->setLabel('Spécialité'),
            ImageField::new('photo')
                ->setBasePath('/assets/image/user/')
                ->setUploadDir('public/assets/image/user/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            ChoiceField::new('roles')
                ->setLabel('Permissions')
                ->setHelp('Choix des rôles des membres')
                ->setChoices([
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_CHEF' => 'ROLE_CHEF',
                    'ROLE_USER' => 'ROLE_USER',
                ])
                ->allowMultipleChoices(),
        ];
    }
}
