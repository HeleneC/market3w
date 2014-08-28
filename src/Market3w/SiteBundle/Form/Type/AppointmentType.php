<?php

namespace Market3w\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Market3w\SiteBundle\Form\Type\HourForAppointmentType;
use Market3w\SiteBundle\Form\Type\Intranet\HourForEditAppointmentType;


class AppointmentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject', 'textarea', array(
            'label' => 'Décrivez votre demande',
            'attr'     => array('rows' => 4),            
        ));
        
        $builder->add('type', 'entity', array(
            'label'    => 'Comment souhaitez-vous rencontrer le conseiller ?', 
            'class'    => 'Market3wSiteBundle:AppointmentType',
            'property' => 'name',
            'expanded' => true,
            'multiple' => false,
        ));
        
        $builder->add('address', new AddressType(), array(
            'required' => false,
        ));
        
        $builder->add('skype', 'text', array(
            'label'    => 'Votre pseudo Skype',
            'required' => false,
            'mapped'   => false,
        ));
        
        $builder->add('date', 'date', array(
            'label'  => 'Quel jour souhaitez-vous rencontrer le conseiller ? ',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
        ));
        
        $builder->add('hour', 'choice', array(
            'label'     => "A quelle heure souhaitez-vous rencontrer le conseiller ? ",
            'choices'   => array('10:00' => '10:00', '11:00' => '11:00', '15:00' => '15:00', '16:00' => '16:00' ),
            'expanded'  => true,
            'multiple'  => false,
            'required'  => true,
        ));
        
        $builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));
    }
        
    public function onPostSubmitData(FormEvent $event)
    {
        $appointment = $event->getData();
        $form        = $event->getForm();
        
        $hourString   = $appointment->getHour();
        $hourDatetime = date_create_from_format('H:i', $hourString);
        $appointment->setHour($hourDatetime);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Market3w\SiteBundle\Entity\Appointment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appointment';
    }
}
