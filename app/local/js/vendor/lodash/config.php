<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/lodash.bundle.css',
	'js' => 'dist/lodash.bundle.js',
	'rel' => [
		'main.polyfill.core',
	],
	'skip_core' => true,
];