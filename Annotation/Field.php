<?php

/*
 * This file is part of the MJMCAssetBundle package.
 *
 * (c) Miguel Malavé <miguel.mjmc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MJMC\FormAnnotationBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Field
 *
 * @author Miguel Malavé <miguel.mjmc@gmail.com>
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Field
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $options = array();

    /**
     * @var array
     */
    public $groups = array('default');

    /**
     * @var int
     */
    public $priority = 0;

    /**
     * @var string
     */
    protected $name;


    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        if (false === strpos($this->type, '\\')) {
            if (class_exists('Symfony\Component\Form\Extension\Core\Type\\'. $this->type)) {
                return 'Symfony\Component\Form\Extension\Core\Type\\'. $this->type;
            }

            if ('AnnotationType' === $this->type){
                return 'MJMC\FormAnnotationBundle\Form\AnnotationType';
            }
        }

        return $this->type;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
