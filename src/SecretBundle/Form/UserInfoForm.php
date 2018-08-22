<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 24.06.2018
 * Time: 12:06
 */
namespace SecretBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserInfoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Imię i nazwisko:',
            ])
            ->add('picturePath', FileType::class, [
                'required' => false,
                'label' => 'Zdjęcie:',
            ])
            ->add('clubCardNumber', TextType::class, [
                'required' => true,
                'label' => 'Numer karty klubowej:',
                'attr' => [
                    'placeholder' => 'Numer składa się z 6 cyfr',
                ]
            ])
            ->add('paymentAmmount', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Small - 70 pln' => 0,
                    'Medium - 90 pln' => 1,
                    'Large - 100 pln' => 2,
                ],
                'label' => 'Typ karnetu:',
            ])
            ->add('paymentDate', DateType::class,[
                'required' => true,
                'label' => 'Karnet opłacony do:',
            ]);

        $builder->add('userExperienceRelation',UserExperienceForm::class);

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $userInfo = $event->getData();
//            $form = $event->getForm();
//
////            if (!$userInfo || null === $userInfo->getId()) {
//                $form->add('userExperienceRelation', CollectionType::class, array(
//                    'entry_type' => UserExperienceForm::class,
//                    'entry_options' => array('label' => 'Doświadczenie:'),
//                ));
////            }
//        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mappingOn' => true,
            'validation_groups' => ['UserInfo'],
        ]);

        $resolver->setAllowedTypes('mappingOn', 'boolean');
    }


}