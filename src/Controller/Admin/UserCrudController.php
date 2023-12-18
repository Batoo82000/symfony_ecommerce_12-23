<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\Form\FormEvents;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Choice;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher) 
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('civility')->setChoices([
                "Madame" => "Mme",
                "Monsieur" => "M.",
                "Mademoiselle" => "Mlle",
                "Autre" => " ",
            ]),
            TextField::new('full_name'),
            EmailField::new('email'),
            TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' =>'Password',
                        'row_attr' => [
                            'class' => 'col-md-6 col-xxl-5'
                        ],
                    ],
                    'second_options' => [
                        'label' =>'Confirm Password',
                        'row_attr' => [
                            'class' => 'col-md-6 col-xxl-5'
                        ],
                    ],
                    'mapped' => false,
                ])
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms()
        ];
    }

    public function createNewFormBuilder(   EntityDto $entityDto, //représente l'ntité avec laquelle je travaille. Il contient des informations sur l'entité, comme son nom, ses propriétés, etc...
                                            KeyValueStore $formOptions, // représente un ensemble d'options pour le formulaire. Ces options peuvent être configurées dans la configuration d'EasyAdmin pour l'entité en cours.
                                            AdminContext $context // contient des informations sur le contexte actuel de l'administration, comme l'action en cours (création, édition, suppression, etc.).
                                        ): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(  EntityDto $entityDto, // représente l'entité avec laquelle je travaille. Il contient des informations sur l'entité, comme son nom, ses propriétés, etc.
                                            KeyValueStore $formOptions, // représente un ensemble d'options pour le formulaire. Ces options peuvent être configurées dans la configuration d'EasyAdmin pour l'entité en cours.
                                            AdminContext $context // contient des informations sur le contexte actuel de l'administration, comme l'action en cours (création, édition, suppression, etc.).
    ): FormBuilderInterface
    {
    $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
    return $this->addPasswordEventListener($formBuilder);
    }

    public function addPasswordEventListener(FormBuilderInterface $formBuilder) {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword()); //écoute quand le formulaire est soumis, puis utilise la méthode hashPassword pour hacher et set le mot de passe
    }

    public function hashPassword() {
        return function($event){
            $form = $event->getForm(); //on récupère dans $form les infos sur le formulaire.
            if(!$form->isValid()){
                return;
            } // si le formulaire n'est pas valide, on return
            $password = $form->get('password')->getData(); // $password récupère ce qui a été saisi dans le formulaire
            
            if ($password === null){
                return;
            }// si la valeur du password est null, on return
            $hash = $userPasswordHasher->hashPassword($this->getUser(), $password); //hache le mot de passe
            $form->getData()->setPassword($hash);//stock la valeur hachée dans password
        };
    }
}
