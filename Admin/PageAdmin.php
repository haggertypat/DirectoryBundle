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
            ->with('Basic')
                ->add('title')
                ->add('content', 'textarea', array('required' => false, 'label' => 'Content', 'attr' => array('class' => 'tinymce')))
                ->add('parent', null, array('label' => 'Parent Page'))
            ->end()
            ->with('Advanced')
                ->add('metaDescription')
                ->add('metaTitle')
                ->add('route', null, array('label' => 'URL', 'required' => false))
                ->add('menuLabel')
                ->add('heading')
                ->add('menuWeight', null, array('label' => 'Menu Weight'))
            ->end()
            ->setHelps(array(
                'menuWeight' => 'Higher number appears sooner in menu',
                'metaDescription' => 'Text that describes the page - only visible to search engines',
                'metaTitle' => 'Title used in tab, will read "<i><b>title</b></i> | <i>site name</i>"'
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
            ->with('Basic')
                ->add('title')
                ->add('parent', null, array('label' => 'Parent Page'))
            ->end()
            ->with('Advanced')
                ->add('metaDescription')
                ->add('metaTitle')
                ->add('menuLabel')
                ->add('heading')
                ->add('route', null, array('label' => 'URL'))
                ->add('menuWeight', null, array('label' => 'Menu Weight'))
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
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

    public function prePersist($object)
    {
        $this->autosetFields($object);
    }
    public function preUpdate($object)
    {
        $this->autosetFields($object);
    }

    public function autosetFields($object)
    {
        // 1. Get original data to check if things have changed
        $uow = $this->configurationPool->getContainer()->get('doctrine')->getEntityManager()->getUnitOfWork();
        $original = $uow->getOriginalEntityData($object);
        
        if(isset($original['title'])) $originalTitle = $original['title'];
        else $originalTitle = null;

        if(isset($original['route'])) $originalRoute = $original['route'];
        else $originalRoute = null;

        $newTitle = $object->getTitle();

        // 2. if unset, or unchanged, set with the title
        if(!$object->getHeading() || $object->getHeading() == $originalTitle) {
            $object->setHeading($object->getTitle());
        }
        if(!$object->getMenuLabel() || $object->getMenuLabel() == $originalTitle) {
            $object->setMenuLabel($object->getTitle());
        }
        if(!$object->getMetaTitle() || $object->getMetaTitle() == $originalTitle) {
            $object->setMetaTitle($object->getTitle());
        }

        // NOTE: determine route before checking to see if we should set it
        //
        //Lower case everything
        $newRoute = strtolower($object->getTitle());
        //Make alphanumeric (removes all other characters)
        $newRoute = preg_replace("/[^a-z0-9_\s-]/", "", $newRoute);
        //Clean up multiple dashes or whitespaces
        $newRoute = preg_replace("/[\s-]+/", " ", $newRoute);
        //Convert whitespaces and underscore to dash
        $newRoute = preg_replace("/[\s_]/", "-", $newRoute);

        if(!$object->getRoute()  || $object->getRoute() == $originalRoute) {
            $object->setRoute($newRoute);
        }   
    }
}