<?php

namespace CCETC\DirectoryBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Datagrid\ORM\ProxyQuery;

class ListingAdminController extends Controller
{
    public function approveAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $object = $this->admin->getObject($id);

        $object->setApproved(true);

        $em->persist($object);
        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'Item has been approved');
        $url = $this->getRequest()->headers->get("referer");
        return new RedirectResponse($url);
    }

    public function unapproveAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $object = $this->admin->getObject($id);

        $object->setApproved(false);

        $em->persist($object);
        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'Item has been un-approved');
        $url = $this->getRequest()->headers->get("referer");
        return new RedirectResponse($url);
    }

    public function batchActionApprove($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setApproved(true);
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected items have been approved');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionUnapprove($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setApproved(false);
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected items have been un-approved');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
    
    public function batchActionSpam($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setSpam(true);
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected items have been marked as spam');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionUnSpam($query)
    {
        $em = $this->getDoctrine()->getEntityManager();

        foreach($query->getQuery()->iterate() as $pos => $object) {
            $object[0]->setSpam(false);
        }

        $em->flush();
        $em->clear();

        $this->getRequest()->getSession()->setFlash('sonata_flash_success', 'The selected items have been marked as not spam');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}