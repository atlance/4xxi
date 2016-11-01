<?php

namespace YahooFinanceBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use YahooFinanceBundle\Entity\Portfolio;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['data' => ''])
            ->add('stock_quotes', CollectionType::class, [
                'entry_type' => StockQuoteType::class,
                'allow_add' => true,
            ]);
        $builder->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Portfolio::class,
        ));
    }

    public function getName()
    {
        return 'yahoo_finance_bundle_portfolio_type';
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
