<?php

/*
 * This file is part of the MJMCAssetBundle package.
 *
 * (c) Miguel Malavé <miguel.mjmc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MJMC\FormAnnotationBundle\Form;

use MJMC\FormAnnotationBundle\Annotation\Field;
use MJMC\FormAnnotationBundle\Annotation\Listener;
use MJMC\FormAnnotationBundle\Annotation\Subscriber;
use MJMC\FormAnnotationBundle\Reader\FormReader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AnnotationType
 *
 * @author Miguel Malavé <miguel.mjmc@gmail.com>
 */
class AnnotationType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data_class']) {
            /** @var string $entity */
            $entity = $options['data_class'];
        } elseif (is_object($builder->getData())) {
            /** @var string $entity */
            $entity = (new \ReflectionClass($builder->getData()))->getName();
        } else {
            throw new \Exception('The form data or the form option data_class must be provided for build the form.');
        }

        /** @var FormReader $formReader */
        $formReader = new FormReader();

        /** @var array $fields */
        $fields = $formReader->getFields($entity, $options['form_group']);

        /** @var array $listeners */
        $listeners = $formReader->getListeners($entity, $options['form_group']);

        /** @var array $subscribers */
        $subscribers = $formReader->getSubscribers($entity, $options['form_group']);

        if (0 === count($fields) && 0 === count($listeners) && 0 === count($subscribers)) {
            throw new \Exception("The class $entity does not contain any form annotation.");
        }

        /** @var Field $field */
        foreach ($fields as $field) {
            $builder->add($field->getName(), $field->getType(), $field->options);
        }

        /** @var Listener $listener */
        foreach ($listeners as $listener) {
            $eventName = (new \ReflectionClass(FormEvents::class))->getConstant($listener->event);

            $builder->addEventListener($eventName, $listener->getClosure(), $listener->priority);
        }

        /** @var Subscriber $subscriber */
        foreach ($subscribers as $subscriber) {
            /** @var EventSubscriberInterface|object $eventSubscriber */
            $eventSubscriber = (new \ReflectionClass($subscriber->class))->newInstance();

            $builder->addEventSubscriber($eventSubscriber);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('form_group' => 'default')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'form';
    }
}
