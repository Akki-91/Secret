<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 28.06.2018
 * Time: 15:57
 */

namespace SecretBundle\Form;

use SecretBundle\Entity\UserExperience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserExperienceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('belt', ChoiceType::class,[
                'required' => true,
                'choices' => [
                  'white' => 1,
                  'blue'  => 2,
                  'purple'=> 3,
                  'brown' => 4,
                  'black' => 5,
                ],
                'label' => 'Kolor pasa',
            ])
            ->add('stripes', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                ],
                'label' => 'Ilość belek',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserExperience::class,
        ));
    }

}