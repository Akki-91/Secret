<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 27.06.2018
 * Time: 16:57
 */

namespace SecretBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use SecretBundle\Entity\UserInfo;

class CheckPresenceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clubCardNumber',TextType::class,[
                'label' => 'Numer karty klubowej:'
            ]);
    }
}
