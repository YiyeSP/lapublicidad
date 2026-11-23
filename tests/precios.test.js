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

// Tabla de precios por defecto (misma que lp_default_precios en inc/quote.php).
const PRECIOS = {
  pendon: {
    nombre: 'Pendón', tipo: 'm2', minimo: 35000, limites: [30, 300],
    medidas: [[80, 200], [100, 200], [120, 240]],
    materiales: [{ id: 'frontlit13', nombre: 'Lona frontlit 13 oz', tarifa: 35000, tarifa_diseno: 50000 }],
    diseno: true,
    acabados: [
      { id: 'ojales', nombre: 'Ojales', valor: 5000, tipo: 'unidad' },
      { id: 'bolsillo', nombre: 'Bolsillos para tubo', valor: 8000, tipo: 'unidad' },
    ],
  },
  vinilo: {
    nombre: 'Vinilo impreso', tipo: 'm2', minimo: 30000, limites: [30, 300],
    medidas: [[50, 50], [100, 100], [100, 150]],
    materiales: [
      { id: 'mate', nombre: 'Vinilo mate', tarifa: 45000, tarifa_diseno: 90000 },
      { id: 'brillante', nombre: 'Vinilo brillante', tarifa: 45000, tarifa_diseno: 90000 },
      { id: 'transparente', nombre: 'Vinilo transparente (sin tinta blanca)', tarifa: 45000, tarifa_diseno: 90000 },
    ],
    diseno: true,
    acabados: [
      { id: 'laminado', nombre: 'Laminado', valor: 25000, tipo: 'm2' },
      { id: 'contorno', nombre: 'Corte de contorno', valor: 20000, tipo: 'm2' },
      { id: 'instalacion', nombre: 'Instalación', valor: 16000, tipo: 'm2' },
    ],
    descuentos: [[1, 0], [2, 5], [3, 12.5], [4, 20], [5, 25], [6, 30], [7, 32.5], [8, 35], [10, 40]],
  },
  stickers: {
    nombre: 'Stickers troquelados (para emprendedores)', tipo: 'paquete',
    precio: 45000, descripcion: 'Paquete de 500 unidades de 3×3 cm con corte de contorno',
  },
  pasacalle: {
    nombre: 'Pasacalle', tipo: 'm2', minimo: 90000, limites: [100, 600],
    medidas: [[300, 100], [400, 100], [500, 100]],
    materiales: [{ id: 'frontlit13', nombre: 'Lona frontlit 13 oz', tarifa: 35000 }],
    diseno: false, diseno_incluido: true,
    acabados: [{ id: 'refuerzo', nombre: 'Ojales y refuerzo perimetral', valor: 12000, tipo: 'fijo' }],
  },
};

const CASOS = [
  {
    nombre: 'Pendón 80×200 cm = 56000',
    producto: 'pendon',
    entrada: { ancho: 80, alto: 200, cantidad: 1, material: 'frontlit13', diseno: false, acabados: [] },
    total: 56000,
  },
  {
    nombre: 'Pendón 30×30 = 35000 (mínimo)',
    producto: 'pendon',
    entrada: { ancho: 30, alto: 30, cantidad: 1, material: 'frontlit13', diseno: false, acabados: [] },
    total: 35000,
  },
  {
    nombre: 'Vinilo 100×100 ×4 con laminado = 244000',
    producto: 'vinilo',
    entrada: { ancho: 100, alto: 100, cantidad: 4, material: 'mate', diseno: false, acabados: ['laminado'] },
    total: 244000,
  },
  {
    nombre: 'Pasacalle 500×100 con refuerzo = 187000',
    producto: 'pasacalle',
    entrada: { ancho: 500, alto: 100, cantidad: 1, material: 'frontlit13', diseno: false, acabados: ['refuerzo'] },
    total: 187000,
  },
  {
    nombre: 'Stickers ×2 = 90000',
    producto: 'stickers',
    entrada: { cantidad: 2 },
    total: 90000,
  },
];

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
