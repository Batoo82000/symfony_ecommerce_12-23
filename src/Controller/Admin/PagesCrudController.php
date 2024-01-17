<?php

namespace App\Controller\Admin;

use App\Entity\Pages;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pages::class;
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
            TextField::new('title'),
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('content'),
            BooleanField::new('isHeader'),
            BooleanField::new('isFooter'),
        ];
    }
}
