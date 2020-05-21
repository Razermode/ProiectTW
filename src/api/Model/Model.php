<?php

namespace Api\Model;

use Api\Exception\Exception as ApiException ;
use Api\Exception\NotFound as NotFoundException;
use Api\Exception\Validation as ValidationException;

abstract class Model
{
    protected $data = null;
    protected $dataFile = null;

    // Hold the class instance.
    protected static $instance = [];
    private function __construct()
    {
        //do nothing, we need this private
    }

    /**
     * @return User|Task
     */
    public static function getInstance() : Model
    {
        $class = static::class;
        $instance = self::$instance[$class] ?? null;

        if (!$instance) {
            self::$instance[$class] = new $class();
        }
        return self::$instance[$class];
    }
    
        /**
     * @param int $id
     * @return array
     * @throws ApiException
     */
    public function loadData($id = null)
    {
        if (is_null($this->data)) {
            if (!$this->dataFile) {
                throw new ApiException("No dataFile");
            }
            if (!defined('DATA_ROOT')) {
                throw new ApiException("Cannot access tasks storage");
            }
            $file = DATA_ROOT.'/'.$this->dataFile;
            if (!file_exists($file) || !is_readable($file)) {
                throw new ApiException("Cannot read tasks data");
            }
            $tasks = json_decode(file_get_contents($file), true);
            if (!$tasks) {
                throw new ApiException("Tasks data is corrupted: " . json_last_error_msg());
            }
            foreach($tasks as $task) {
                $this->data[$task['id']] = $task;
            }
        }

        if ($id) {
            if (!is_numeric($id) || $id <=0) {
                throw new ValidationException("Invalid task ID");
            }
            if (!isset($this->data[$id])) {
                throw new NotFoundException("Task not found");
            }
            return $this->data[$id];
        }
        return $this->data;
    }

    /**
     * @param array $data
     * @throws ApiException
     */
    public function saveData($data)
    {

        if (!$this->dataFile) {
            throw new ApiException("No dataFile");
        }

        $this->data = $data;
        if (!defined('DATA_ROOT')) {
            throw new ApiException("Cannot access tasks storage");
        }
        $file = DATA_ROOT.'/'.$this->dataFile;
        if (!file_exists($file) || !is_writable($file)) {
            throw new ApiException("Cannot write tasks data");
        }
        file_put_contents($file, json_encode($this->data, JSON_PRETTY_PRINT));
    }    
}