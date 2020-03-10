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
 * Class Subscriber
 *
 * @author Miguel Malavé <miguel.mjmc@gmail.com>
 *
 * @Annotation
 * @Target("CLASS")
 */
class Subscriber
{
    /**
     * @var string
     *
     * @Required
     */
    public $class;

    /**
     * @var array
     */
    public $groups = array('default');
}
