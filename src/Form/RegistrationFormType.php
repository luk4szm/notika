<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', TextType::class)
			->add('firstName', TextType::class)
			->add('lastName', TextType::class)
			->add('plainPassword', PasswordType::class, [
				// instead of being set onto the object directly,
				// this is read and encoded in the controller
				'mapped' => false,
				'constraints' => [
					new NotBlank([
						'message' => 'Proszę wpisać hasło',
					]),
					new Length([
						'min' => 6,
						'minMessage' => 'Twoje hasło musi posiadać przynajmniej {{ limit }} znaków',
						// max length allowed by Symfony for security reasons
						'max' => 4096,
					]),
				],
			])
			->add('email', EmailType::class, [
			    'constraints' => [
			        new Length([
			           'max' => 191,
                       'maxMessage' => 'Adres e-mail może mieć maksymalnie 191 znaków'
                    ]),
                ],
            ])
			->add('member', CheckboxType::class, [
			    'required' => false,
            ]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
