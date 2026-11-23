/**
 * Pruebas mínimas de precios para el tema lapublicidad.
 * Verifica los casos de control contra la función calc() real de assets/js/main.js.
 *
 * Ejecutar: node tests/precios.test.js
 */
'use strict';

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const mainJs = fs.readFileSync(path.join(root, 'assets', 'js', 'main.js'), 'utf8');

// Extrae la función calc(p, s) del IIFE de main.js para probar la lógica real.
function extraerCalc(source) {
  const inicio = source.indexOf('function calc(p, s) {');
  if (inicio === -1) throw new Error('No se encontró function calc(p, s) en main.js');
  let i = inicio;
  let llaves = 0;
  let abierto = false;
  for (; i < source.length; i++) {
    const ch = source[i];
    if (ch === '{') { llaves++; abierto = true; }
    else if (ch === '}') {
      llaves--;
      if (abierto && llaves === 0) { i++; break; }
    }
  }
  return source.slice(inicio, i);
}

const calcSrc = extraerCalc(mainJs);
// eslint-disable-next-line no-new-func
const calc = new Function('p', 's', calcSrc + '\nreturn calc(p, s);');

// Datos editables: cuando cambien los precios o casos de control, edita tests/datos-precios.json
const datos = JSON.parse(fs.readFileSync(path.join(__dirname, 'datos-precios.json'), 'utf8'));
const PRECIOS = datos.precios;
const CASOS = datos.casos;

let ok = 0;
let fail = 0;

for (const caso of CASOS) {
  const p = PRECIOS[caso.producto];
  const r = calc(p, caso.entrada);
  const total = r && r.total;
  const paso = r && r.ok && total === caso.total;
  if (paso) {
    ok++;
    console.log(`OK   ${caso.nombre}`);
  } else {
    fail++;
    console.log(`FAIL ${caso.nombre}`);
    console.log(`     esperado: ${caso.total}`);
    console.log(`     obtenido: ${total}`);
    if (r && r.error) console.log(`     error: ${r.error}`);
  }
}

console.log(`\nResultado: ${ok} OK, ${fail} FAIL`);
process.exit(fail ? 1 : 0);
