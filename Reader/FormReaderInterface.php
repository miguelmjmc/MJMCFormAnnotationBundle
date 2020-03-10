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

/**
 * Interface FormReaderInterface
 *
 * @author Miguel Malavé <miguel.mjmc@gmail.com>
 */
interface FormReaderInterface
{
    /**
     * @param string $class
     * @param string $group
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getFields(string $class, string $group = 'default'): array;

    /**
     * @param string $class
     * @param string $group
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getListeners(string $class, string $group = 'default'): array;

    /**
     * @param string $class
     * @param string $group
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getSubscribers(string $class, string $group = 'default'): array;
}
