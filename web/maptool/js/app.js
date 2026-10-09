/* Eastern Caribbean Zone – VATSIM airspace overview
 * MapLibre GL 3D map. Data built by scripts/build-airspaces.mjs. */
'use strict';

const M_PER_FT = 0.3048;
const TYPE_COLORS = {
  UIR: '#4263eb', FIR: '#3b5bdb', TMA: '#2f9e44',
  APP: '#12b886', CTR: '#f76707', ATZ: '#e03131', OCEANIC: '#0c8599',
};
const TYPE_LABELS = {
  UIR: 'Upper airspace (UIR/UTA)', FIR: 'FIR (lower)', TMA: 'Terminal area (TMA)',
  APP: 'Approach sector', CTR: 'Control zone (CTR)', ATZ: 'Aerodrome zone (ATZ)',
  OCEANIC: 'Oceanic FIR (procedural)',
};
const ALL_TYPES = ['UIR', 'FIR', 'TMA', 'APP', 'CTR', 'ATZ', 'OCEANIC'];
// Extrusion sub-layers, drawn biggest→smallest. The large enclosing volumes are
// faint and drawn first; the small inner volumes are opaque-er and drawn last so
// they read through the larger volumes overlapping them (nested "cylinders").
const EXTRUSION_LAYERS = [
  { id: 'as-3d-fir', types: ['FIR', 'UIR', 'OCEANIC'], mul: 0.18 },
  { id: 'as-3d-tma', types: ['TMA', 'APP'], mul: 0.50 },
  { id: 'as-3d-ctr', types: ['CTR', 'ATZ'], mul: 1.00 },
];

// Initial view — fits the core islands (the oceanic FIR extends far east to ~37°W).
const ECZ_BOUNDS = [[-74.5, 8.0], [-56.0, 19.2]];

// ---- state ----------------------------------------------------------------
const state = {
  dim: '3d',
  basemap: 'dark',
  exag: 12,
  opacity: 0.50,
  groups: new Set(),      // active groups
  types: new Set(ALL_TYPES),
  isoPos: null,           // isolated position id or null
};

// ---- basemap style --------------------------------------------------------
const RASTER = {
  dark: { tiles: ['https://a.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png?key=cb1_4fo0_1_683873add83b41466006b60d', 'https://b.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png?key=cb1_4fo0_1_683873add83b41466006b60d', 'https://c.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png?key=cb1_4fo0_1_683873add83b41466006b60d'], attr: '© OpenStreetMap © CARTO' },
  light: { tiles: ['https://a.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png?key=cb1_4fo0_1_683873add83b41466006b60d', 'https://b.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png?key=cb1_4fo0_1_683873add83b41466006b60d', 'https://c.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png?key=cb1_4fo0_1_683873add83b41466006b60d'], attr: '© OpenStreetMap © CARTO' },
  satellite: { tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'], attr: 'Imagery © Esri, Maxar, Earthstar Geographics' },
};

function buildStyle() {
  const sources = {
    'terrain-dem': {
      type: 'raster-dem',
      tiles: ['https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{z}/{x}/{y}.png'],
      encoding: 'terrarium', tileSize: 256, maxzoom: 13,
    },
  };
  const layers = [{ id: 'bg', type: 'background', paint: { 'background-color': '#0a0e13' } }];
  for (const key of Object.keys(RASTER)) {
    sources['base-' + key] = { type: 'raster', tiles: RASTER[key].tiles, tileSize: 256, attribution: RASTER[key].attr };
    layers.push({
      id: 'base-' + key, type: 'raster', source: 'base-' + key,
      layout: { visibility: key === state.basemap ? 'visible' : 'none' },
      paint: { 'raster-opacity': key === 'satellite' ? 1 : 0.9 },
    });
  }
  return {
    version: 8,
    glyphs: 'https://demotiles.maplibre.org/font/{fontstack}/{range}.pbf',
    sources, layers,
  };
}

// ---- map init -------------------------------------------------------------
const map = new maplibregl.Map({
  container: 'map',
  style: buildStyle(),
  center: [-65.5, 13.6],
  zoom: 5.4,
  pitch: 48,
  bearing: -12,
  maxPitch: 85,
  attributionControl: { compact: true },
  preserveDrawingBuffer: true, // allow canvas export (screenshots)
});
map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'top-left');
map.addControl(new maplibregl.ScaleControl({ unit: 'nautical' }), 'bottom-right');
window.eczMap = map; // debug handle

