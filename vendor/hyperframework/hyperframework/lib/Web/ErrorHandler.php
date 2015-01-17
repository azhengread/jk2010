<?php
namespace Hyperframework\Web;

use Hyperframework\Common\Config;
use Hyperframework\Common\ViewTemplate;
use Hyperframework\Common\ErrorHandler as Base;

class ErrorHandler extends Base {
    private $isDebuggerEnabled;
    private $startupOutputBufferLevel;

    public function __construct() {
        parent::__construct();
        $this->isDebuggerEnabled =
            Config::getBoolean('hyperframework.error_handler.debug', false);
        if (ini_get('display_errors') === '1') {
            if ($this->isDebuggerEnabled !== false) {
                $this->isDebuggerEnabled = true;
            }
        } else {
            if ($this->isDebuggerEnabled !== true) {
                $this->isDebuggerEnabled = false;
            }
        }
        if ($this->isDebuggerEnabled) {
            ob_start();
        }
        $this->startupOutputBufferLevel = ob_get_level();
    }

    protected function displayFatalError() {
        $isError = $this->isError();
        $source = $this->getSource();
        if ($this->isDebuggerEnabled()) {
            $headers = headers_list();
            if (headers_sent() === false) {
                $this->resetHttpHeaders();
            }
            $outputBuffer = $this->getOutputBuffer();
            $this->executeDebugger($headers, $outputBuffer);
        } elseif (ini_get('display_errors') === '1') {
            $this->displayError();
        } elseif (headers_sent() === false) {
            $this->resetHttpHeaders();
            $this->deleteOutputBuffer();
            $this->renderErrorView();
        }
    }

    private function resetHttpHeaders() {
        header_remove();
        $source = $this->getSource();
        if ($source instanceof HttpException) {
            foreach ($source->getHttpHeaders() as $header) {
                header($header);
            }
        } else {
            header('HTTP/1.1 500 Internal Server Error');
        }
    }

    protected function writeLog() {
        if ($this->getSource() instanceof HttpException) {
            $shouldLogHttpException = Config::getBoolean(
                'hyperframework.error_handler.log_http_exception', false
            );
            if ($shouldLogHttpException === false) {
                return;
            }
        }
        parent::writeLog();
    }

    private function deleteOutputBuffer() {
        $obLevel = ob_get_level();
        while ($obLevel > $this->startupOutputBufferLevel) {
            ob_end_clean();
            --$obLevel;
        }
    }

    protected function getOutputBuffer() {
        $startupOutputBufferLevel = $this->getStartupOutputBufferLevel();
        $outputBufferLevel = ob_get_level();
        if ($outputBufferLevel < $startupOutputBufferLevel) {
            return false;
        }
        while ($outputBufferLevel > $startupOutputBufferLevel) {
            ob_end_flush();
            --$outputBufferLevel;
        }
        $content = ob_get_contents();
        if ($content === false) {
            return $content;
        }
        ob_end_clean();
        if ($content === '') {
            return $content;
        }
        $charset = Config::getString(
            'Hyperframework.error_handler.output_buffer_charset', ''
        );
        $encoding = null;
        foreach (headers_list() as $header) {
            $header = str_replace(' ', '', strtolower($header));
            if ($header === 'content-encoding:gzip') {
                $encoding = 'gzip';
            } elseif ($header === 'content-encoding:deflate') {
                $encoding = 'deflate';
            } elseif (strncmp('content-type:', $header, 13) === 0) {
                $header = substr($header, 13);
                $segments = explode(';', $header);
                foreach ($segments as $segment) {
                    if (strncmp('charset=', $segment, 8) === 0) {
                        if ($charset !== '')
                        $charset = substr($segment, 8);
                        break;
                    }
                }
            }
        }
        if ($encoding !== null) {
            $content = $this->decodeOutputBuffer($content, $encoding);
        }
        if ($charset !== '') {
            $content = $this->convertOutputBufferCharset($content, $charset);
        }
        return $content;
    }

    private function decodeOutputBuffer($content, $encoding) {
        if ($encoding === 'gzip') {
            $result = file_get_contents(
                'compress.zlib://data:;base64,' . base64_encode($content)
            );
            if ($result !== false) {
                $content = $result;
            }
        } elseif ($encoding === 'deflate') {
            $result = gzinflate($content);
            if ($result !== false) {
                $content = $result;
            }
        }
        return $content;
    }

    private function convertOutputBufferCharset($content, $charset) {
        if ($charset !== 'utf-8') {
            $result = iconv($charset, 'utf-8', $content);
            if ($result !== false) {
                $content = $result;
            }
        }
        return $content;
    }

    protected function executeDebugger($headers, $outputBuffer) {
        $class = Config::getString(
            'Hyperframework.error_handler.debugger_class', ''
        );
        if ($class === '') {
            $class = 'Hyperframework\Web\Debugger';
        }
        $debugger = new $class;
        $debugger->execute(
            $this->getSource(), $headers, $outputBuffer
        );
    }

    protected function renderErrorView() {
        if ($this->isError() === true) {
            $type = 'error';
        } else {
            $type = 'exception';
        }
        $template = new ViewTemplate(
            ['source' => $this->getSource(), 'type' => $type]
        );
        $format = $this->getErrorViewFormat();
        $prefix = $template->getRootPath() . DIRECTORY_SEPARATOR
            . '_error' . DIRECTORY_SEPARATOR . 'show.';
        if ($format !== null && $format !== 'php') {
            if (file_exists($prefix . $format . '.php')) {
                $template->load('_error/show.' . $format . '.php');
                return;
            }
        }
        if (file_exists($prefix . 'php')) {
            $template->load('_error/show.php');
            return;
        }
        header('Content-Type: text/plain;charset=utf-8');
        if ($this->getSource() instanceof HttpException) {
            echo $this->getSource()->getCode();
        } else {
            echo '500 Internal Server Error';
        }
    }

    protected function getErrorViewFormat() {
        $pattern = '#\.([0-9a-zA-Z]+)$#';
        $requestPath = RequestPath::get();
        if (preg_match($pattern, $requestPath, $matches) === 1) {
            return $matches[1];
        }
    }

    protected function isDebuggerEnabled() {
        return $this->isDebuggerEnabled;
    }

    protected function getStartupOutputBufferLevel() {
        return $this->startupOutputBufferLevel;
    }
}
