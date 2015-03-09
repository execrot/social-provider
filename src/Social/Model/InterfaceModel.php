<?php

namespace Social\Model;

/**
 * Interface InterfaceModel
 * @package Social\Model
 */
interface InterfaceModel
{
    /**
     * @param array $modelData
     * @return void
     */
    public function populate(array $modelData);

    /**
     * @return array
     */
    public function asArray();
}