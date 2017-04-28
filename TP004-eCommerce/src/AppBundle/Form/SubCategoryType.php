<?php

namespace AppBundle\Form;

use AppBundle\Subscriber\SubCategorySubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubCategoryType extends AbstractType
{
    private $locales;
    private $request;

    public function __construct($locales, RequestStack $request){
        $this->locales = $locales;
        $this->request = $request->getMasterRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', SubCategoryTranslationType::class, [
                'mapped' => false
            ])
            ->add('category', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => "translations[{$this->request->getLocale()}].name"
        ]);

        //souscripteur du formulaire
        $builder->addEventSubscriber(new SubCategorySubscriber($this->locales));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SubCategory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_subcategory';
    }


}
