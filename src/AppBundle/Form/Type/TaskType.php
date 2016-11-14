<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('deadline', DateTimeType::class, ['widget' => 'single_text'])
            ->add('completed', ChoiceType::class,
                [
                    'choices' => array(
                        'Yes' => true,
                        'No' => false
                    ),
                    'choices_as_values' => true,
                    'choice_value' => function ($currentChoiceKey) {
                        return $currentChoiceKey ? 'true' : 'false';
                    },
                ]
                )
            ->add('user', EntityType::class, [
                'class' => 'AppBundle:User',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'task';
    }
}