let AIRSPACES = null;
let POSITIONS = null;

map.on('load', async () => {
  const [asp, apt, lbl, pos] = await Promise.all([
    fetch('data/airspaces.geojson').then((r) => r.json()),
    fetch('data/airports.geojson').then((r) => r.json()),
    fetch('data/airspace-labels.geojson').then((r) => r.json()),
    fetch('data/positions.json').then((r) => r.json()),
  ]);
  AIRSPACES = asp; POSITIONS = pos;

  // init active groups = all
  asp.features.forEach((f) => state.groups.add(f.properties.group));

  map.addSource('airspaces', { type: 'geojson', data: asp, promoteId: 'id' });
  map.addSource('airports', { type: 'geojson', data: apt });
  map.addSource('as-labels', { type: 'geojson', data: lbl });

  addAirspaceLayers();
  addAirportLayers();
  buildSidebar();
  applyFilter();
  applyDim();
  map.fitBounds(ECZ_BOUNDS, { padding: 40, pitch: 48, bearing: -12, duration: 0 });
});

// ---- layers ---------------------------------------------------------------
const colorExpr = () => ['match', ['get', 'type'], ...Object.entries(TYPE_COLORS).flat(), '#868e96'];

const baseExpr = () => ['*', ['get', 'floor_ft'], M_PER_FT * state.exag];
const heightExpr = () => ['*', ['get', 'ceil_ft'], M_PER_FT * state.exag];
const layerOpacity = (L) => +(state.opacity * L.mul).toFixed(3);

function addAirspaceLayers() {
  // flat fill (2D)
  map.addLayer({
    id: 'as-fill', type: 'fill', source: 'airspaces',
    layout: { visibility: 'none' },
    paint: {
      'fill-color': colorExpr(),
      'fill-opacity': ['case', ['boolean', ['feature-state', 'hover'], false], Math.min(state.opacity + 0.25, 0.95), state.opacity],
    },
  });
  // 3D extrusions, biggest first so the small inner volumes draw last / on top
  for (const L of EXTRUSION_LAYERS) {
    map.addLayer({
      id: L.id, type: 'fill-extrusion', source: 'airspaces',
      paint: {
        'fill-extrusion-color': colorExpr(),
        'fill-extrusion-base': baseExpr(),
        'fill-extrusion-height': heightExpr(),
        'fill-extrusion-opacity': layerOpacity(L),
        'fill-extrusion-vertical-gradient': false,
      },
    });
  }
  // outline (all volumes)
  map.addLayer({
    id: 'as-line', type: 'line', source: 'airspaces',
    paint: {
      'line-color': colorExpr(),
      'line-width': ['case', ['boolean', ['feature-state', 'hover'], false], 2.6, 1.2],
      'line-opacity': 0.9,
    },
  });
  // labels — dedicated point source, one per airspace, collision-managed by rank
  map.addLayer({
    id: 'as-label', type: 'symbol', source: 'as-labels',
    layout: {
      'text-field': ['get', 'name'],
      'text-size': ['interpolate', ['linear'], ['zoom'], 4, 10, 8, 13.5],
      'text-font': ['Open Sans Semibold'],
      'text-anchor': 'center',
      'text-allow-overlap': false,
      'text-ignore-placement': false,
      'symbol-sort-key': ['get', 'rank'],
    },
    paint: { 'text-color': '#e6edf3', 'text-halo-color': '#0a0e13', 'text-halo-width': 1.6 },
  });

  EXTRUSION_LAYERS.forEach((L) => wireInteraction(L.id));
  wireInteraction('as-fill');
}

