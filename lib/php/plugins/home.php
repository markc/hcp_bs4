<?php declare(strict_types=1);
// lib/php/plugins/home.php 20150101 - 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Home extends Plugin
{
    /**
     * Override the list method from parent Plugin class
     * @throws RuntimeException If output buffering fails
     */
    public function list() : string
    {
        elog(__METHOD__);

        if (file_exists(INC . 'home.tpl')) {
            ob_start();
            include INC . 'home.tpl';
            $content = ob_get_clean();
            
            if ($content === false) {
                throw new RuntimeException('Failed to get output buffer content');
            }
            
            return $content;
        }
        
        return $this->t->list([]);
    }
}

?>
