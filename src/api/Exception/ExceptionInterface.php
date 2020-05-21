<?php
namespace Api\Exception;

interface ExceptionInterface
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getMessage();


    /**
     * @return int
     */
    public function getHttpResponseCode();
}