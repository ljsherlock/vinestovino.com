<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace IAWP_SCOPED\Symfony\Component\Console\Helper;

use IAWP_SCOPED\Symfony\Component\Console\Input\InputAwareInterface;
use IAWP_SCOPED\Symfony\Component\Console\Input\InputInterface;
/**
 * An implementation of InputAwareInterface for Helpers.
 *
 * @author Wouter J <waldio.webdesign@gmail.com>
 * @internal
 */
abstract class InputAwareHelper extends Helper implements InputAwareInterface
{
    protected $input;
    /**
     * {@inheritdoc}
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }
}
