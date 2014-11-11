<?php

namespace Brick\Queue;

/**
 * A Job assigned by the Queue.
 */
class Job
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $pid;

    /**
     * @var array
     */
    private $data;

    /**
     * @param integer $id   The job id.
     * @param integer $pid  The process id.
     * @param array   $data An associative array containing the job data.
     */
    public function __construct($id, $pid, array $data)
    {
        $this->id   = $id;
        $this->pid  = $pid;
        $this->data = $data;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $key
     *
     * @return string
     *
     * @throws \RuntimeException If the requested key does not exist.
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new \RuntimeException(sprintf('The requested key "%s" does not exist', $key));
    }
}
