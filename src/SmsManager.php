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
        $this->withDriver($this->config['default']);
    }

    /**
     * Change the driver on the fly.
     *
     * @param $driver
     * @return $this
     * @throws \Exception
     */
    public function withDriver($driver)
    {
        $this->validateDriver($driver);

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
    public function send($message, $callback)
    {
        $driverObj = $this->getDriverInstance();
        $driverObj->message($message);

        call_user_func($callback, $driverObj);

        return $driverObj->send();
    }

    /**
     * Generate driver instance.
     *
     * @return mixed
     */
    public function getDriverInstance()
    {
        $class = $this->config['map'][$this->driver];
        return new $class($this->settings);
    }

    /**
     * Validate Parameters before sending.
     *
     * @param $driver
     * @throws \Exception
     */
    protected function validateDriver($driver)
    {
        if (empty($driver)) {
            throw new \Exception("Driver not selected or default driver does not exist.");
        }

        if (empty($this->config['drivers'][$driver]) || empty($this->config['map'][$driver])) {
            throw new \Exception("Driver not found in config file. Try updating the package.");
        }

        if (!class_exists($this->config['map'][$driver])) {
            throw new \Exception("Driver source not found. Please update the package.");
        }

        if (!($this->getDriverInstance() instanceof Contracts\DriverInterface)) {
            throw new \Exception("Driver must be an instance of Contracts\DriverInterface.");
        }
    }
}
