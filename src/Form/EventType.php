<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType ;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array('attr' => array('placeholder' => 'Entrer  le titre de l\'évenement')))
            ->add('libelle',TextareaType::class, array(
                'attr' => array('id' => 'editor'),'required' => false)
                                                                  )
            ->add('dateDepart', DateType::class)
            ->add('urlTof', FileType::class, array('label' => 'Télécharcher une image (PNG file)'))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer l\'évenement'))

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Event::class,
        ));
    }
}
