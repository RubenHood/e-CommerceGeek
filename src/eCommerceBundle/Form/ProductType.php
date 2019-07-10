<?php

namespace eCommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use eCommerceBundle\Entity\Product;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $producto = new Product;

        //obtenemos la entidad pasada previamente
        $producto = $builder->getData();

        $cat = $producto->getCategory();

        $builder
            ->add('name', TextType::class, ['label' => 'Nombre'])
            ->add('description', TextareaType::class, ['label' => 'Descripcion'])
            ->add('price', MoneyType::class, ['label' => 'Precio'])
            ->add('category', ChoiceType::class, [
                'choices'  => [
                    'Man' => 'Man',
                    'Woman' => 'Woman',
                    'Ninja' => 'Ninja',
                    'Frog' => 'Frog',
                    'Power' => 'Power'
                ],
                'data' => $cat
            ])
            ->add('img', TextType::class, ['label' => 'Imagen']);
    }
}
