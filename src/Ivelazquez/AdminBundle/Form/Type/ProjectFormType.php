<?php

namespace Ivelazquez\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('description')
            ->add('finishDate', 'date', array(
                'widget'   => 'single_text',
                'format'   => 'dd/M/yyyy',
                'required' => false
            ))
            ->add('country', 'country', array(
                'empty_value' => '--Select a country--',
                'required'    => false
            ))
            ->add('status', 'choice', array(
                'expanded' => true,
                'choices'  => array(
                    'finished'    => 'finished',
                    'maintenance' => 'maintenance',
                    'developing'  => 'developing'
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ivelazquez\AdminBundle\Entity\Project',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'project';
    }

}