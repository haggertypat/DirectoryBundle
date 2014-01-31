<?php

namespace CCETC\DirectoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignupFormType extends ListingFormType
{
    private $classPath;
    protected $container;  
    protected $registrationSetting; 

    /**
     * @param string $classPath The Listing class name
     */
    public function __construct($classPath, $container)
    {
        $this->classPath = $classPath;
        $this->container = $container;
        $registrationSetting = $this->container->getParameter('ccetc_directory.registration_setting');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = parent::buildForm($builder, $options);

        if($this->registrationSetting == "required") {
            $builder
                ->add('password1', 'password', array('label' => 'Password', 'property_path' => false))
                ->add('password2', 'password', array('label' => 'Verify Password', 'property_path' => false))
            ;            
        }
    }

    // NOTE: this still needs to appear here, even though it's in the parent class... Not sure why, but the form doesn't treat the data as an object when handling
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->classPath,
        ));
    }

    public function getFieldsets()
    {
        $fieldsets = parent::getFieldsets();

        if($this->registrationSetting == "required") {
            $fieldsets['Contact Information'][] = 'password1';
            $fieldsets['Contact Information'][] = 'password2';
        }

        return $fieldsets;
    }

    public function getName()
    {
        return 'ccetc_directory_signup';
    }
}
