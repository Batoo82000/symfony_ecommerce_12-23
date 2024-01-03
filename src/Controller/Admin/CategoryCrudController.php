<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
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
            SlugField::new('slug')->setTargetFieldName('name'), // Produit un slug à partir du champs name et le stock en bdd
            TextEditorField::new('description'),
            BooleanField::new('isMega'),
            ImageField::new('imageUrl') // Permet l'upload vers un dossier d'une image + mise en bdd du chemin de l'image
            ->setFormTypeOptions([
                "attr"=> [
                    "accept"=>"image/x-png, image/gif, image/jpeg, image/jpg, image/webp"
                ]
            ])
            ->setBasePath("assets/images/categories")
            ->setUploadDir("/public/assets/images/categories")
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')// Renome le fichier selon un schéma selon ce qui est indiqué entre parenthèse, ici, on récupère le slug et on le concatène avec le timestamp tout en gardant l'extension.
        ];
    }
}
