<?php
namespace Assets;

final class Shell
{
    public static function run($command)
    {
        $pipes = array();
        $descriptorspec = array(
            array('pipe', 'r'),
            array('pipe', 'w'),
            array('pipe', 'w')
        );

        $proc = proc_open($command, $descriptorspec, $pipes);
        $input = null;
        fwrite($pipes[0], $input);
        fclose($pipes[0]);
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        if (!empty($error)) {
            throw new ShellException($error);
        }

        return (proc_close($proc) === 0);
    }
}