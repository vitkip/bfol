import { useState, useEffect } from 'react'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

export default function PartnersScroll() {
  const [partners, setPartners] = useState([])
  const { pick } = useLang()

  useEffect(() => {
    api.get('/public/partners')
      .then(r => setPartners(r.data || []))
      .catch(() => {})
  }, [])

  if (!partners.length) return null

  const items = [...partners, ...partners]

  return (
    <section className="py-12 border-y border-white/5 overflow-hidden relative">
      <div className="max-w-[1400px] mx-auto px-4 mb-8 text-center">
        <span className="inline-block text-xs font-bold text-amber-400 uppercase tracking-[0.2em] mb-2 glass px-3 py-1 rounded-full">
          {pick('ຄູ່ຮ່ວມງານ', 'Partners', '合作夥伴')}
        </span>
        <h2 className="text-xl font-bold text-white">
          {pick('ອົງການ ຄູ່ຮ່ວມງານ ສາກົນ', 'International Partner Organizations', '國際合作夥伴機構')}
        </h2>
        <div className="h-1 w-12 bg-gradient-to-r from-amber-400 to-amber-600 mx-auto mt-3 rounded-full" />
      </div>

      <div className="relative">
        {/* Fade edges */}
        <div className="absolute left-0 top-0 bottom-0 w-24 md:w-40 bg-gradient-to-r from-[#0f0a1e] to-transparent z-10 pointer-events-none" />
        <div className="absolute right-0 top-0 bottom-0 w-24 md:w-40 bg-gradient-to-l from-[#0f0a1e] to-transparent z-10 pointer-events-none" />

        <div
          className="flex gap-6 items-center"
          style={{ animation: `scroll-x ${partners.length * 3.5}s linear infinite` }}
        >
          {items.map((p, i) => (
            <a
              key={`${p.id}-${i}`}
              href={p.website || '#'}
              target="_blank"
              rel="noreferrer"
              className="flex-shrink-0 flex flex-col items-center gap-2.5 group cursor-pointer"
            >
              <div className="w-24 h-16 flex items-center justify-center glass rounded-xl p-3
                group-hover:bg-white/10 group-hover:scale-105 transition-all duration-200">
                {p.logo_url ? (
                  <img src={p.logo_url} alt={p.name}
                    className="max-w-full max-h-full object-contain grayscale group-hover:grayscale-0 opacity-60 group-hover:opacity-100 transition-all duration-300" />
                ) : (
                  <div className="text-xs font-bold text-center text-slate-400 leading-tight">
                    {p.acronym || p.name?.slice(0, 6)}
                  </div>
                )}
              </div>
              <span className="text-[11px] text-slate-500 group-hover:text-amber-400 transition-colors max-w-[90px] text-center leading-tight">
                {p.acronym || p.name?.slice(0, 14)}
              </span>
            </a>
          ))}
        </div>
      </div>

      <style>{`
        @keyframes scroll-x {
          0%   { transform: translateX(0); }
          100% { transform: translateX(-50%); }
        }
      `}</style>
    </section>
  )
}
