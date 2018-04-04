<?php

namespace AT\CodeQualityTool\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    public static function updateHooks(Event $event)
    {
        $hookPath = __DIR__ . '/../../../../../.git/hooks/pre-commit';
        $newHookPath = __DIR__ . '/../../pre-commit';
        
        file_put_contents($hookPath, file_get_contents($newHookPath));
        
        // Make the hook executable
        @chmod($hookPath, 0744);
    }
}
