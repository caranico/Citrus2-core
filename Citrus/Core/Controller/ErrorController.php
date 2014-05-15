<?php
namespace Citrus\Core\Controller;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    /**
     * @todo externalize content template
     */
    public function doException($exception)
    {
        $content = '<pre class="message">' . get_class($exception) . ': ' . $exception->getMessage() . '</pre>'
                   . '<p>'
                   . '<code>' . $exception->getFile() . '</code>, line ' . $exception->getLine() . '.'
                   . '</p>'
                   . '<p>Trace :</p>'
                   . '<ol>';
        foreach ($exception->getTrace() as $tr) {
            $content .= '<li><code>';
            if (isset($tr['class'])) $content .= $tr['class'] . '::';

            $content .= $tr['function'] . '</code> ';

            if (isset($tr['file'])) $content .= '<i>' . $tr['file'] . '</i> ';
            if (isset($tr['line'])) $content .= 'line ' . $tr['line'];

            $content .= '</li>';
        }
        $content .= '</ol>';

        return new Response($content, 500);
    }
}
