<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MinifyAssets extends Command
{
    protected $signature   = 'assets:minify {--css-only} {--js-only}';
    protected $description = 'Minify CSS and JS assets into .min versions';

    private array $cssFiles = [
        'frontend/css/style.css',
        'frontend/css/plugin.css',
        'frontend/css/global-layout.css',
        'frontend/css/homepage.css',
        'frontend/css/mobile-app.css',
        'frontend/css/boat-detail.css',
        'frontend/css/hotel-detail.css',
        'frontend/css/package-detail.css',
        'frontend/css/boat-listing.css',
        'frontend/css/cab-detail.css',
    ];

    private array $jsFiles = [
        'frontend/js/main.js',
    ];

    public function handle(): int
    {
        $cssOnly = $this->option('css-only');
        $jsOnly  = $this->option('js-only');

        if (!$jsOnly) {
            $this->info('Minifying CSS files...');
            foreach ($this->cssFiles as $file) {
                $this->minifyFile(public_path($file), 'css');
            }
        }

        if (!$cssOnly) {
            $this->info('Minifying JS files...');
            foreach ($this->jsFiles as $file) {
                $this->minifyFile(public_path($file), 'js');
            }
        }

        $this->info('✓ Assets minified successfully.');
        return 0;
    }

    private function minifyFile(string $path, string $type): void
    {
        if (!file_exists($path)) {
            $this->warn("Skipping (not found): {$path}");
            return;
        }

        $content  = file_get_contents($path);
        $minified = $type === 'css' ? $this->minifyCSS($content) : $this->minifyJS($content);
        $minPath  = preg_replace('/\.(css|js)$/', '.min.$1', $path);

        file_put_contents($minPath, $minified);

        $before = strlen($content);
        $after  = strlen($minified);
        $saved  = round(($before - $after) / $before * 100);

        $this->line("  ✓ " . basename($path) . " → " . basename($minPath) . " ({$saved}% smaller)");
    }

    private function minifyCSS(string $css): string
    {
        // Remove block comments, preserving /*! license comments
        $css = preg_replace('#/\*(?!!)(.*?)\*/#s', '', $css);

        // Remove line-by-line whitespace
        $lines = array_map('trim', explode("\n", $css));
        $css   = implode(' ', $lines);

        // Collapse all whitespace runs to a single space
        $css = preg_replace('/\s+/', ' ', $css);

        // Remove spaces around structural characters
        $css = preg_replace('/\s*([\{\};:,>~+])\s*/', '$1', $css);

        // Remove trailing semicolons before closing brace
        $css = str_replace(';}', '}', $css);

        return trim($css);
    }

    private function minifyJS(string $js): string
    {
        // Remove single-line comments (skip URLs like https://)
        $js = preg_replace('~(?<!:)//(?!/).*$~m', '', $js);

        // Remove block comments
        $js = preg_replace('#/\*.*?\*/#s', '', $js);

        // Collapse whitespace
        $js = preg_replace('/\s+/', ' ', $js);

        return trim($js);
    }
}
