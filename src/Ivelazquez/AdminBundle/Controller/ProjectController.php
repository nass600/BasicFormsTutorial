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
        return array();
    }
}
