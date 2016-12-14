<?php

namespace CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CourseBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * Class CourseController
 * @Route("/course")
 */
class CourseController extends Controller
{
    /**
     * @Route("/", name="course_index")
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBy(array('owner' => $user));
        $dashboards = $user->getDashboards();
        return $this->render('CourseBundle:Default:index.html.twig', array("courses" => $courses, "dashboards" => $dashboards));
    }

    /**
     * @Route("/new", name="course_new")
     */
    public function newAction(Request $request)
    {
        $course = new Course();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $course->setOwner($user);

        $form = $this->createFormBuilder($course)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('start_date', DateType::class)
            ->add('end_date', DateType::class)
            ->add('meeting_time', TimeType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Course'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $course = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            return $this->redirectToRoute('course_index');

        }

        return $this->render('CourseBundle:Default:new.html.twig', array ('form' => $form->createView()));
    }

    /**
     * @Route("/view/{courseid}", name="course_view")
     */
    public function viewAction($courseid)
    {
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($courseid);

        return $this->render('CourseBundle:Default:view.html.twig', array('course' => $course));
    }
}
