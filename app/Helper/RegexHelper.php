<?php
function getPattern(): array
{
    $pattern = [];
    $pattern[0] = '/#n/';
    $pattern[1] = '/#b/';
    $pattern[2] = '/#eb/';
    $pattern[3] = '/#i/';
    $pattern[4] = '/#ei}/';
    $pattern[5] = '/#2b/';
    $pattern[6] = '/#hr/';
    $pattern[7] = '/#p/';
    $pattern[8] = '/#ep/';
    $pattern[9] = '/#2n/';

    return $pattern;
}

function getReplacement(): array
{

    $replace = [];
    $replace[9] = '<br>';
    $replace[8] = '<span class="font-semibold">';
    $replace[7] = '</span>';
    $replace[6] = '<em>';
    $replace[5] = '</em>';
    $replace[4] = '<span class="text-2xl font-semibold">';
    $replace[3] = '<hr class="text-bold">';
    $replace[2] = '<p class="mb-2">';
    $replace[1] = '</p>';
    $replace[0] = '<br><br>';

    return $replace;
}
