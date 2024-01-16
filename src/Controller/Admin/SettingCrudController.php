<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }
    public function configureActions(Actions $actions): Actions //La méthode configureActions est utilisée pour configurer les actions disponibles pour une entité dans le tableau de bord d'EasyAdmin.
    {
        return $actions //Cette ligne indique que la méthode doit retourner l'objet $actions après avoir appliqué les modifications.
            ->add(Crud::PAGE_EDIT, Action::INDEX) // Dans la partie edit de category dans le dashboard, fait apparaitre un bouton "back to listing"
            ->add(Crud::PAGE_NEW, Action::INDEX) // Dans la partie create de category dans le dashboard, fait apparaitre un bouton "back to listing"
            ->add(Crud::PAGE_EDIT, Action::DETAIL) // Dans la partie edit de category dans le dashboard, fait apparaitre un bouton "show"
            ->add(Crud::PAGE_INDEX, Action::DETAIL) // Dans la partie index de category dans le dashboard, fait apparaitre un bouton "show"
            ;   
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('website_name'),
            TextField::new('description'),
            ChoiceField::new('currency')
            ->setChoices([
                "EUR" => "EUR",
                "USD" => "USD",
                "XOF" => "XOF",
            ]),
            IntegerField::new('taxe_rate'),
            ImageField::new('logo')
            ->setFormTypeOptions([
                "attr"=> [
                    "accept"=>"image/x-png, image/gif, image/jpeg, image/jpg, image/webp"
                    ]
                    ])
                    ->setBasePath("assets/images/setting")
                    ->setUploadDir("/public/assets/images/setting")
                    ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                    ->setRequired($pageName === Crud::PAGE_NEW),
            TextField::new('facebookLink')->hideOnIndex(),
            TextField::new('youtubeLink')->hideOnIndex(),
            TextField::new('instagramLink')->hideOnIndex(),
            TextField::new('street'),
            TextField::new('city'),
            TextField::new('code_postal'),
            TextField::new('state'),
            TelephoneField::new('phone_number'),
        ];
    }
}