function addAirportLayers() {
  // controlled fields = amber; uncontrolled = hollow grey
  map.addLayer({
    id: 'apt-dot', type: 'circle', source: 'airports',
    paint: {
      'circle-radius': ['interpolate', ['linear'], ['zoom'], 4, 3, 9, 6],
      'circle-color': ['case', ['get', 'controlled'], '#ffd43b', '#4a5763'],
      'circle-stroke-color': ['case', ['get', 'controlled'], '#0a0e13', '#8b98a5'],
      'circle-stroke-width': 1.5,
    },
  });
  map.addLayer({
    id: 'apt-label', type: 'symbol', source: 'airports',
    minzoom: 6,
    layout: {
      'text-field': ['get', 'icao'], 'text-size': 11, 'text-font': ['Open Sans Semibold'],
      'text-offset': [0, 1.1], 'text-anchor': 'top',
    },
    paint: {
      'text-color': ['case', ['get', 'controlled'], '#ffd43b', '#8b98a5'],
      'text-halo-color': '#0a0e13', 'text-halo-width': 1.4,
    },
  });

  map.on('mouseenter', 'apt-dot', () => (map.getCanvas().style.cursor = 'pointer'));
  map.on('mouseleave', 'apt-dot', () => (map.getCanvas().style.cursor = ''));
  map.on('click', 'apt-dot', (e) => {
    const p = e.features[0].properties;
    const controlled = p.controlled === true || p.controlled === 'true';
    let line;
    if (controlled) {
      const freq = p.twr_freq ? ` · ${p.twr_freq}` : '';
      line = `<br><span style="color:#8b98a5">Tower:</span> ${p.twr_name}${freq}`;
    } else {
      line = `<br><span style="color:#8b98a5">Uncontrolled field</span>`;
    }
    new maplibregl.Popup({ closeButton: true })
      .setLngLat(e.lngLat)
      .setHTML(`<b>${p.icao}</b> — ${p.name}${line}`)
      .addTo(map);
  });
}

// ---- interaction ----------------------------------------------------------
let hovered = null;
function wireInteraction(layer) {
  map.on('mousemove', layer, (e) => {
    map.getCanvas().style.cursor = 'pointer';
    const id = e.features[0].id;
    if (hovered !== id) {
      if (hovered != null) map.setFeatureState({ source: 'airspaces', id: hovered }, { hover: false });
      hovered = id;
      map.setFeatureState({ source: 'airspaces', id: hovered }, { hover: true });
    }
  });
  map.on('mouseleave', layer, () => {
    map.getCanvas().style.cursor = '';
    if (hovered != null) map.setFeatureState({ source: 'airspaces', id: hovered }, { hover: false });
    hovered = null;
  });
  map.on('click', layer, (e) => showInfo(e.features[0].properties));
}

// ---- info panel -----------------------------------------------------------
function showInfo(p) {
  const color = TYPE_COLORS[p.type] || '#868e96';
  const el = document.getElementById('info-body');
  const note = p.note ? `<div class="info-note">${p.note}</div>` : '';
  let bands = [];
  try { bands = JSON.parse(p.bands || '[]'); } catch { /* noop */ }
  // altitude-banded classes, drawn high → low
  const bandRows = bands.map(([c, floor, ceil]) => `
    <div class="band">
      <span class="band-cls" style="background:${color}22;color:${color};border:1px solid ${color}55">Class ${c}</span>
      <span class="band-alt">${floor}<span class="band-sep"> – </span>${ceil}</span>
    </div>`).join('');

  el.innerHTML = `
    <div class="info-head">
      <div class="info-name">${p.name}</div>
      <span class="info-type" style="background:${color}22;color:${color};border:1px solid ${color}55">${TYPE_LABELS[p.type] || p.type}</span>
    </div>
    <div class="info-cols">
      <div class="info-meta">
        <div class="info-k">Zone</div><div class="info-v">${p.group}</div>
        <div class="info-k">Controlled by</div><div class="info-v">${p.station_name}${p.station ? ` · ${p.station}` : ''}</div>
        <div class="info-k">Frequency</div><div class="info-v">${p.frequency || '—'}</div>
      </div>
      <div class="info-bands">
        <div class="info-k">Airspace classes</div>
        ${bandRows}
      </div>
    </div>${note}`;
  document.getElementById('info').classList.remove('hidden');
}
document.getElementById('info-close').onclick = () => document.getElementById('info').classList.add('hidden');

