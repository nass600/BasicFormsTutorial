<?php

namespace Ivelazquez\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProjectController extends Controller
{
    /**
     * @Route("/", name="admin_project_list")
     * @Template()
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
     * @Template()
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
}
