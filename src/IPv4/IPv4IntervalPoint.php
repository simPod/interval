<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4;

use GpsLab\Component\Interval\Exception\InvalidPointTypeException;
use GpsLab\Component\Interval\PointInterface;

class IPv4IntervalPoint implements PointInterface
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @var int
     */
    private $long;

    /**
     * @param string $ip
     */
    public function __construct($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            throw InvalidPointTypeException::create('IPv4', $ip);
        }

        $this->ip = $ip;
        $this->long = ip2long($ip);
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->long;
    }

    /**
     * @param IPv4IntervalPoint $point
     *
     * @return bool
     */
    public function eq(IPv4IntervalPoint $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param IPv4IntervalPoint $point
     *
     * @return bool
     */
    public function neq(IPv4IntervalPoint $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param IPv4IntervalPoint $point
     *
     * @return bool
     */
    public function lt(IPv4IntervalPoint $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param IPv4IntervalPoint $point
     *
     * @return bool
     */
    public function lte(IPv4IntervalPoint $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param IPv4IntervalPoint $point
     *
     * @return bool
     */
    public function gt(IPv4IntervalPoint $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param IPv4IntervalPoint $point
     *
     * @return bool
     */
    public function gte(IPv4IntervalPoint $point)
    {
        return $this->value() >= $point->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->ip;
    }
}