// ---- filtering ------------------------------------------------------------
function applyFilter() {
  const conds = [
    ['in', ['get', 'group'], ['literal', [...state.groups]]],
    ['in', ['get', 'type'], ['literal', [...state.types]]],
  ];
  if (state.isoPos) conds.push(['==', ['get', 'station'], state.isoPos]);
  const base = ['all', ...conds];
  ['as-fill', 'as-line', 'as-label'].forEach((l) => map.setFilter(l, base));
  EXTRUSION_LAYERS.forEach((L) => map.setFilter(L.id, ['all', ...conds, ['in', ['get', 'type'], ['literal', L.types]]]));
  refreshPositionList();
}

// positions list mirrors the active Zone + Airspace-type filters
const posRegistry = [];  // { el, group, types[] }
const posHeaders = {};   // group -> header element
function refreshPositionList() {
  if (!posRegistry.length) return;
  const groupVisible = {};
  posRegistry.forEach((r) => {
    const vis = state.groups.has(r.group) && r.types.some((t) => state.types.has(t));
    r.el.style.display = vis ? '' : 'none';
    if (vis) groupVisible[r.group] = true;
  });
  Object.entries(posHeaders).forEach(([g, el]) => { el.style.display = groupVisible[g] ? '' : 'none'; });
}

// ---- view controls --------------------------------------------------------
function applyDim() {
  const is3d = state.dim === '3d';
  EXTRUSION_LAYERS.forEach((L) => map.setLayoutProperty(L.id, 'visibility', is3d ? 'visible' : 'none'));
  map.setLayoutProperty('as-fill', 'visibility', is3d ? 'none' : 'visible');
  map.easeTo({ pitch: is3d ? 48 : 0, bearing: is3d ? map.getBearing() : 0, duration: 500 });
}

function refreshColors() {
  EXTRUSION_LAYERS.forEach((L) => map.setPaintProperty(L.id, 'fill-extrusion-color', colorExpr()));
  map.setPaintProperty('as-fill', 'fill-color', colorExpr());
  map.setPaintProperty('as-line', 'line-color', colorExpr());
}
function refreshOpacity() {
  EXTRUSION_LAYERS.forEach((L) => map.setPaintProperty(L.id, 'fill-extrusion-opacity', layerOpacity(L)));
  map.setPaintProperty('as-fill', 'fill-opacity',
    ['case', ['boolean', ['feature-state', 'hover'], false], Math.min(state.opacity + 0.25, 0.95), state.opacity]);
}
function refreshExag() {
  EXTRUSION_LAYERS.forEach((L) => {
    map.setPaintProperty(L.id, 'fill-extrusion-base', baseExpr());
    map.setPaintProperty(L.id, 'fill-extrusion-height', heightExpr());
  });
}
function setBasemap(key) {
  state.basemap = key;
  Object.keys(RASTER).forEach((k) => map.setLayoutProperty('base-' + k, 'visibility', k === key ? 'visible' : 'none'));
}

