<style>
.orb-field{position:absolute;inset:0;overflow:hidden;pointer-events:none}
.orb{position:absolute;border-radius:50%;filter:blur(1px);opacity:.6;animation:floatY 9s ease-in-out infinite}
.orb.o1{width:14px;height:14px;background:#2dd4bf;top:22%;left:12%;box-shadow:0 0 18px #2dd4bf}
.orb.o2{width:9px;height:9px;background:#f0b429;top:60%;left:22%;animation-delay:-3s;box-shadow:0 0 14px #f0b429}
.orb.o3{width:12px;height:12px;background:#a7f3d0;top:35%;left:78%;animation-delay:-5s;box-shadow:0 0 16px #a7f3d0}
.orb.o4{width:7px;height:7px;background:#fff;top:70%;left:64%;animation-delay:-7s;box-shadow:0 0 12px #fff}
.orb.o5{width:10px;height:10px;background:#2dd4bf;top:15%;left:55%;animation-delay:-2s;box-shadow:0 0 14px #2dd4bf}
.shine{position:relative;overflow:hidden}
.shine::after{content:'';position:absolute;top:0;left:-80%;width:50%;height:100%;background:linear-gradient(120deg,transparent,rgba(255,255,255,.35),transparent);transform:skewX(-20deg);transition:left .7s ease;pointer-events:none}
.shine:hover::after{left:130%}
.flip{perspective:1200px;background:transparent;border:none;box-shadow:none;padding:0}
.flip-inner{position:relative;width:100%;min-height:250px;transform-style:preserve-3d;transition:transform .8s cubic-bezier(.22,.68,.32,1)}
.flip:hover .flip-inner{transform:rotateY(180deg)}
.flip-face{position:absolute;inset:0;backface-visibility:hidden;border-radius:var(--radius);padding:1.6rem;display:flex;flex-direction:column;justify-content:flex-end}
.flip-front{background:linear-gradient(160deg,#0f6f5c,#06382e);color:#fff;box-shadow:var(--shadow)}
.flip-front h3{color:#fff}
.flip-front p{color:rgba(255,255,255,.8)}
.flip-back{background:#fff;transform:rotateY(180deg);border:1px solid var(--border);box-shadow:var(--shadow);justify-content:center;align-items:flex-start}
.live-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#16a34a;box-shadow:0 0 0 0 rgba(22,163,74,.5);animation:pulseDot 1.6s infinite}
.ring-wrap{display:flex;gap:1.4rem;flex-wrap:wrap;justify-content:center}
.ring{position:relative;width:92px;height:92px}
.ring svg{transform:rotate(-90deg)}
.ring circle{fill:none;stroke-width:8;stroke-linecap:round}
.ring .bgc{stroke:rgba(15,111,92,.12)}
.ring .fgc{stroke:url(#gradRing);stroke-dasharray:264;stroke-dashoffset:264;animation:ringFill 1.6s cubic-bezier(.22,.68,.32,1) forwards}
.ring .val{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:800;color:var(--primary-dark);font-size:1rem}
.ring .val small{font-size:.58rem;color:var(--muted);font-weight:600;text-align:center}
@keyframes ringFill{to{stroke-dashoffset:var(--off,0)}}
.ticker{overflow:hidden;border:1px solid var(--border);border-radius:999px;background:#fff;padding:.55rem 0;box-shadow:var(--shadow)}
.ticker-track{display:inline-flex;white-space:nowrap;animation:marquee 26s linear infinite}
.ticker-item{padding:0 1.4rem;font-size:.82rem;font-weight:600;color:var(--muted)}
.ticker-item b{color:var(--primary)}
.stepper{display:flex;align-items:center;gap:.4rem;justify-content:center;margin:1.4rem 0}
.step-dot{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;background:#fff;border:2px solid var(--border);color:var(--muted)}
.step-dot.on{background:linear-gradient(135deg,var(--primary),var(--accent-2));border-color:transparent;color:#fff;box-shadow:0 8px 18px rgba(15,111,92,.35)}
.step-bar{flex:1;max-width:70px;height:3px;border-radius:999px;background:var(--border);position:relative;overflow:hidden}
.step-bar.on::after{content:'';position:absolute;inset:0;background:linear-gradient(90deg,var(--primary),var(--accent-2));animation:growX 1s ease forwards}
@keyframes growX{from{transform:scaleX(0)}to{transform:scaleX(1)}}
</style>
<svg width="0" height="0" style="position:absolute"><defs><linearGradient id="gradRing" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f6f5c"/><stop offset="1" stop-color="#2dd4bf"/></linearGradient></defs></svg>