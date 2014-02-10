<?php

namespace CCETC\DirectoryBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Datagrid\ORM\ProxyQuery;

use CCETC\DirectoryBundle\Entity\BaseListing;

class ListingAdminController extends Controller
{
    public function updateStatusAction($id, $status)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $object = $this->admin->getObject($id);

        $object->setStatus($status);

        $em->persist($object);
        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'Listing has been marked as "'.BaseListing::$statusChoices[$status].'"');
        $url = $this->getRequest()->headers->get("referer");
        return new RedirectResponse($url);
    }

    public function batchActionApprove($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setStatus('approved');
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected Listings have been marked as "Approved"');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionUnapprove($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setStatus('new');
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected Listings have been "Needs Approval"');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
    
    public function batchActionSpam($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setStatus('spam');
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected Listings have been marked as spam');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionUnSpam($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setStatus('new');
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected Listings have been marked as "Needs Approval"');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}