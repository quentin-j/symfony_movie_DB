<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'rôle',
                'multiple' =>true,
                'expanded' =>true,
                'choices' => [
                    'Administrateur' => "ROLE_ADMIN",
                    'Utilisateur' => "ROLE_USER",
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe ne correspondent pas !',
                'required' => false,
                'first_options' => ['label' => 'Mot de Pasee'],
                'second_options' => ['label' => 'Répétez le mot de Pasee'],

                'mapped' => false   // <= On spécifie au composant formulaire de na pas enregistrer cette valeur dans l'objet
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
