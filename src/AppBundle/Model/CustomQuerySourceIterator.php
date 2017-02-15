<?php
/**
 * Created by PhpStorm.
 * User: tigran
 * Date: 2/15/17
 * Time: 3:08 AM
 */

namespace AppBundle\Model;


use Exporter\Source\DoctrineORMQuerySourceIterator;

class CustomQuerySourceIterator extends DoctrineORMQuerySourceIterator
{
    protected function getValue($value)
    {
        //if value is array or collection, creates string
        if (is_array($value) or $value instanceof \Traversable) {
            $result = array();
            foreach ($value as $item) {
                $result[] = $this->getValue($item);
            }
            $value = implode(', ', $result);
            //formated datetime output
        } elseif ($value instanceof \DateTime) {
            $value = $value->format('r');
        } elseif (is_object($value)) {
            $value = (string) $value;
        }

        return $value;
    }

}