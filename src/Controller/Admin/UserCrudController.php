<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\Form\FormEvents;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    // Constructeur de la classe avec injection de dépendance
    public function __construct(public UserPasswordHasherInterface $userPasswordHasher ){}

    // Méthode statique pour obtenir le nom de la classe de l'entité gérée
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions //La méthode configureActions est utilisée pour configurer les actions disponibles pour une entité dans le tableau de bord d'EasyAdmin.
    {
        return $actions //Cette ligne indique que la méthode doit retourner l'objet $actions après avoir appliqué les modifications.
            ->add(Crud::PAGE_EDIT, Action::INDEX) // Dans la partie edit de user dans le dashboard, fait apparaitre un bouton "back to listing"
            ->add(Crud::PAGE_EDIT, Action::DETAIL) // Dans la partie edit de user dans le dashboard, fait apparaitre un bouton "show"
            ->add(Crud::PAGE_INDEX, Action::DETAIL) // Dans la partie index de user dans le dashboard, fait apparaitre un bouton "show"
            ;   
    }

    // Configuration des champs du formulaire pour la création/édition d'un utilisateur
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('civility')
            ->setChoices([
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

    // Méthode pour créer le FormBuilder lors de la création d'une nouvelle entité
    public function createNewFormBuilder(   EntityDto $entityDto, //représente l'entité avec laquelle je travaille. Il contient des informations sur l'entité, comme son nom, ses propriétés, etc...
                                            KeyValueStore $formOptions, // représente un ensemble d'options pour le formulaire. Ces options peuvent être configurées dans la configuration d'EasyAdmin pour l'entité en cours.
                                            AdminContext $context // contient des informations sur le contexte actuel de l'administration, comme l'action en cours (création, édition, suppression, etc.).
                                        ): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    // Méthode pour créer le FormBuilder lors de l'édition d'une entité existante
    public function createEditFormBuilder(  EntityDto $entityDto, // représente l'entité avec laquelle je travaille. Il contient des informations sur l'entité, comme son nom, ses propriétés, etc.
                                            KeyValueStore $formOptions, // représente un ensemble d'options pour le formulaire. Ces options peuvent être configurées dans la configuration d'EasyAdmin pour l'entité en cours.
                                            AdminContext $context // contient des informations sur le contexte actuel de l'administration, comme l'action en cours (création, édition, suppression, etc.).
    ): FormBuilderInterface
    {
    $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
    return $this->addPasswordEventListener($formBuilder);
    }

    // Méthode pour ajouter un écouteur d'événement pour le hachage du mot de passe
    public function addPasswordEventListener(FormBuilderInterface $formBuilder) {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword()); //écoute quand le formulaire est soumis, puis utilise la méthode hashPassword pour hacher et set le mot de passe
    }
    
    // Méthode pour hacher le mot de passe après la soumission du formulaire
    public function hashPassword() {
        return function($event){

            $form = $event->getForm(); //on récupère dans $form les infos sur le formulaire.

            if(!$form->isValid()){
                return;
            } // si le formulaire n'est pas valide, on return

            $password = $form->get('password')->getData(); // $password récupère ce qui a été saisi dans le formulaire au niveau de password
            
            if ($password === null){
                return;
            }// si la valeur du password est null, on return

            // Utilisation du service d'interface UserPasswordHasherInterface pour hacher le mot de passe
            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password); //hache le mot de passe
            $form->getData()->setPassword($hash);//stock la valeur hachée dans password de l'entité user
        };
    }
}
