<?php
namespace CCETC\DirectoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('content', 'textarea', array('required' => false, 'label' => 'Content', 'attr' => array('class' => 'tinymce')))
            ->add('parent', null, array('label' => 'Parent Page'))
            ->add('menuWeight', null, array('label' => 'Menu Weight'))
            ->setHelps(array(
                'menuWeight' => 'Higher number appears sooner in menu'
            ))
        ;
    }

    public $formPreHook = array(
        'template' => 'CCETCDirectoryBundle:Admin:_tiny_mce_include.html.twig'
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('parent', null, array('label' => 'Parent Page'))
       ;
    }

    public $showPreHook = array(
        'template' => 'CCETCDirectoryBundle:Admin:_show_page_view_on_site.html.twig'
    );

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with(' ')
            ->add('title')
            ->add('parent', null, array('label' => 'Parent Page'))
            ->add('menuWeight', null, array('label' => 'Menu Weight'))
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('menuWeight', null, array('label' => 'Menu Weight'))
            ->add('parent', null, array('label' => 'Parent Page'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))        
        ;
    }
}