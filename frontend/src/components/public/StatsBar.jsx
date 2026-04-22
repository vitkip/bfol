import { useState, useEffect, useRef } from 'react'
import api from '../../api/client'

function useCountUp(target, duration = 1500, started = false) {
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

function StatItem({ label, value, icon, suffix }) {
  const [started, setStarted] = useState(false)
  const ref = useRef(null)
  const count = useCountUp(value, 1500, started)

  useEffect(() => {
    const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting) setStarted(true) }, { threshold: 0.3 })
    if (ref.current) obs.observe(ref.current)
    return () => obs.disconnect()
  }, [])

  return (
    <div ref={ref} className="flex flex-col items-center text-center px-6 py-4">
      <div className="w-14 h-14 rounded-full bg-blue-800 flex items-center justify-center mb-3">
        <i className={`${icon || 'fas fa-star'} text-yellow-400 text-xl`} />
      </div>
      <div className="text-3xl font-bold text-white">
        {count.toLocaleString()}{suffix}
      </div>
      <div className="text-blue-300 text-sm mt-1">{label}</div>
    </div>
  )
}

const fallback = [
  { id: 1, label: 'ສະມາຊິກ', value: 1200, icon: 'fas fa-users', suffix: '+' },
  { id: 2, label: 'ວັດ/ສໍານັກ', value: 800, icon: 'fas fa-place-of-worship', suffix: '+' },
  { id: 3, label: 'ພ້ອຍ/ສາມະເນນ', value: 5000, icon: 'fas fa-dharmachakra', suffix: '+' },
  { id: 4, label: 'ປະເທດຄູ່ຮ່ວມ', value: 25, icon: 'fas fa-globe', suffix: '' },
]

export default function StatsBar() {
  const [stats, setStats] = useState(fallback)

  useEffect(() => {
    api.get('/public/stats')
      .then(r => { if (r.data?.length) setStats(r.data) })
      .catch(() => {})
  }, [])

  return (
    <section className="bg-blue-900">
      <div className="max-w-7xl mx-auto px-4">
        <div className="grid grid-cols-2 md:grid-cols-4 divide-x divide-blue-800">
          {stats.map(s => <StatItem key={s.id} {...s} />)}
        </div>
      </div>
    </section>
  )
}
