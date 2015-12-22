<?php
namespace Redbox\Scan\Adapter;
use Symfony\Component\Yaml;
use Redbox\Scan\Exception;
use Redbox\Scan\Report;

/**
 * Read and write files from a given ftp location.
 * see examples/ftp.php for a demonstration.
 *
 * @package Redbox\Scan\Adapter
 */
class Ftp implements AdapterInterface
{
    const FTP_MODE_ASCII  = FTP_ASCII;
    const FTP_MODE_BINARY = FTP_BINARY;

    protected $host     = '';
    protected $username = '';
    protected $password = '';
    protected $filename = '';
    protected $port     = 21;

    protected $timeout  = 90;
    protected $handle   = null;

    /**
     * You might think just connect to the ftp server from the constructor
     * but psr-4 dictates that autoloadable classes MUST NOT...
     *
     * Quote:
     * Autoloader implementations MUST NOT throw exceptions, MUST NOT raise errors of any level, and SHOULD NOT return a value.
     *
     * So we need to use authenticate() after we construct.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $filename
     * @param int $port;
     * @param int $timeout
     */
    public function __construct($host = "", $username = "", $password = "", $filename = "", $port = 21, $timeout = 90)
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
        $this->filename = $filename;
        $this->timeout  = $timeout;
        $this->port     = $port;
    }

    /**
     * We should be so nice to terminate the construction of we are done.
     */
    public function __destruct() {
        if ($this->handle) {
            ftp_close($this->handle);
        }
    }

    public function authenticate()
    {

        set_error_handler(
            function () {
            }
        );

        $this->handle  = ftp_connect($this->host, $this->port, $this->timeout);
        $authenticated = ftp_login($this->handle, $this->username, $this->password);

        restore_error_handler();

        if ($this->handle === false) {
            throw new Exception\RuntimeException('Could not connect to host: '.$this->host);
        }

        if ($authenticated == false) {
            throw new Exception\RuntimeException('Could not connect to host: '.$this->host);
        }
        return true;
    }

    /**
     * Read the previous scan results from the file system.
     *
     * @return array
     */
    public function read() {
        if (!$this->handle === false)
            return false;

        $stream = fopen('php://memory', 'w');
        if (!$stream)
            return false;

        $data   = '';
        if ($ret = ftp_nb_fget($this->handle, $stream, $this->filename, self::FTP_MODE_ASCII)) {
            while ($ret === FTP_MOREDATA) {
                rewind($stream);
                $data .=  stream_get_contents($stream);
                $ret = ftp_nb_continue($this->handle);
            }
            if ($ret != FTP_FINISHED) {
               return false;
            } else {
                $data = Yaml\Yaml::parse($data);
                return $data;
            }
        }
        return false;
    }

    // TODO: This should be an universial exception
    /**
     * Write the report to the filesystem so we can reuse it
     * at a later stace when we invoke Redbox\Scan\ScanService's scan() method.
     *
     * @param Report\Report|null $report
     * @return bool
     */
    public function write(Report\Report $report = null) {
        if ($this->handle === false)
            return false;

        if ($report) {

            $stream = fopen('php://memory', 'w+');
            if (!$stream) return false;
            $data = $report->toArray();
            $data = Yaml\Yaml::dump($data, 99);

            fwrite($stream, $data);
            rewind($stream);

            if(ftp_fput($this->handle, $this->filename, $stream, self::FTP_MODE_ASCII)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

}