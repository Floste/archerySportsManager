<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 04/07/2017
 * Time: 11:39
 */

namespace Sf\ArcherySportsManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class LostPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email',
            ])
/*
 * todo enable recaptcha
            ->add('recaptcha', EWZRecaptchaType::class, [
                'label'       => false,
                'mapped'      => false,
                'constraints' => [
                    new RecaptchaTrue()
                ]
            ])
*/
        ;
    }
}
