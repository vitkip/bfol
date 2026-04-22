import { useState, useEffect, useCallback } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/client'

const fallbackSlides = [
  {
    id: 1,
    tag: 'ອົງການສາສະໜາ',
    title: 'ສະຫະພັນພຸດທະສາສະໜາລາວ',
    subtitle: 'ຮ່ວມກັນສົ່ງເສີມ ແລະ ພັດທະນາ ດ້ານສາສະໜາ',
    image_url: null,
    btn1_text: 'ຮຽນຮູ້ເພີ່ມ',
    btn1_url: '/about/history',
    btn2_text: 'ຕິດຕໍ່ຕ',
    btn2_url: '/contact',
  },
]

export default function HeroSlider() {
  const [slides, setSlides] = useState(fallbackSlides)
  const [current, setCurrent] = useState(0)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/public/slides')
      .then(r => { if (r.data?.length) setSlides(r.data) })
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [])

  const prev = useCallback(() => setCurrent(c => (c - 1 + slides.length) % slides.length), [slides.length])
  const next = useCallback(() => setCurrent(c => (c + 1) % slides.length), [slides.length])

  useEffect(() => {
    const t = setInterval(next, 5000)
    return () => clearInterval(t)
  }, [next])

  const slide = slides[current]

  return (
    <section className="relative h-[520px] md:h-[620px] overflow-hidden bg-blue-950">
      {/* Slides */}
      {slides.map((s, i) => (
        <div key={s.id} className={`absolute inset-0 transition-opacity duration-700 ${i === current ? 'opacity-100' : 'opacity-0'}`}>
          {s.image_url ? (
            <img src={s.image_url} alt={s.title} className="w-full h-full object-cover" />
          ) : (
            <div className="w-full h-full bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-900" />
          )}
          <div className="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent" />
        </div>
      ))}

      {/* Content */}
      <div className="relative z-10 h-full flex items-center">
        <div className="max-w-7xl mx-auto px-6 w-full">
          <div className="max-w-2xl">
            {slide.tag && (
              <span className="inline-block bg-yellow-400 text-blue-900 text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wide">
                {slide.tag}
              </span>
            )}
            <h1 className="text-3xl md:text-5xl font-bold text-white leading-tight mb-4">
              {slide.title}
            </h1>
            {slide.subtitle && (
              <p className="text-blue-200 text-base md:text-lg mb-8 leading-relaxed">
                {slide.subtitle}
              </p>
            )}
            <div className="flex flex-wrap gap-3">
              {slide.btn1_text && (
                <Link to={slide.btn1_url || '#'}
                  className="px-6 py-3 bg-yellow-400 text-blue-900 font-bold rounded-lg hover:bg-yellow-300 transition-colors">
                  {slide.btn1_text}
                </Link>
              )}
              {slide.btn2_text && (
                <Link to={slide.btn2_url || '#'}
                  className="px-6 py-3 border-2 border-white text-white font-medium rounded-lg hover:bg-white hover:text-blue-900 transition-colors">
                  {slide.btn2_text}
                </Link>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Prev / Next */}
      <button onClick={prev}
        className="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition-colors">
        <i className="fas fa-chevron-left" />
      </button>
      <button onClick={next}
        className="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition-colors">
        <i className="fas fa-chevron-right" />
      </button>

      {/* Dots */}
      <div className="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        {slides.map((_, i) => (
          <button key={i} onClick={() => setCurrent(i)}
            className={`h-2 rounded-full transition-all duration-300 ${i === current ? 'w-8 bg-yellow-400' : 'w-2 bg-white/50'}`} />
        ))}
      </div>
    </section>
  )
}
