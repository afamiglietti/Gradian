<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CourseBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;;


/**
 * @Route("/")
 */
class HomeController extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        return $this->render('HomeBundle:Default:index.html.twig');
    }
}
