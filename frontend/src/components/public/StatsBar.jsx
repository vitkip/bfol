import { useState, useEffect, useRef } from 'react'
import api from '../../api/client'

function useCountUp(target, duration = 1800, started = false) {
  const [count, setCount] = useState(0)
  useEffect(() => {
    if (!started || !target) return
    let start = 0
    const step = target / (duration / 16)
    const timer = setInterval(() => {
      start += step
      if (start >= target) { setCount(target); clearInterval(timer) }
      else setCount(Math.floor(start))
    }, 16)
    return () => clearInterval(timer)
  }, [target, duration, started])
  return count
}

function StatItem({ label, value, icon, suffix, index, total }) {
  const [started, setStarted] = useState(false)
  const ref = useRef(null)
  const count = useCountUp(value, 1800, started)

  useEffect(() => {
    const obs = new IntersectionObserver(
      ([e]) => { if (e.isIntersecting) setStarted(true) },
      { threshold: 0.3 }
    )
    if (ref.current) obs.observe(ref.current)
    return () => obs.disconnect()
  }, [])

  return (
    <div
      ref={ref}
      className="relative flex flex-col items-center text-center px-6 py-10 group"
      style={{ animationDelay: `${index * 100}ms` }}
    >
      {/* Vertical divider (not on last item) */}
      {index < total - 1 && (
        <div className="absolute right-0 top-1/4 bottom-1/4 w-px bg-white/10 hidden md:block" />
      )}

      {/* Icon ring — glassmorphism */}
      <div className="relative mb-5">
        <div className="w-16 h-16 rounded-2xl glass flex items-center justify-center
          group-hover:bg-amber-500/15 group-hover:scale-110 transition-all duration-300">
          <i className={`${icon || 'fas fa-star'} text-amber-400 text-2xl`} />
        </div>
      </div>

      {/* Number */}
      <div className="text-4xl font-extrabold text-white mb-1 tabular-nums leading-none">
        {count.toLocaleString()}
        <span className="text-amber-400 text-2xl ml-0.5">{suffix}</span>
      </div>

      {/* Label */}
      <div className="text-slate-400 text-sm font-medium mt-1">{label}</div>
    </div>
  )
}

const fallback = [
  { id: 1, label: 'ສະມາຊິກ',       value: 1200, icon: 'fas fa-users',          suffix: '+' },
  { id: 2, label: 'ວັດ/ສໍານັກ',     value: 800,  icon: 'fas fa-place-of-worship', suffix: '+' },
  { id: 3, label: 'ພ້ອຍ/ສາມະເນນ',   value: 5000, icon: 'fas fa-dharmachakra',    suffix: '+' },
  { id: 4, label: 'ປະເທດຄູ່ຮ່ວມ',   value: 25,   icon: 'fas fa-globe',           suffix: '' },
]

export default function StatsBar() {
  const [stats, setStats] = useState(fallback)

  useEffect(() => {
    api.get('/public/stats')
      .then(r => { if (r.data?.length) setStats(r.data) })
      .catch(() => {})
  }, [])

  return (
    <section className="relative overflow-hidden border-y border-white/5">
      {/* Background decoration */}
      <div className="absolute inset-0 bg-indigo-950/60 backdrop-blur-sm" />
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-10 -left-10 w-40 h-40 rounded-full bg-amber-500/5 blur-[100px]" />
        <div className="absolute -bottom-10 -right-10 w-40 h-40 rounded-full bg-blue-500/5 blur-[100px]" />
      </div>

      <div className="relative max-w-[1400px] mx-auto px-4">
        <div className="grid grid-cols-2 md:grid-cols-4">
          {stats.map((s, i) => (
            <StatItem key={s.id} {...s} index={i} total={stats.length} />
          ))}
        </div>
      </div>
    </section>
  )
}
