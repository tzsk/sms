<?php
namespace Tzsk\Sms;


class SmsManager
{
    /**
     * Sms Configuration.
     *
     * @var null|object
     */
    protected $config = null;

    /**
     * Sms Driver Settings.
     *
     * @var null|object
     */
    protected $settings = null;

    /**
     * Sms Driver Name.
     *
     * @var null|string
     */
    protected $driver = null;

    /**
     * SmsManager constructor.
     */
    public function __construct()
    {
        $this->config = config('sms');
        $this->driver = $this->config['default'];
        $this->settings = $this->config['drivers'][$this->driver];
    }

    /**
     * Change the driver on the fly.
     *
     * @param $driver
     * @return $this
     */
    public function with($driver) {
        $this->driver = $driver;
        $this->settings = $this->config['drivers'][$this->driver];

        return $this;
    }

    /**
     * Send message.
     *
     * @param $message
     * @param $callback
     * @return mixed
     * @throws \Exception
     */
    public function send($message, $callback) {
        if (!$this->driver) {
            throw new \Exception("Driver not selected or default driver does not exist.");
        }
        if (empty($this->config['drivers'][$this->driver]) OR empty($this->config['map'][$this->driver])) {
            throw new \Exception("Driver not found in config file. Try updating the package.");
        }

        if (!class_exists($this->config['map'][$this->driver])) {
            throw new \Exception("Driver source not found. Please update the package.");
        }

        $class = $this->config['map'][$this->driver];
        $object = new $class($this->settings);
        call_user_func($callback, $object);

        return $object->send($message);
    }

}