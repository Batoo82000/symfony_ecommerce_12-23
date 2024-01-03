<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
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
            TextField::new('street'),
            TextField::new('city'),
            TextField::new('code_postal'),
            TextField::new('state'),
            TelephoneField::new('phone_number'),
        ];
    }
}
