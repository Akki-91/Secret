<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 28.06.2018
 * Time: 17:12
 */

namespace SecretBundle\Form;

use SecretBundle\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->add('name');

        $builder
            ->add('name', ChoiceType::class,[
                'required' => true,
                'choices' => [
                    'white' => 1,
                    'blue'  => 2,
                    'purple'=> 3,
                    'brown' => 4,
                    'black' => 5,
                ],
                'label' => 'Kolor pasa',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Tag::class,
        ));
    }
}