<?php

namespace AppBundle\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryTranslationType extends AbstractType
{

    private $locales;
    private $doctrine;
    private $request;

    public function __construct($locales, Registry $doctrine, RequestStack $request){
        $this->locales = $locales;
        $this->request = $request->getMasterRequest();
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityId = $this->request->get('id');

        foreach ($this->locales as $key => $value) {

            $entity = $entityId ? $this->doctrine->getRepository('AppBundle:Category')->find($entityId) : null;

            $builder
                ->add("name_$value", TextType::class, [
                    'mapped' => false,
                    'data' => $entity ? $entity->translate($value)->getName() : null
                ])
            ;

        }

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CategoryTranslation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_categorytranslation';
    }


}
