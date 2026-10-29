<?php

function svg(string $name, $attr = []): string {
    if (is_string($attr)) {
        $attr = ['class' => $attr];
    }
    return kirby()->component('svg')($kirby, $name, $attr);
}