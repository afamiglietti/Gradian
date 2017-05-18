<?php

namespace AssignmentBundle\Form;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AssignmentBundle\Form\CategoryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class AssignmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $options['categories'];
        $builder
            ->add('name')
            ->add('recurring')
            ->add('maxSubmissions')
            ->add('dueDate')
            ->add('required')
            ->add('points')
            ->add('instructions')
            ->add('rubricLink')
            ->add('active')
            ->add('course', EntityType::class, array('class' => 'CourseBundle:Course', 'choice_label' => 'name'))
            ->add('category', EntityType::class, array('class' => 'AssignmentBundle:Category', 'choices' => $categories, 'choice_label' => 'name', 'placeholder' => ''))
            ->add('save', SubmitType::class, array('label' => 'Create Assignment'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssignmentBundle\Entity\Assignment',
            'categories' => null
        ));
    }
}
