<?php

namespace Sf\ArcherySportsManagerBundle\Form;

use Sf\ArcherySportsManagerBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData();
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email',
            ])
            ->add('denomitation',TextType::class)
            ->add('plainPassword', PasswordType::class, [
                'label' => 'password',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
                'required' => false,
            ])
            ->add('isAdmin', CheckboxType::class, [
                'label'    => 'Compte d\'admin (Gestion des accès)',
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label'    => 'Compte actif',
                'required' => false,
            ])
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'SfArcherySportsManager_user';
    }


}
