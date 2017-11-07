<?php

declare(strict_types=1);

namespace Brick\Queue;

/**
 * A queued Message.
 */
class Message
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $pid;

    /**
     * @var array
     */
    private $data;

    /**
     * @param int   $id   The message id.
     * @param int   $pid  The process id.
     * @param array $data An associative array containing the message data.
     */
    public function __construct(int $id, int $pid, array $data)
    {
        $this->id   = $id;
        $this->pid  = $pid;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPid() : int
    {
        return $this->pid;
    }

    /**
     * @return array
     */
    public function getData() : array
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
    public function get(string $key) : string
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new \RuntimeException(sprintf('The requested key "%s" does not exist', $key));
    }
}
