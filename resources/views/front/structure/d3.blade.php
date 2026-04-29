@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
@endphp

@section('title', $t('ໂຄງສ້າງ (D3.js)','Structure (D3.js)','組織架構 D3').' - '.($settings->site_name_lo ?: 'BFOL'))

@push('styles')
<style>
  body { overflow: hidden; }   /* prevent scroll behind canvas */

  /* ─── Chart container ─── */
  #org-canvas {
    width:  100%;
    height: calc(100dvh - var(--topbar-h, 120px));
    background: #f8f6f0;
    cursor: grab;
    position: relative;
    overflow: hidden;
  }
  #org-canvas.panning { cursor: grabbing; }

  /* ─── Links ─── */
  .link {
    fill: none;
    stroke: #c5a021;
    stroke-opacity: .45;
    stroke-width: 1.8;
  }
  .link.dept-0 { stroke: #7c5a12; }
  .link.dept-1 { stroke: #1a5fa8; }
  .link.dept-2 { stroke: #1a7a4a; }
  .link.dept-3 { stroke: #7c3a9f; }

  /* ─── Node groups ─── */
  .node { cursor: pointer; }
  .node-ring {
    fill: white;
    stroke-width: 3;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,.15));
    transition: r .2s;
  }
  .node-ring-l1 { stroke: #c5a021; stroke-width: 4; }
  .node-ring-l2 { stroke-width: 3; }
  .node-ring-l3 { stroke: #94a3b8; stroke-width: 2; }

  /* dept ring colours */
  .dept-0 .node-ring-l2 { stroke: #c5a021; }
  .dept-1 .node-ring-l2 { stroke: #1a5fa8; }
  .dept-2 .node-ring-l2 { stroke: #1a7a4a; }
  .dept-3 .node-ring-l2 { stroke: #7c3a9f; }

  /* ─── Tooltip ─── */
  #tip {
    position: fixed;
    z-index: 200;
    pointer-events: none;
    background: rgba(15,15,15,.92);
    backdrop-filter: blur(8px);
    color: #fff;
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 12px;
    max-width: 240px;
    box-shadow: 0 8px 24px rgba(0,0,0,.35);
    border: 1px solid rgba(255,255,255,.08);
    display: none;
    line-height: 1.5;
  }
  #tip strong { color: #f0c040; font-size: 13px; }
  #tip .tip-pos { color: #9dc8f0; font-size: 11px; }
  #tip .tip-dept { background: rgba(255,255,255,.1); border-radius: 99px;
                   padding: 1px 8px; font-size: 10px; display:inline-block; margin-top:4px; }

  /* ─── Controls ─── */
  #controls {
    position: absolute;
    bottom: 24px; right: 24px;
    display: flex; flex-direction: column; gap: 6px;
    z-index: 50;
  }
  .ctrl-btn {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: white;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 14px; color: #475569;
    transition: all .15s;
  }
  .ctrl-btn:hover { background: #f1f5f9; border-color: #c5a021; color: #7c5a12; transform: scale(1.05); }

  /* ─── Compare bar ─── */
  #compare-bar {
    position: absolute; top: 0; left: 0; right: 0; z-index: 50;
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid #e2e8f0;
    padding: 8px 20px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
  }

  /* ─── Legend ─── */
  #legend {
    position: absolute; bottom: 24px; left: 24px; z-index: 50;
    background: rgba(255,255,255,.94);
    backdrop-filter: blur(6px);
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 10px 14px;
    font-size: 11px;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
  }
  #legend h4 { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;
               letter-spacing: .08em; margin-bottom: 6px; }
  .leg-item { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
  .leg-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

  /* ─── Spinner ─── */
  #d3-spinner {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 12px; z-index: 10; background: #f8f6f0;
  }
  .spin-ring {
    width: 48px; height: 48px; border-radius: 50%;
    border: 4px solid #e2d6b0;
    border-top-color: #c5a021;
    animation: spin .8s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')

<div id="org-canvas">

  {{-- Loading spinner --}}
  <div id="d3-spinner">
    <div class="spin-ring"></div>
    <p style="font-size:13px;color:#94a3b8;">{{ $t('ກຳລັງໂຫຼດ...','Loading chart...','載入中...') }}</p>
  </div>

  {{-- Compare bar --}}
  <div id="compare-bar">
    <div class="flex items-center gap-2">
      <span style="background:#c5a021;color:white;font-size:10px;font-weight:800;padding:2px 8px;border-radius:99px;">
        D3.js
      </span>
      <span style="font-size:12px;font-weight:700;color:#1e293b;">
        {{ $t('ໂຄງສ້າງອົງການ','Organizational Structure','組織架構') }}
      </span>
    </div>
    <div style="flex:1"></div>
    <div style="display:flex;gap:8px;align-items:center;">
      <span style="font-size:11px;color:#94a3b8;">{{ $t('ທ່ຽບ:','Compare:','對比:') }}</span>
      <a href="{{ route('front.structure') }}"
         style="font-size:11px;font-weight:600;color:#1a5fa8;background:#eff6ff;border:1px solid #bfdbfe;
                border-radius:8px;padding:4px 12px;text-decoration:none;transition:all .15s;"
         onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
        <i class="fas fa-sitemap" style="font-size:9px;margin-right:4px;"></i>CSS Tree
      </a>
      <a href="{{ route('front.committee') }}"
         style="font-size:11px;font-weight:600;color:#475569;background:#f1f5f9;border:1px solid #e2e8f0;
                border-radius:8px;padding:4px 12px;text-decoration:none;transition:all .15s;"
         onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
        <i class="fas fa-id-card" style="font-size:9px;margin-right:4px;"></i>{{ $t('ຄະນະກຳມະການ','Committee','委員會') }}
      </a>
    </div>
    <div style="display:flex;gap:6px;align-items:center;font-size:11px;color:#94a3b8;border-left:1px solid #e2e8f0;padding-left:10px;">
      <i class="fas fa-hand-pointer" style="font-size:10px;"></i>
      {{ $t('ລາກ & ລ໌ = ຊູມ', 'Drag & scroll = zoom', '拖拉+滾輪縮放') }}
    </div>
  </div>

  {{-- SVG will be injected here by D3 --}}
  <svg id="org-svg" width="100%" height="100%"></svg>

  {{-- Tooltip --}}
  <div id="tip"></div>

  {{-- Zoom controls --}}
  <div id="controls">
    <button class="ctrl-btn" id="btn-zoomin"  title="Zoom In"><i class="fas fa-plus"></i></button>
    <button class="ctrl-btn" id="btn-zoomout" title="Zoom Out"><i class="fas fa-minus"></i></button>
    <button class="ctrl-btn" id="btn-fit"     title="Fit to screen"><i class="fas fa-expand"></i></button>
  </div>

  {{-- Legend --}}
  <div id="legend">
    <h4>{{ $t('ລາຍລະອຽດ','Legend','圖例') }}</h4>
    <div class="leg-item">
      <div class="leg-dot" style="background:#c5a021;"></div>
      <span style="color:#475569;">{{ $t('ປະທານ','President','主席') }}</span>
    </div>
    <div id="dept-legend"></div>
    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0;">
      <div class="leg-item">
        <div style="width:28px;height:2px;background:#c5a021;opacity:.5;flex-shrink:0;"></div>
        <span style="color:#94a3b8;font-size:10px;">{{ $t('ສາຍການບັງຄັບບັນຊາ','Reporting Line','報告線') }}</span>
      </div>
    </div>
  </div>

</div>

{{-- Tooltip element (outside canvas so it can overflow) --}}
<div id="tip"></div>

@endsection

@push('scripts')
{{-- D3.js v7 --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.9.0/d3.min.js"></script>

<script>
(function () {
  'use strict';

  // ─── Data from PHP ───────────────────────────────────────────────────────────
  const rawData = @json($treeData);

  // ─── Palette ─────────────────────────────────────────────────────────────────
  const DEPT_COLORS = ['#c5a021','#1a5fa8','#1a7a4a','#7c3a9f'];
  const DEPT_BG     = ['#fef9e7','#eff6ff','#f0fdf4','#faf5ff'];
  const LINE_GOLD   = '#c5a021';

  // ─── Radii by level ─────────────────────────────────────────────────────────
  const R = { 1: 46, 2: 34, 3: 24 };

  // ─── Gender SVG icon paths (simplified monk/female/male) ─────────────────────
  function genderIcon(gender) {
    if (gender === 'monk')   return '\u{1F9D8}'; // used in title attr only
    if (gender === 'female') return '♀';
    return '♂';
  }

  // ─── Build chart after DOM ready ─────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', () => {
    const spinner  = document.getElementById('d3-spinner');
    const canvas   = document.getElementById('org-canvas');
    const svgEl    = document.getElementById('org-svg');
    const tip      = document.getElementById('tip');
    const compareH = document.getElementById('compare-bar').offsetHeight;

    // Adjust canvas top for compare bar
    canvas.style.paddingTop = compareH + 'px';

    const W = canvas.clientWidth;
    const H = canvas.clientHeight - compareH;

    // ─── SVG setup ──────────────────────────────────────────────────────────
    const svg = d3.select('#org-svg')
      .attr('viewBox', `0 0 ${W} ${H}`)
      .style('display', 'block');

    const g = svg.append('g').attr('class', 'chart-root');

    // ─── Zoom ────────────────────────────────────────────────────────────────
    const zoom = d3.zoom()
      .scaleExtent([0.15, 4])
      .on('zoom', e => {
        g.attr('transform', e.transform);
      });

    svg.call(zoom)
       .on('dblclick.zoom', null);  // disable dbl-click zoom

    canvas.addEventListener('mousedown', () => canvas.classList.add('panning'));
    canvas.addEventListener('mouseup',   () => canvas.classList.remove('panning'));

    // ─── Controls ────────────────────────────────────────────────────────────
    function fitView(dur = 600) {
      const bounds = g.node().getBBox();
      if (!bounds.width || !bounds.height) return;
      const pad = 60;
      const scaleX = (W - pad * 2) / bounds.width;
      const scaleY = (H - pad * 2) / bounds.height;
      const scale  = Math.min(scaleX, scaleY, 1.6);
      const tx = W / 2 - scale * (bounds.x + bounds.width  / 2);
      const ty = H / 2 - scale * (bounds.y + bounds.height / 2) + compareH;
      svg.transition().duration(dur)
         .call(zoom.transform, d3.zoomIdentity.translate(tx, ty).scale(scale));
    }

    document.getElementById('btn-zoomin') .addEventListener('click', () => svg.transition().duration(300).call(zoom.scaleBy, 1.4));
    document.getElementById('btn-zoomout').addEventListener('click', () => svg.transition().duration(300).call(zoom.scaleBy, 0.7));
    document.getElementById('btn-fit')    .addEventListener('click', () => fitView());

    // ─── Hierarchy + layout ──────────────────────────────────────────────────
    if (!rawData || !rawData.id) {
      spinner.innerHTML = '<p style="color:#94a3b8;font-size:13px;">ບໍ່ມີຂໍ້ມູນ / No data</p>';
      return;
    }

    const root = d3.hierarchy(rawData);

    // Separate dy per level for visual balance
    const DX = 148;   // horizontal spacing
    const DY = 210;   // vertical spacing

    const treeLayout = d3.tree().nodeSize([DX, DY]);
    treeLayout(root);

    // ─── defs: clip-paths + gradient ─────────────────────────────────────────
    const defs = svg.append('defs');

    // Radial gradient for president glow
    const glow = defs.append('radialGradient').attr('id', 'glow-l1')
                     .attr('cx','50%').attr('cy','50%').attr('r','50%');
    glow.append('stop').attr('offset','0%').attr('stop-color','#c5a021').attr('stop-opacity','.25');
    glow.append('stop').attr('offset','100%').attr('stop-color','#c5a021').attr('stop-opacity','0');

    // Drop shadows
    const shadow = defs.append('filter').attr('id','node-shadow')
                       .attr('x','-30%').attr('y','-30%').attr('width','160%').attr('height','160%');
    shadow.append('feDropShadow').attr('dx',0).attr('dy',3).attr('stdDeviation',4)
          .attr('flood-color','rgba(0,0,0,.18)');

    // Clip paths per node
    root.descendants().forEach(d => {
      const r = R[d.data.level] || 24;
      defs.append('clipPath').attr('id', `clip-${d.data.id}`)
          .append('circle').attr('cx', 0).attr('cy', 0).attr('r', r - 2);
    });

    // ─── Links ───────────────────────────────────────────────────────────────
    const linkGen = d3.linkVertical()
      .x(d => d.x)
      .y(d => d.y);

    g.selectAll('.link')
      .data(root.links())
      .join('path')
      .attr('class', d => {
        const idx = d.target.data.deptIdx;
        return `link${idx >= 0 ? ' dept-'+idx : ''}`;
      })
      .attr('d', linkGen)
      .attr('opacity', 0)
      .transition().duration(800).delay((d, i) => i * 30 + 200)
      .attr('opacity', 1);

    // ─── Nodes ───────────────────────────────────────────────────────────────
    const node = g.selectAll('.node')
      .data(root.descendants())
      .join('g')
      .attr('class', d => `node dept-${d.data.deptIdx}`)
      .attr('transform', d => `translate(${d.data.x ?? d.x},${d.parent ? d.parent.y : d.y})`)  // start at parent pos
      .attr('opacity', 0);

    // Animate nodes in
    node.transition().duration(600).delay((d, i) => i * 40 + 100)
        .attr('transform', d => `translate(${d.x},${d.y})`)
        .attr('opacity', 1);

    // ── President glow ring ──
    node.filter(d => d.data.level === 1)
      .append('circle')
      .attr('r', R[1] + 14)
      .attr('fill', 'url(#glow-l1)');

    // ── Outer ring (white bg circle with shadow) ──
    node.append('circle')
      .attr('class', d => `node-ring node-ring-l${d.data.level}`)
      .attr('r', d => R[d.data.level] || 24)
      .attr('filter', 'url(#node-shadow)');

    // ── Photo image ──
    node.append('image')
      .attr('href',   d => d.data.photo || '')
      .attr('x',      d => -(R[d.data.level] || 24) + 2)
      .attr('y',      d => -(R[d.data.level] || 24) + 2)
      .attr('width',  d => (R[d.data.level] || 24) * 2 - 4)
      .attr('height', d => (R[d.data.level] || 24) * 2 - 4)
      .attr('clip-path', d => `url(#clip-${d.data.id})`)
      .attr('preserveAspectRatio', 'xMidYMid slice')
      .style('display', d => d.data.photo ? null : 'none');

    // ── Fallback icon (when no photo) ──
    node.filter(d => !d.data.photo)
      .append('circle')
      .attr('r', d => R[d.data.level] || 24)
      .attr('fill', d => d.data.deptIdx >= 0 ? DEPT_BG[d.data.deptIdx % DEPT_BG.length] : '#fef9e7');

    node.filter(d => !d.data.photo)
      .append('text')
      .attr('text-anchor', 'middle')
      .attr('dominant-baseline', 'central')
      .attr('font-size', d => (R[d.data.level] || 24) * 0.85)
      .attr('fill', d => d.data.deptIdx >= 0 ? DEPT_COLORS[d.data.deptIdx % DEPT_COLORS.length] : '#c5a021')
      .text(d => {
        if (d.data.gender === 'monk')   return '🙏';
        if (d.data.gender === 'female') return '♀';
        return '♂';
      });

    // ── Name label ──
    node.append('text')
      .attr('class', 'node-name')
      .attr('text-anchor', 'middle')
      .attr('y', d => (R[d.data.level] || 24) + 14)
      .attr('font-size', d => d.data.level === 1 ? 13 : d.data.level === 2 ? 11 : 10)
      .attr('font-weight', d => d.data.level <= 2 ? '700' : '600')
      .attr('fill', '#1e293b')
      .text(d => {
        const name = d.data.name || '';
        // Truncate long names for L3
        return d.data.level === 3 && name.length > 18 ? name.slice(0, 17) + '…' : name;
      });

    // ── Position label ──
    node.append('text')
      .attr('text-anchor', 'middle')
      .attr('y', d => (R[d.data.level] || 24) + 28)
      .attr('font-size', d => d.data.level === 1 ? 11 : 10)
      .attr('fill', d => {
        if (d.data.level === 1) return '#c5a021';
        if (d.data.deptIdx >= 0) return DEPT_COLORS[d.data.deptIdx % DEPT_COLORS.length];
        return '#64748b';
      })
      .attr('font-weight', '600')
      .text(d => {
        const pos = d.data.position || '';
        return pos.length > 20 ? pos.slice(0, 19) + '…' : pos;
      });

    // ── Dept badge for L2 ──
    const l2badge = node.filter(d => d.data.level === 2 && d.data.dept);

    l2badge.append('rect')
      .attr('x', d => -(d.data.dept.length * 3.5 + 10))
      .attr('y', d => (R[2]) + 40)
      .attr('width', d => d.data.dept.length * 7 + 20)
      .attr('height', 16)
      .attr('rx', 8)
      .attr('fill', d => DEPT_BG[d.data.deptIdx % DEPT_BG.length])
      .attr('stroke', d => DEPT_COLORS[d.data.deptIdx % DEPT_COLORS.length])
      .attr('stroke-width', .8);

    l2badge.append('text')
      .attr('text-anchor', 'middle')
      .attr('y', d => (R[2]) + 51)
      .attr('font-size', 9)
      .attr('fill', d => DEPT_COLORS[d.data.deptIdx % DEPT_COLORS.length])
      .attr('font-weight', '700')
      .text(d => d.data.dept);

    // ─── Tooltip ─────────────────────────────────────────────────────────────
    const tooltip = d3.select('#tip');

    node.on('mouseover', function (event, d) {
      const lines = [
        `<strong>${d.data.name || ''}</strong>`,
        d.data.position ? `<div class="tip-pos">${d.data.position}</div>` : '',
        d.data.dept     ? `<div class="tip-dept">${d.data.dept}</div>` : '',
      ].filter(Boolean).join('');

      tooltip.style('display', 'block').html(lines);
      d3.select(this).select('.node-ring').attr('stroke-width', d.data.level === 1 ? 6 : 4);
    })
    .on('mousemove', function (event) {
      const x = event.clientX, y = event.clientY;
      const tipW = 220, tipH = 70;
      tooltip
        .style('left', (x + 14) + 'px')
        .style('top',  (y - tipH / 2) + 'px');
    })
    .on('mouseleave', function (event, d) {
      tooltip.style('display', 'none');
      const orig = d.data.level === 1 ? 4 : d.data.level === 2 ? 3 : 2;
      d3.select(this).select('.node-ring').attr('stroke-width', orig);
    });

    // ─── Click: highlight branch ──────────────────────────────────────────────
    node.on('click', function (event, d) {
      event.stopPropagation();
      // Dim everything
      g.selectAll('.node').transition().duration(200).attr('opacity', .25);
      g.selectAll('.link').transition().duration(200).attr('opacity', .1);
      // Highlight ancestors + descendants
      const ids = new Set();
      d.ancestors().forEach(a => ids.add(a.data.id));
      d.descendants().forEach(a => ids.add(a.data.id));
      g.selectAll('.node').filter(n => ids.has(n.data.id)).transition().duration(200).attr('opacity', 1);
      g.selectAll('.link').filter(l => ids.has(l.source.data.id) && ids.has(l.target.data.id))
        .transition().duration(200).attr('opacity', 1);
    });

    svg.on('click', () => {
      g.selectAll('.node').transition().duration(200).attr('opacity', 1);
      g.selectAll('.link').transition().duration(200).attr('opacity', 1);
    });

    // ─── Legend: dept labels ──────────────────────────────────────────────────
    const deptLegend = document.getElementById('dept-legend');
    const depts = [...new Set(root.descendants()
      .filter(d => d.data.level === 2 && d.data.dept)
      .map(d => JSON.stringify({ label: d.data.dept, idx: d.data.deptIdx }))
    )].map(s => JSON.parse(s));

    depts.forEach(({ label, idx }) => {
      const col = DEPT_COLORS[idx % DEPT_COLORS.length];
      deptLegend.innerHTML += `
        <div class="leg-item">
          <div class="leg-dot" style="background:${col};"></div>
          <span style="color:#475569;">${label}</span>
        </div>`;
    });

    // ─── Fit on load ─────────────────────────────────────────────────────────
    spinner.style.display = 'none';
    setTimeout(() => fitView(800), 50);
  });
})();
</script>
@endpush
