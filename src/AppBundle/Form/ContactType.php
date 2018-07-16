<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Subject;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('first_name', TextType::class, array('required' => false,
                                                                      'constraints' => array(new Length(array('min' => 3)),
                                                                                             new NotBlank(array('message' => 'Le champs First Name est obligatoire',)))))
            ->add('last_name', TextType::class, array('required' => false,
                                                                 'constraints' => array(new Length(array('min' => 3)),
                                                                                        new NotBlank(array('message' => 'Le champs Last Name est obligatoire',)))))
            ->add('email', EmailType::class, array('required' => false))
            ->add('subject', ChoiceType::class, array(
                'choices'  => $options['em']->getRepository('AppBundle:Subject')->findAll(),

                'choice_label' => function (Subject $subject) {
                    return $subject->getSubject();
                },
                'choice_value' => function (Subject $subject = null) {
                    return $subject ? $subject->getSubjectCode() : '';
                },
                'choices_as_values' => true,
                'required' => false))
            ->add('message', TextareaType::class, array('required' => false))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }


    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'em' => null,
        ));
    }
}
