<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
         * add : ajouter un champ au formulaire
         *          paramètres :
         *              - nom du champ utilisé dans la vue
         *              - type du champ (Symfony\Component\Form\Extension\Core\Type\)
         *              - options : tableau associatif
         *                  - constraints : contraintes de validation
         *                  - data : valeur du champ
         *                  - label : intitulé de champ (mais plus smart à faire ds la vue pour multilang)
         *                  - query_builder : requête pour alimenter un champ (champ multiple)
         */

        $builder
            ->add('firstName') //voir exemple ds l'entité
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est vide'
                    ]),
                    new Length([
                        'min' => '2',
                        'minMessage' => 'Votre nom doit comporter {{ limit }} caractères au minimum',
                        'max' => '50',
                        'maxMessage' => 'Votre nom doit comporter {{ limit }} caractères au maximum'
                    ])
                ]
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email est vide'
                    ]),
                    new Email([
                        'message' => 'L\'email est incorrect',
                        'checkHost' => true,
                        'checkMX' => true
                    ]),
                    new Length([
                        'min' => '5',
                        'minMessage' => 'Votre email doit comporter {{ limit }} caractères au minimum',
                        'max' => '100',
                        'maxMessage' => 'Votre email doit comporter {{ limit }} caractères au maximum'
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le message est vide'
                    ]),
                    new Length([
                        'min' => '10',
                        'minMessage' => 'Votre message doit comporter {{ limit }} caractères au minimum'
                    ])
                ]
            ])
            ->add('subject', EntityType::class, [
                'class' => 'AppBundle\Entity\ContactSubject',
                'choice_label' => 'content',
                'placeholder' => 'Choisir un sujet',
                'expanded' => false, //Affichage de bouton radio
                'multiple' => false, //UNIQUEMENT EN MANY TO MANY !!
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un sujet'
                    ])
                ]
            ])
            ->add('hobbies', EntityType::class, [
                'class' => 'AppBundle\Entity\Hobby',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new Count([
                        'min' => '1',
                        'minMessage' => 'Vous devez selectionner minimum 1 loisir'
                    ])
                ]
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }


}
