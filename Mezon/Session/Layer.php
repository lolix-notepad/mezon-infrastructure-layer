<?php
namespace Mezon\Session;

use Mezon\Conf\Conf;

/**
 * Configuration routines
 *
 * @package InfrastructureLayer
 * @subpackage Session
 * @author Dodonov A.A.
 * @version v.1.0 (2021/11/13)
 * @copyright Copyright (c) 2021, aeon.org
 */

class Layer
{

    /**
     * Was the session started
     *
     * @var boolean
     */
    private static $sessionWasStarted = false;

    /**
     * Method starts session
     *
     * @return bool true if the session was started, false otherwise
     */
    public static function startSession(): bool
    {
        if (Conf::getConfigValueAsString('session/layer', 'real') === 'real') {
            if (self::$sessionWasStarted) {
                return true;
            } else {
                // @codeCoverageIgnoreStart
                return self::$sessionWasStarted = session_start();
            }
            // @codeCoverageIgnoreEnd
        } else {
            return self::$sessionWasStarted = true;
        }
    }

    /**
     * Method returns information was the session started
     *
     * @return bool true if the session was started, false otherwise.
     */
    public static function wasSessionStarted(): bool
    {
        return self::$sessionWasStarted;
    }

    /**
     * Method returns session's name
     *
     * @return string session's name
     */
    public static function sessionName(): string
    {
        if (Conf::getConfigValueAsString('session/layer', 'real') === 'real') {
            // @codeCoverageIgnoreStart
            return session_name();
            // @codeCoverageIgnoreEnd
        } else {
            return 'session-name';
        }
    }

    /**
     * Cookies
     *
     * @var list<array{name: string, value: string, expires: int, path: string, domain: string, secure: bool, httponly: bool}>
     */
    public static $cookies = [];

    /**
     * Method sets cookie
     *
     * @param string $name
     *            cookie name
     * @param string $value
     *            cookie value
     * @param int $expires
     *            expires
     * @param string $path
     *            paht of the cookie
     * @param string $domain
     *            domain
     * @param bool $secure
     *            secure?
     * @param bool $httponly
     *            http only?
     * @return bool true on success, false otherwise
     */
    public static function setCookie(
        string $name,
        string $value = "",
        int $expires = 0,
        string $path = "",
        string $domain = "",
        bool $secure = false,
        bool $httponly = false): bool
    {
        if (Conf::getConfigValueAsString('session/layer', 'real') === 'real') {
            // @codeCoverageIgnoreStart
            return setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
            // @codeCoverageIgnoreEnd
        } else {
            self::$cookies[] = [
                'name' => $name,
                'value' => $value,
                'expires' => $expires,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly
            ];
            return true;
        }
    }

    /**
     * Method setups session id
     *
     * @param string $id
     *            id of the session
     * @return string session's name
     */
    public static function sessionId(string $id = ''): string
    {
        if (Conf::getConfigValueAsString('session/layer', 'real') === 'real') {
            // @codeCoverageIgnoreStart
            return session_id($id);
            // @codeCoverageIgnoreEnd
        } else {
            return 'session-id';
        }
    }

    /**
     * Write session data and end session
     *
     * @return bool true on success or false on failure
     */
    public static function sessionWriteClose(): bool
    {
        self::$sessionWasStarted = false;

        if (Conf::getConfigValueAsString('session/layer', 'real') === 'real') {
            // @codeCoverageIgnoreStart
            return session_write_close();
            // @codeCoverageIgnoreEnd
        } else {
            return true;
        }
    }

    // TODO make article for dev.to about this abstraction
}
