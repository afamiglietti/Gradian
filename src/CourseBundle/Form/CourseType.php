<?php

namespace CourseBundle\Form;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Time;


class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('maxPoints', TextType::class, array('label' => 'Maximum Class Score'))
            ->add('gradeA', IntegerType::class, array('label' => 'Points for A Grade'))
            ->add('gradeB', IntegerType::class, array('label' => 'Points for B Grade'))
            ->add('gradeC', IntegerType::class, array('label' => 'Points for C Grade'))
            ->add('gradeD', IntegerType::class, array('label' => 'Points for D Grade'))
            ->add('start_date', DateType::class, array('label' => 'Course Start Date'))
            ->add('end_date', DateType::class, array('label' => 'Course End Date'))
            ->add('meeting_time', TimeType::class, array('label' => 'Class Meeting Time'))
            ->add('save', SubmitType::class, array('label' => 'Save Course'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CourseBundle\Entity\Course',
        ));
    }
}
