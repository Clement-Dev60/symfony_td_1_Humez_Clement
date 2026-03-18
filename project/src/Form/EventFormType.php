<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Promo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add("title")
            ->add("description")
            ->add("startDate")
            ->add("endDate")
            ->add("room")
            ->add("speaker")
            ->add("promo", EntityType::class, [
                "class" => Promo::class,
                "choice_label" => "name",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Event::class,
        ]);
    }
}
