<?php

declare(strict_types=1);

namespace Loevgaard\SyliusBrandPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


final class BrandType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber(null, [
                'label' => 'loevgaard_sylius_brand.form.brand.code',
            ]))
            ->add('name', TextType::class, [
                'label' => 'loevgaard_sylius_brand.form.brand.name',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => BrandImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'loevgaard_sylius_brand.form.brand.images',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
            ])
            ->add('products', ProductAutocompleteChoiceType::class, [
                'label' => 'Products',
                'multiple' => true,
                'required' => true,
                'resource' => 'sylius.product',
                'choice_value' => 'code',
                'choice_name' => 'name',
                'data' => $options['data']->getProducts(),
                'data_class' => null
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'loevgaard_sylius_brand_brand';
    }
}
