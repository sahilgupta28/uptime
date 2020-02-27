<?php

namespace App\Components;

class UptimeChecker
{
    public function start($url)
    {
        $command_output = $this->executeCmd($url);
        return $this->hasSuccessStatus($command_output);
    }

    private function hasSuccessStatus($command_output)
    {
        foreach ($command_output as $output) {
            if (strpos($output, 'HTTP') !== false && strpos($output, '200')) {
                return true;
            }
        }
        return false;
    }

    private function executeCmd($url)
    {
        exec("curl -sSL -D - $url -o /dev/null", $output);
        return $output;
    }
}
