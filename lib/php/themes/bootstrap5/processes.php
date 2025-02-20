<?php declare(strict_types=1);
// lib/php/themes/bootstrap5/processes.php 20170225 - 20250119
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Themes_Bootstrap5_Processes extends Themes_Bootstrap5_Theme
{
    public function list(array $in): string
    {
        elog(__METHOD__);

        $csrfToken = $_SESSION['c'] ?? '';
        $procs = htmlspecialchars($in['procs'] ?? '');
        $processCount = count(explode("\n", $in['procs'] ?? '')) - 1;

        return <<<HTML
        <div class="processes-container w-100">
            <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                <h1 class="mb-0 me-auto">
                    <i class="bi bi-cpu"></i> Processes <small>({$processCount})</small>
                </h1>
                <form method="post" class="form-inline">
                    <input type="hidden" name="c" value="{$csrfToken}">
                    <input type="hidden" name="m" value="list">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> Refresh
                        </button>
                    </div>
                </form>
            </div>
            <pre class="overflow-auto">{$procs}</pre>
        </div>
        HTML;
    }
}
