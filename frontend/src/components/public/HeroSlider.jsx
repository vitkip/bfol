import { useState, useEffect, useCallback } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/client'

const fallbackSlides = [
  {
    id: 1,
    tag: 'ອົງການສາສະໜາ',
    title: 'ສະຫະພັນພຸດທະສາສະໜາລາວ',
    subtitle: 'ຮ່ວມກັນສົ່ງເສີມ ແລະ ພັດທະນາ ດ້ານສາສະໜາ ທາງ ລາວ ໃນ ລະດັບ ຊາດ ແລະ ສາກົນ',
    image_url: null,
    btn1_text: 'ຮຽນຮູ້ເພີ່ມ',
    btn1_url: '/about/history',
    btn2_text: 'ຕິດຕໍ່ພວກເຮົາ',
    btn2_url: '/contact',
  },
]

export default function HeroSlider() {
  const [slides, setSlides]   = useState(fallbackSlides)
  const [current, setCurrent] = useState(0)
  const [paused, setPaused]   = useState(false)

  useEffect(() => {
    api.get('/public/slides')
      .then(r => { if (r.data?.length) setSlides(r.data) })
      .catch(() => {})
  }, [])

  const prev = useCallback(() => setCurrent(c => (c - 1 + slides.length) % slides.length), [slides.length])
  const next = useCallback(() => setCurrent(c => (c + 1) % slides.length), [slides.length])

  useEffect(() => {
    if (paused) return
    const t = setInterval(next, 6000)
    return () => clearInterval(t)
  }, [next, paused])

  const slide = slides[current]

  return (
    <section
      className="relative h-[560px] md:h-[680px] overflow-hidden"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
    >
      {/* Slides */}
      {slides.map((s, i) => (
        <div
          key={s.id}
          className={`absolute inset-0 transition-all duration-1000 ${
            i === current ? 'opacity-100 scale-100' : 'opacity-0 scale-105'
          }`}
        >
          {s.image_url ? (
            <img src={s.image_url} alt={s.title} className="w-full h-full object-cover" />
          ) : (
            <div className="w-full h-full bg-gradient-to-br from-indigo-950 via-purple-900 to-indigo-950 relative">
              {/* Floating orbs */}
              <div className="absolute top-20 right-20 w-96 h-96 rounded-full bg-amber-500/10 blur-[120px] animate-float" />
              <div className="absolute bottom-10 right-40 w-64 h-64 rounded-full bg-blue-500/15 blur-[100px]" style={{animationDelay:'2s'}} />
              <div className="absolute top-1/2 left-1/3 w-48 h-48 rounded-full bg-purple-500/10 blur-[80px]" style={{animationDelay:'4s'}} />
            </div>
          )}
          <div className="absolute inset-0 bg-gradient-to-r from-indigo-950/90 via-indigo-950/50 to-transparent" />
          <div className="absolute inset-0 bg-gradient-to-t from-indigo-950/60 via-transparent to-transparent" />
        </div>
      ))}

      {/* Content */}
      <div className="relative z-10 h-full flex items-center">
        <div className="max-w-[1400px] mx-auto px-4 w-full">
          <div className="max-w-2xl">
            {slide.tag && (
              <div className="flex items-center gap-3 mb-5 animate-fade-in-up">
                <div className="h-px w-10 bg-gradient-to-r from-amber-400 to-transparent" />
                <span className="text-amber-400 text-xs font-bold uppercase tracking-[0.2em]">
                  {slide.tag}
                </span>
              </div>
            )}
            <h1 className="text-2xl md:text-4xl lg:text-5xl font-extrabold text-white leading-[1.5] mb-5 drop-shadow-2xl animate-fade-in-up"
              style={{animationDelay:'0.1s'}}>
              {slide.title}
            </h1>
            {slide.subtitle && (
              <p className="text-slate-300 text-base md:text-lg mb-9 leading-relaxed max-w-xl drop-shadow animate-fade-in-up"
                style={{animationDelay:'0.2s'}}>
                {slide.subtitle}
              </p>
            )}
            <div className="flex flex-wrap gap-4 animate-fade-in-up" style={{animationDelay:'0.3s'}}>
              {slide.btn1_text && (
                <Link to={slide.btn1_url || '#'}
                  className="group inline-flex items-center gap-2 px-7 py-3.5 bg-gradient-to-r from-amber-500 to-amber-400
                    text-indigo-950 font-bold rounded-2xl cursor-pointer
                    hover:from-amber-400 hover:to-amber-300 transition-all duration-300
                    shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5">
                  {slide.btn1_text}
                  <i className="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform" />
                </Link>
              )}
              {slide.btn2_text && (
                <Link to={slide.btn2_url || '#'}
                  className="inline-flex items-center gap-2 px-7 py-3.5 glass text-white font-medium rounded-2xl cursor-pointer
                    hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">
                  {slide.btn2_text}
                </Link>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Slide counter */}
      <div className="absolute top-6 right-6 z-20 text-white/40 text-sm font-mono hidden md:block glass rounded-xl px-3 py-1.5">
        <span className="text-amber-400 font-bold text-lg">{String(current + 1).padStart(2,'0')}</span>
        <span className="mx-1 text-white/20">/</span>
        {String(slides.length).padStart(2,'0')}
      </div>

      {/* Nav arrows */}
      <button onClick={prev}
        className="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 glass cursor-pointer
          text-white rounded-xl flex items-center justify-center transition-all duration-200
          hover:bg-white/15 hover:scale-110 hover:text-amber-400">
        <i className="fas fa-chevron-left text-sm" />
      </button>
      <button onClick={next}
        className="absolute right-4 md:right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 glass cursor-pointer
          text-white rounded-xl flex items-center justify-center transition-all duration-200
          hover:bg-white/15 hover:scale-110 hover:text-amber-400">
        <i className="fas fa-chevron-right text-sm" />
      </button>

      {/* Dot indicators */}
      <div className="absolute bottom-7 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
        {slides.map((_, i) => (
          <button key={i} onClick={() => setCurrent(i)}
            className={`rounded-full transition-all duration-400 cursor-pointer ${
              i === current
                ? 'w-8 h-2.5 bg-amber-400 shadow-sm shadow-amber-400/40'
                : 'w-2.5 h-2.5 bg-white/20 hover:bg-white/40'
            }`}
          />
        ))}
      </div>

      {/* Progress bar */}
      <div className="absolute bottom-0 left-0 right-0 h-0.5 bg-white/5 z-20">
        <div
          key={current}
          className="h-full bg-gradient-to-r from-amber-400 to-amber-500"
          style={{ animation: paused ? 'none' : 'progress 6s linear forwards' }}
        />
      </div>

      <style>{`
        @keyframes progress { from { width: 0% } to { width: 100% } }
      `}</style>
    </section>
  )
}
