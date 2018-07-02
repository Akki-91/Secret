<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 27.06.2018
 * Time: 17:31
 */

namespace SecretBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use SecretBundle\Entity\UserInfo;

class AllUsersListForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', EntityType::class,[
                'class' => 'SecretBundle:UserInfo',
                'choice_label' => 'name',
                'choice_value' => function (UserInfo $entity = null) {
                    return $entity ? $entity->getId() : '';
                },
                'label' => "Imię i nazwisko:",
            ])
        ;
    }
}