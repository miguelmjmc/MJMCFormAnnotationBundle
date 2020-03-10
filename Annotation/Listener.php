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
 * Class Listener
 *
 * @author Miguel Malavé <miguel.mjmc@gmail.com>
 *
 * @Annotation
 * @Target("METHOD")
 */
class Listener
{
    /**
     * @var string
     *
     * @Enum({"PRE_SET_DATA", "POST_SET_DATA", "PRE_SUBMIT", "SUBMIT", "POST_SUBMIT"})
     * @Required
     */
    public $event;

    /**
     * @var int
     */
    public $priority = 0;

    /**
     * @var array
     */
    public $groups = array('default');

    /**
     * @var \Closure
     */
    protected $closure;


    /**
     * @param \Closure $closure
     *
     * @return void
     */
    public function setClosure(\Closure $closure): void
    {
        $this->closure = $closure;
    }

    /**
     * @return \Closure|null
     */
    public function getClosure(): ?\Closure
    {
        return $this->closure;
    }
}
