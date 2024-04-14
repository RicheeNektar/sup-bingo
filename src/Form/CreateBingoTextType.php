<?php

namespace App\Form;

use App\Constraint\UniqueBingoText;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateBingoTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction('create');

        $builder
            ->add(
                'text',
                TextType::class,
                [
                    'constraints' => [
                        new Length(max: 128),
                        new NotBlank(),
                        new UniqueBingoText([
                            'bingo' => $options['bingo']
                        ]),
                    ],
                ],
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => '<i class="bi bi-floppy-fill"></i>',
                    'label_html' => true,
                    'attr' => [
                        'class' => 'btn btn-primary float-end'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->define('bingo');
    }
}
