<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 18.07.2018
 * Time: 23:37
 */

namespace SecretBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SecretBundle\Entity\Product;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', ChoiceType::class,[
                'required' => true,
                'choices' => [
                    'świeże' => 'swieze',
                    'stare'  => 'stare',
                    'czarne'=> 'czarne',
                ],
                'label' => 'Opis produktu:',
            ])
            ->add('ammountAvaible', ChoiceType::class,[
                'required' => true,
                'choices' => [
                    '111' => 111,
                    '222'  => 222,
                    '333'=> 333,
                ],
                'label' => 'Ilość:',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }
}