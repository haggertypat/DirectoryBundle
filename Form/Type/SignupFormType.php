<?php

namespace CCETC\DirectoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignupFormType extends AbstractType
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
        $builder
            ->add('name', 'text', array('label' => 'Listing Name'))
            ->add('address', 'text')
            ->add('city', 'text')
            ->add('state', 'choice', array('choices' => array('NY' => 'New York')))
            ->add('zip', 'text')
            ->add('website', 'text', array('required' => false))
            ->add('contactName', 'text', array('label' => 'Contact Name'))
            ->add('primaryEmail', 'text', array('label' => 'E-mail', 'required' => false))
            ->add('primaryPhone', 'text', array('label' => 'Phone', 'required' => false))
            ->add('description', 'textarea', array('label' => 'Listing Description', 'attr' => array('rows' => '5'), 'required' => false))
            ->add('photoFile', 'file', array('required' => false, 'label' => 'Profile Photo', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->classPath,
        ));
    }

    public function getFieldsets()
    {
        return array(
            'Basic Information' => array(
                'name', 'contactName', 'primaryPhone', 'primaryEmail', 'website', 'description', 'photoFile'
            ),
            'Address' => array(
                'address', 'city', 'state', 'zip'
            )
        );
    }

    public function getName()
    {
        return 'ccetc_directory_signup';
    }
}
