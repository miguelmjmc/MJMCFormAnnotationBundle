<?php

/*
 * This file is part of the MJMCAssetBundle package.
 *
 * (c) Miguel Malavé <miguel.mjmc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MJMC\FormAnnotationBundle\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use MJMC\FormAnnotationBundle\Annotation\Field;
use MJMC\FormAnnotationBundle\Annotation\Listener;
use MJMC\FormAnnotationBundle\Annotation\Subscriber;

/**
 * Class FormReader
 *
 * @author Miguel Malavé <miguel.mjmc@gmail.com>
 */
class FormReader implements FormReaderInterface
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;


    /**
     * FormReader constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->annotationReader = new AnnotationReader();
    }

    /**
     * {@inheritdoc}
     */
    public function getFields(string $class, string $group = 'default'): array
    {
        /** @var \ReflectionClass $reflectionClass */
        $reflectionClass = new \ReflectionClass($class);

        /** @var array $properties */
        $properties = $reflectionClass->getProperties();

        /** @var array $collections */
        $collections = array();

        /** @var \ReflectionProperty $property */
        foreach ($properties as $propertyKey => $property) {
            /** @var array $annotations */
            $annotations = $this->annotationReader->getPropertyAnnotations($property);

            /** @var mixed $annotation */
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Field) {
                    if (in_array($group, $annotation->groups)) {
                        $annotation->setName($property->getName());

                        $collections[$annotation->priority][] = $annotation;
                    }
                }
            }
        }

        krsort($collections);

        /** @var array $fields */
        $fields = array();

        /** @var array $collection */
        foreach ($collections as $collection) {
            /** @var Field $field */
            foreach ($collection as $field) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners(string $class, string $group = 'default'): array
    {
        /** @var \ReflectionClass $reflectionClass */
        $reflectionClass = new \ReflectionClass($class);

        /** @var array $methods */
        $methods = $reflectionClass->getMethods();

        /** @var array $listeners */
        $listeners = array();

        /** @var \ReflectionMethod $method */
        foreach ($methods as $method) {
            /** @var array $annotations */
            $annotations = $this->annotationReader->getMethodAnnotations($method);

            /** @var mixed $annotation */
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Listener) {
                    if (in_array($group, $annotation->groups)) {
                        $annotation->setClosure($method->getClosure($reflectionClass->newInstanceWithoutConstructor()));

                        $listeners[] = $annotation;
                    }
                }
            }
        }

        return $listeners;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribers(string $class, string $group = 'default'): array
    {
        /** @var \ReflectionClass $reflectionClass */
        $reflectionClass = new \ReflectionClass($class);

        /** @var array $subscribers */
        $subscribers = array();

        /** @var array $annotations */
        $annotations = $this->annotationReader->getClassAnnotations($reflectionClass);

        /** @var mixed $annotation */
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Subscriber) {
                if (in_array($group, $annotation->groups)) {
                    $subscribers[] = $annotation;
                }
            }
        }

        return $subscribers;
    }
}
