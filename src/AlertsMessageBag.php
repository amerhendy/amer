<?php
namespace Amerhendy\Amer;

use BadMethodCallException;
use Illuminate\Session\Store as Session;
use Illuminate\Support\MessageBag;
use Illuminate\Config\Repository as Config;

class AlertsMessageBag extends MessageBag
{
    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @param \Illuminate\Session\Store $session
     * @param \Illuminate\Config\Repository $config
     * @param array $messages
     * @return \Alerts\AlertsMessageBag
     */
    public function __construct(Session $session, Config $config, array $messages = [])
    {
        $this->config = $config;
        $this->session = $session;
        if ($session->has($this->getSessionKey())) {
            $messages = array_merge_recursive(
                $session->get($this->getSessionKey()),
                $messages
            );
        }
        parent::__construct($messages);
    }
    function testit($level = null)
    {
        $alerts = $this->session->get($this->getSessionKey());
        if(is_null($level) && isset($alerts)) {
            $totalCount = 0;
            foreach($alerts as $level => $messages) {
                if(is_array($alerts[$level])){
                    $totalCount = $totalCount + count($alerts[$level], COUNT_RECURSIVE);
                }
            }
            return $totalCount;
        } else {
            if(isset($alerts[$level])) {
                return count($alerts[$level], COUNT_RECURSIVE);
            }
        }
        return 0;
    }
    /**
     * Format an array of messages.
     *
     * @param array $messages
     * @param string $format
     * @param string $messageKey
     * @return array
     */
    protected function transform($messages, $format, $messageKey)
    {
        $messages = (array) $messages;

        // We will simply spin through the given messages and transform each one
        // replacing the :message place holder with the real message allowing
        // the messages to be easily formatted to each developer's desires.
        foreach ($messages as $key => &$message) {
            $replace = [':message', ':key'];

            if ($message instanceof MessageBag) {
                // Do nothing.
            } elseif (is_array($message)) {
                foreach ($message as $k => &$m) {
                    $m = str_replace($replace, [$m, $messageKey], $format);
                }
            } else {
                $message = str_replace($replace, [$message, $messageKey], $format);
            }
        }

        return $messages;
    }

    /**
     * Store the messages in the current session.
     *
     * @return \Alerts\AlertsMessageBag
     */
    public function flash()
    {
        $this->session->flash($this->getSessionKey(), $this->messages);

        return $this;
    }

    /**
     * Deletes all messages.
     *
     * @param bool $withSession
     *
     * @return \Alerts\AlertsMessageBag
     */
    public function flush($withSession = true)
    {
        $this->messages = [];

        if($withSession) {
            $this->session->forget($this->getSessionKey());
        }

        return $this;
    }

    /**
     * Checks to see if any messages exist.
     *
     * @param null $key A specific level you wish to check for.
     *
     * @return bool
     */
    public function has($level = null)
    {
        $alerts = $this->session->get($this->getSessionKey());
        if(is_null($level) && isset($alerts)) {
            return true;
        } else {
            if(isset($alerts[$level])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the number of messages in the message bag.
     *
     * @param null $level A specific level name you wish to count.
     *
     * @return int
     */
    public function count($level = null): int
    {
        $alerts = $this->session->get($this->getSessionKey());
        if(is_null($level) && isset($alerts)) {
            $totalCount = 0;
            foreach($alerts as $level => $messages) {
                if(is_array($alerts[$level])){
                    $totalCount = $totalCount + count($alerts[$level], COUNT_RECURSIVE);
                }
            }
            return $totalCount;
        } else {
            if(isset($alerts[$level])) {
                return count($alerts[$level], COUNT_RECURSIVE);
            }
        }
        return 0;
    }

    /**
     * Returns the alert levels from the config.
     *
     * @return array
     */
    public function getLevels()
    {
        return (array) $this->config->get('Amer.Amer.Alert.levels');
    }

    /**
     * Returns the session key from the config.
     *
     * @return string
     */
    protected function getSessionKey()
    {
        return $this->config->get('Amer.Amer.Alert.session_key');
    }

    /**
     * Returns the Illuminate Session Store.
     *
     * @return \Illuminate\Session\Store
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Returns the Illuminate Config Repository.
     *
     * @return \Illuminate\Config\Repository
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Dynamically handle alert additions.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        // Check if the method is in the allowed alert levels array.
        if (in_array($method, $this->getLevels())) {
            // Array of alerts.
            if (is_array($parameters[0])) {
                foreach ($parameters[0] as $parameter) {
                    $this->add($method, $parameter);
                }

                return $this;
            }

            // Single alert.
            return $this->add($method, $parameters[0]);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
