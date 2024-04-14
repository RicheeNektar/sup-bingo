<?php

namespace App\Form;

use App\Constraint\UniqueUsername;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($this->urlGenerator->generate('user_create'))
            ->add(
                'username',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(max: 32),
                        new NotBlank(),
                        new UniqueUsername(),
                    ]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(min: 8),
                        new Regex('/[a-z]/', 'Must contain at least one lowercase character'),
                        new Regex('/[A-Z]/', 'Must contain at least one uppercase character'),
                        new Regex('/\d/', 'Must contain at least one number'),
                    ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn btn-primary float-end'
                    ]
                ]
            )
        ;
    }
}
