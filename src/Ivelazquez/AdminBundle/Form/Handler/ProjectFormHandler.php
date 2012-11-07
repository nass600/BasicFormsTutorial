<?php

namespace Ivelazquez\AdminBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class ProjectFormHandler
{
    protected $form;

    protected $request;

    protected $em;

    public function __construct(FormInterface $form, Request $request, EntityManager $em)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
    }

    public function process()
    {
        $this->form->bind($this->request);

        if ($this->form->isValid()) {
            $this->em->persist($this->form->getData());
            $this->em->flush();

            return true;
        }

        return false;
    }
}