// ---- sidebar --------------------------------------------------------------
function buildSidebar() {
  // groups
  const groupColors = { 'Curaçao FIR (TNCF)': '#4dabf7', 'Juliana TMA (TNCM)': '#38d9a9', 'Piarco FIR (TTZP)': '#ff922b' };
  const gWrap = document.getElementById('groups');
  [...state.groups].forEach((g) => {
    const c = document.createElement('div');
    c.className = 'chip active';
    c.style.background = groupColors[g] + '22';
    c.style.borderColor = groupColors[g];
    c.innerHTML = `<span class="dot" style="background:${groupColors[g]}"></span>${g}`;
    c.onclick = () => {
      if (state.groups.has(g)) { state.groups.delete(g); c.classList.remove('active'); c.style.background = ''; }
      else { state.groups.add(g); c.classList.add('active'); c.style.background = groupColors[g] + '22'; }
      applyFilter();
    };
    gWrap.appendChild(c);
  });

  // types
  const tWrap = document.getElementById('types');
  ALL_TYPES.forEach((t) => {
    const c = document.createElement('div');
    c.className = 'chip active';
    c.style.background = TYPE_COLORS[t] + '22';
    c.innerHTML = `<span class="dot" style="background:${TYPE_COLORS[t]}"></span>${t}`;
    c.onclick = () => {
      if (state.types.has(t)) { state.types.delete(t); c.classList.remove('active'); c.style.background = ''; }
      else { state.types.add(t); c.classList.add('active'); c.style.background = TYPE_COLORS[t] + '22'; }
      applyFilter();
    };
    tWrap.appendChild(c);
  });

  // positions grouped — each row tagged with the airspace types it owns (or a
  // role-based fallback) so the list can mirror the active zone/type filters.
  const pWrap = document.getElementById('positions');
  const stationTypes = {};
  AIRSPACES.features.forEach((f) => {
    const s = f.properties.station; if (!s) return;
    (stationTypes[s] = stationTypes[s] || new Set()).add(f.properties.type);
  });
  const roleFallback = (id) => ({
    CTR: ['FIR', 'UIR'], APP: ['TMA', 'APP'], TWR: ['CTR', 'ATZ'], GND: ['CTR', 'ATZ'], FSS: ['OCEANIC'],
  }[id.split('_').pop()] || ALL_TYPES);
  const groups = {};
  POSITIONS.forEach((p) => { (groups[p.group] = groups[p.group] || []).push(p); });
  Object.entries(groups).forEach(([g, list]) => {
    const h = document.createElement('div'); h.className = 'pos-group'; h.textContent = g; pWrap.appendChild(h);
    posHeaders[g] = h;
    list.forEach((p) => {
      const row = document.createElement('div');
      row.className = 'pos';
      row.innerHTML = `<span class="swatch" style="background:${p.color}"></span>
        <span class="pmeta"><span class="pname">${p.name}</span><span class="pcs">${p.id} · ${p.role}</span></span>
        <span class="pfreq">${p.freq || ''}</span>`;
      row.onclick = () => {
        if (state.isoPos === p.id) { state.isoPos = null; row.classList.remove('iso'); }
        else {
          document.querySelectorAll('.pos.iso').forEach((e) => e.classList.remove('iso'));
          state.isoPos = p.id; row.classList.add('iso');
        }
        applyFilter();
      };
      pWrap.appendChild(row);
      const owned = stationTypes[p.id];
      posRegistry.push({ el: row, group: g, types: owned && owned.size ? [...owned] : roleFallback(p.id) });
    });
  });
  refreshPositionList();

  // legend
  const lWrap = document.getElementById('legend');
  ALL_TYPES.forEach((t) => {
    const li = document.createElement('div'); li.className = 'li';
    li.innerHTML = `<span class="box" style="background:${TYPE_COLORS[t]}"></span>${t}`;
    lWrap.appendChild(li);
  });
}

// ---- control wiring -------------------------------------------------------
document.querySelectorAll('#dim-toggle button').forEach((b) => {
  b.onclick = () => {
    document.querySelectorAll('#dim-toggle button').forEach((x) => x.classList.remove('active'));
    b.classList.add('active');
    state.dim = b.dataset.dim;
    applyDim();
  };
});
document.getElementById('basemap').onchange = (e) => setBasemap(e.target.value);
document.getElementById('exag').oninput = (e) => {
  state.exag = +e.target.value;
  document.getElementById('exag-val').textContent = state.exag + '×';
  refreshExag();
};
document.getElementById('opacity').oninput = (e) => {
  state.opacity = +e.target.value / 100;
  document.getElementById('op-val').textContent = e.target.value + '%';
  refreshOpacity();
};
// ---- return button from profile --------------------------------------------------------
const params = new URLSearchParams(window.location.search);
const returnUrl = params.get('return');

if (returnUrl) {
  const btn = document.createElement('button');

  btn.className = 'chip';
  btn.textContent = '← Back';

  btn.onclick = () => {
    window.location.href = returnUrl;
  };

  document.querySelector('.side-head').appendChild(btn);
}