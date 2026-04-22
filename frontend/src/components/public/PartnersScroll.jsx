import { useState, useEffect, useRef } from 'react'
import api from '../../api/client'

export default function PartnersScroll() {
  const [partners, setPartners] = useState([])
  const trackRef = useRef(null)

  useEffect(() => {
    api.get('/public/partners')
      .then(r => setPartners(r.data || []))
      .catch(() => {})
  }, [])

  // Duplicate for seamless loop
  const items = [...partners, ...partners]

  if (!partners.length) return null

  return (
    <section className="py-10 bg-gray-50 border-y border-gray-200 overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 mb-6">
        <h2 className="text-center text-lg font-bold text-blue-900">
          ອົງການ ຄູ່ຮ່ວມງານ
        </h2>
        <div className="h-1 w-12 bg-yellow-400 mx-auto mt-2 rounded" />
      </div>

      <div className="relative">
        {/* Fade edges */}
        <div className="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-50 to-transparent z-10 pointer-events-none" />
        <div className="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-50 to-transparent z-10 pointer-events-none" />

        <div
          ref={trackRef}
          className="flex gap-8 items-center"
          style={{ animation: `scroll-x ${partners.length * 3}s linear infinite` }}
        >
          {items.map((p, i) => (
            <a key={`${p.id}-${i}`}
              href={p.website || '#'}
              target="_blank"
              rel="noreferrer"
              className="flex-shrink-0 flex flex-col items-center gap-2 group">
              <div className="w-20 h-14 flex items-center justify-center bg-white rounded-lg shadow-sm border border-gray-100 p-2 group-hover:shadow-md transition-shadow">
                {p.logo_url ? (
                  <img src={p.logo_url} alt={p.name}
                    className="max-w-full max-h-full object-contain grayscale group-hover:grayscale-0 transition-all" />
                ) : (
                  <div className="text-[10px] font-bold text-center text-blue-700 leading-tight">
                    {p.acronym || p.name?.slice(0, 6)}
                  </div>
                )}
              </div>
              <span className="text-[10px] text-gray-500 group-hover:text-blue-700 transition-colors max-w-[80px] text-center leading-tight">
                {p.acronym || p.name?.slice(0, 12)}
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
