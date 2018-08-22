<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 18.07.2018
 * Time: 23:37
 */

namespace SecretBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CartForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cartName', TextType::class,[
                'label' => "Nazwa koszyka:",
            ]);

        $builder->add('product',ProductForm::class);
    }
}