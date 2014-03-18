<?php

namespace CCETC\DirectoryBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{

    public function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ccetc_directory_type_filter_location';
    }

    public function getChoices()
    {
        return array(
            '25' => '25',
            '50' => '50',
            '75' => '75',
            '100' => '100'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array('choices' => $this->getChoices(), 'required' => false))
            ->add('value', 'text', array_merge(array('required' => false, 'label' => 'taco'), $options['field_options']))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_options' => array()
        ));
    }
}
