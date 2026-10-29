<?php

// site/plugins/svg-helper/index.php

Kirby::plugin('your-name/svg-helper', [
    'components' => [
        'svg' => function (Kirby $kirby, string $file, array $attr = []) {
            // Get the root SVG directory
            $root = $kirby->root('assets') . '/svg';
            
            // Construct the full file path
            $file = $root . '/' . $file . '.svg';
            
            if (!file_exists($file)) {
                return '<!-- SVG file not found: ' . $file . ' -->';
            }
            
            // Read the SVG content
            $content = file_get_contents($file);
            
            // Create a new DOMDocument
            $doc = new DOMDocument();
            $doc->loadXML($content);
            
            // Get the SVG element
            $svg = $doc->getElementsByTagName('svg')->item(0);
            
            // Apply attributes
            foreach ($attr as $key => $value) {
                if ($key === 'class') {
                    // Append classes to existing ones
                    $currentClass = $svg->getAttribute('class');
                    $svg->setAttribute($key, trim($currentClass . ' ' . $value));
                } else {
                    $svg->setAttribute($key, $value);
                }
            }
            
            return $doc->saveXML($svg);
        }
    ]
]);
