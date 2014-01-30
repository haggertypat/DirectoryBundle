<?php

namespace CCETC\DirectoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignupFormType extends ListingFormType
{
    private $classPath;

    /**
     * @param string $classPath The Listing class name
     */
    public function __construct($classPath)
    {
        $this->classPath = $classPath;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = parent::buildForm($builder, $options);

        $builder
            ->add('password1', 'password', array('label' => 'Password', 'property_path' => false))
            ->add('password2', 'password', array('label' => 'Verify Password', 'property_path' => false))
        ;
    }

    public function getFieldsets()
    {
        $fieldsets = parent::getFieldsets();

        $fieldsets['Contact Information'][] = 'password1';
        $fieldsets['Contact Information'][] = 'password2';

        return $fieldsets;
    }

    public function getName()
    {
        return 'ccetc_directory_signup';
    }
}
