<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCETC\DirectoryBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\Pool;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminListingApprovalBlockService extends BaseBlockService
{
    protected $container;
    
    /**
     * @param string                                                     $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    public function __construct($name, EngineInterface $templating, $container)
    {
        parent::__construct($name, $templating);
        
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());
        $listingTypeHelper = $this->container->get('ccetc.directory.helper.listingtypehelper');

        $templating = $this->container->get('templating');
        $content = "";

        foreach($listingTypeHelper->getAll() as $listingType)
        {
            $listingRepository = $listingType->getRepository();

            $content .= $templating->render('CCETCDirectoryBundle:Block:_admin_listing_approval.html.twig', array(
                'block' => $blockContext->getBlock(),
                'listings' => $listingRepository->findByStatus('new'),
                'reapprovalListings' => $listingRepository->findByStatus('edited'),
                'expiredListings' => $listingRepository->findByStatus('expired'),
                'upForRenewalListings' => $listingRepository->findByStatus('upForRenewal'),
                'listingAdmin' => $listingType->getAdminClass(),
                'translationKey' => $listingType->getTranslationKey()
            ));
        }        
        
        return new Response($content);
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Admin Listing Approval';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        
    }
}