<?php

namespace AppBundle\Form;

use AppBundle\Subscriber\UserFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    private $request;
    private $passwordEncoder;
    public function __construct(RequestStack $request, UserPasswordEncoder $passwordEncoder){
        $this->request = $request;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.username.notblank'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'user.password_confirm.match',
                'options' =>[
                    'attr' => [
                        'class' => 'password-field'
                    ]
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.password.notblank'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.email.notblank'
                    ]),
                    new Email([
                        'message' => 'user.email.email',
                        'checkHost' => true,
                        'checkMX' => true
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.address.notblank'
                    ])
                ]
            ])
            ->add('zipcode', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.zipcode.notblank'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.city.notblank'
                    ])
                ]
            ])
            ->add('country', CountryType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.country.notblank'
                    ])
                ],
                'placeholder' => ''
            ])
        ;

        //souscripteur du formulaire
        $builder->addEventSubscriber(new UserFormSubscriber($this->request, $this->passwordEncoder));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
