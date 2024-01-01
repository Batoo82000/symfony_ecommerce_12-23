<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use PhpParser\Parser\Multiple;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
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
            TextField::new('name'),
            TextField::new('brand'),
            SlugField::new('slug')->setTargetFieldName('name'),
            AssociationField::new('categories'), // Ici on récupère le 'name' de l'entité catégory
            TextField::new('description'),
            TextEditorField::new('more_description'),
            TextEditorField::new('additional_infos'),
            ImageField::new('imagesUrls') // Permet l'upload vers un dossier d'une image + mise en bdd du chemin de l'image
            ->setFormTypeOptions([
                "multiple" => true, //Indique que le champs peut accueillir plusieurs fichiers en upload
                "attr"=> [
                    "accept"=>"image/x-png, image/gif, image/jpeg, image/jpg" //On choisi le typage des fichiers que l'on peux uploader
                ]
            ])
            ->setBasePath("assets/images/products")
            ->setUploadDir("/public/assets/images/products")
            ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]'),// Renome le fichier selon un schéma selon ce qui est indiqué entre parenthèse, ici, on récupère le slug et on le concatène avec le timestamp tout en gardant l'extension.
            MoneyField::new('regular_price')->setCurrency('EUR'),
            MoneyField::new('solde_price')->setCurrency('EUR'),
            IntegerField::new('stock'),
            BooleanField::new('isAvailable'),
            BooleanField::new('isBestSeller'),
            BooleanField::new('isNewArrival'),
            BooleanField::new('isFeatured'),
            BooleanField::new('IsSpecialOffer'),
        ];
    }
    
}
