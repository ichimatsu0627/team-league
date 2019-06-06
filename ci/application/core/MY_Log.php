<?php
class MY_Log extends CI_Log {

    private function _write_log($level, $msg)
    {
        if (isset($this->_levels[$level]))
        {
            $filepath = $this->_log_path.'log-'.date('Y-m-d').'.'.$this->_file_ext;
        }
        else
        {
            $filepath = $this->_log_path.$level.'.'.$this->_file_ext;
        }

        if (!is_string($msg)) {

            $msg = print_r($msg, true);
        }

        $message = '';

        if ( ! file_exists($filepath))
        {
            $newfile = TRUE;
            // Only add protection to php files
            if ($this->_file_ext === 'php')
            {
                $message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
            }
        }

        if ( ! $fp = @fopen($filepath, 'ab'))
        {
            return FALSE;
        }

        flock($fp, LOCK_EX);

        // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
        if (strpos($this->_date_fmt, 'u') !== FALSE)
        {
            $microtime_full = microtime(TRUE);
            $microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
            $date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
            $date = $date->format($this->_date_fmt);
        }
        else
        {
            $date = date($this->_date_fmt);
        }

        $message .= $this->_format_line($level, $date, $msg);

        for ($written = 0, $length = self::strlen($message); $written < $length; $written += $result)
        {
            if (($result = fwrite($fp, self::substr($message, $written))) === FALSE)
            {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newfile) && $newfile === TRUE)
        {
            chmod($filepath, $this->_file_permissions);
        }

        return is_int($result);
    }

    /**
     * Write Log File
     *
     * Generally this function will be called using the global log_message() function
     *
     * @param string  $level  The error level: 'error', 'debug' or 'info'
     * @param string  $msg    The error message
     * @return    bool
     */
    public function write_log($level, $msg)
    {
        if ($this->_enabled === FALSE)
        {
            return FALSE;
        }

        $upper_level = strtoupper($level);

        if ( ! isset($this->_levels[$upper_level]))
        {
            return $this->_write_log($level, $msg);
        }

        $level = $upper_level;

        if (
            isset($this->_levels[$level])
            && (($this->_levels[$level] > $this->_threshold))
            && ! isset($this->_threshold_array[$this->_levels[$level]])
        )
        {
            return FALSE;
        }

        return $this->_write_log($level, $msg);
    }

}
