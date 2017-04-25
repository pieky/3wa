<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordResetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'user.password_confirm.match',
                'options' =>[
                    'attr' => [
                        'class' => 'password-field'
                    ]
                ],
                'required' => true,
                'first_options'  => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'user.password.notblank'
                        ])
                    ]
                ],
                'second_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'user.password_confirm.notblank'
                        ])
                    ]
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_password_reset';
    }


}
