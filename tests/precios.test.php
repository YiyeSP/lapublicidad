<?php
/**
 * Pruebas mínimas de precios para el tema lapublicidad.
 * Verifica los casos de control contra lp_calcular() real de inc/quote.php.
 *
 * Ejecutar: php tests/precios.test.php
 */

// Stubs mínimos de WordPress para poder cargar quote.php en CLI.
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}
if (!function_exists('add_action')) { function add_action($hook, $cb, $prio = 10, $args = 1) {} }
if (!function_exists('add_filter')) { function add_filter($hook, $cb, $prio = 10, $args = 1) {} }
if (!function_exists('register_activation_hook')) { function register_activation_hook($file, $cb) {} }
if (!function_exists('get_option')) { function get_option($name, $default = false) { return $default; } }
if (!function_exists('sanitize_key')) { function sanitize_key($key) { return preg_replace('/[^a-z0-9_\-]/', '', strtolower((string) $key)); } }
if (!function_exists('sanitize_text_field')) { function sanitize_text_field($str) { return trim(strip_tags((string) $str)); } }
if (!function_exists('wp_unslash')) { function wp_unslash($value) { return $value; } }
if (!function_exists('wp_send_json_error')) { function wp_send_json_error($data = null) { echo json_encode(['success' => false, 'data' => $data]); exit; } }
if (!function_exists('wp_send_json_success')) { function wp_send_json_success($data = null) { echo json_encode(['success' => true, 'data' => $data]); exit; } }

require_once __DIR__ . '/../inc/quote.php';

// Datos editables: cuando cambien los precios o casos de control, edita tests/datos-precios.json
$datos = json_decode(file_get_contents(__DIR__ . '/datos-precios.json'), true);
$casos = $datos['casos'];

$ok = 0;
$fail = 0;
foreach ($casos as $caso) {
    $entrada = $caso['entrada'];
    $entrada['producto'] = $caso['producto'];
    $r = lp_calcular($entrada);
    $total = isset($r['total']) ? $r['total'] : null;
    $paso = !empty($r['ok']) && $total === $caso['total'];
    if ($paso) {
        $ok++;
        echo "OK   {$caso['nombre']}\n";
    } else {
        $fail++;
        echo "FAIL {$caso['nombre']}\n";
        echo "     esperado: {$caso['total']}\n";
        echo "     obtenido: {$total}\n";
        if (!empty($r['error'])) echo "     error: {$r['error']}\n";
    }
}

echo "\nResultado: {$ok} OK, {$fail} FAIL\n";
exit($fail ? 1 : 0);
