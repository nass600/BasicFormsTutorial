<?php

namespace Ivelazquez\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ivelazquez\AdminBundle\Form\Handler\ProjectFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Ivelazquez\AdminBundle\Form\Type\ProjectFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProjectController extends Controller
{
    /**
     * @Route("/", name="admin_project_list")
     * @Template("IvelazquezAdminBundle:Project:list.html.twig")
     */
    public function listAction()
    {
        $projects = $this->getDoctrine()->getEntityManager()->getRepository('IvelazquezAdminBundle:Project')->findAll();

        return array(
            'projects' => $projects
        );
    }

    /**
     * @Route("/show/{id}", name="admin_project_show")
     * @Template("IvelazquezAdminBundle:Project:show.html.twig")
     */
    public function showAction($id)
    {
        $project = $this->getDoctrine()->getEntityManager()->getRepository(
            'IvelazquezAdminBundle:Project'
        )->findOneById($id);

        return array(
            'project' => $project
        );
    }


    /**
     * @Route("/new", name="admin_project_new")
     * @Template("IvelazquezAdminBundle:Project:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(new ProjectFormType());

        if ($request->getMethod() == "POST") {
            $formHandler = new ProjectFormHandler($form, $request, $this->getDoctrine()->getEntityManager());

            if ($formHandler->process()) {

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    "The project <b>{$form->getData()->getTitle()}</b> has been created"
                );

                return $this->redirect($this->generateUrl('admin_project_list'));
            }

            $this->get('session')->getFlashBag()->add(
                'error',
                "Something went wrong while processing the form"
            );
        }

        return array(
            'form' => $form->createView()
        );
    }


    /**
     * @Route("/edit/{id}", name="admin_project_edit")
     * @Template("IvelazquezAdminBundle:Project:edit.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $project = $em->getRepository('IvelazquezAdminBundle:Project')->findOneById($id);

        if (null === $project) {
            throw new \InvalidArgumentException("The project with id: $id does not exists in the datatbase");
        }

        $form = $this->createForm(new ProjectFormType(), $project);

        if ($request->getMethod() == "POST") {
            $formHandler = new ProjectFormHandler($form, $request, $em);

            if ($formHandler->process()) {
                return $this->redirect($this->generateUrl('admin_project_list'));
            }
        }

        return array(
            'form'  => $form->createView()
        );
    }
}
