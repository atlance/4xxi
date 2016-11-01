<?php

namespace YahooFinanceBundle\Form;

use YahooFinanceBundle\Entity\StockQuote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockQuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('symbol', HiddenType::class, ['data' => '__value__'])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StockQuote::class,
        ));
    }

    public function getName()
    {
        return 'yahoo_finance_bundle_stock_quote_type';
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
