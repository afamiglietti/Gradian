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


class SubmitAssignmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $assignments = $options['assignments'];
        $builder
            ->add('link')
            ->add('comment')
            ->add('assignment', EntityType::class, array('class' => 'AssignmentBundle:Assignment', 'choices' => $assignments, 'choice_label' => 'name', 'placeholder' => ''))
            ->add('save', SubmitType::class, array('label' => 'Submit Assignment'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssignmentBundle\Entity\Submission',
            'assignments' => null
        ));
    }
}
