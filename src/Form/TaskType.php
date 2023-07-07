<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            //->add('CreationDate')
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'To Do' => 'todo',
                    'In Progress' => 'in_progress',
                    'Done' => 'done',
                ],
                'placeholder' => 'Select status',
                'required' => true,
            ])            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
