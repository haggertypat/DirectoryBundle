<?php

namespace CCETC\DirectoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ListingFormType extends AbstractType
{
    protected $classPath;
    protected $container;   
    protected $registrationSetting;

    /**
     * @param string $classPath The Listing class name
     */
    public function __construct($classPath, $container)
    {
        $this->classPath = $classPath;
        $this->container = $container;
        $this->registrationSetting = $this->container->getParameter('ccetc_directory.registration_setting');        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->container->getParameter('ccetc_directory.registration_setting') == 'required') {
            $constraints =  array(
               new NotBlank(),
           );
            $emailRequired = true;
        } else {
            $emailRequired = false;
            $constraints = null;
        }

        if($this->registrationSetting != "none") {
            $emailRequired = true;
        } else {
            $emailRequired = false;
        }


        $builder
            ->add('name', 'text', array('label' => $this->container->get('translator')->trans('Listing').' Name'))
            ->add('address', 'text')
            ->add('city', 'text')
            ->add('state', 'choice', array('choices' => array('NY' => 'New York')))
            ->add('zip', 'text')
            ->add('website', 'text', array('required' => false))
            ->add('contactName', 'text', array('label' => 'Contact Name'))
            ->add('primaryEmail', 'text', array('label' => 'E-mail', 'required' => $emailRequired))
            ->add('primaryPhone', 'text', array('label' => 'Phone', 'required' => false))
            ->add('description', 'textarea', array('label' => $this->container->get('translator')->trans('Listing').' Description', 'attr' => array('rows' => '5', 'class' => 'span6'), 'required' => false))
            ->add('photoFile', 'file', array('required' => false, 'label' => 'Profile Photo', 'required' => false))
        ;


        return $builder;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => $this->classPath,
            'timed_spam' => true,
            'honeypot' => true,
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
        return 'ccetc_directory_listing';
    }
}
