import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

// ─── News Card ────────────────────────────────────────────────────────────────
function NewsCard({ item, featured = false }) {
  const { pick, ui } = useLang()
  const title   = pick(item.title_lo, item.title_en, item.title_zh)
  const excerpt = pick(item.excerpt_lo, item.excerpt_en, item.excerpt_zh)

  return (
    <Link
      to={`/news/${item.slug}`}
      className={`group flex bg-white/95 backdrop-blur-xl border border-white/20 rounded-[24px] overflow-hidden cursor-pointer shadow-lg hover:shadow-[0_8px_30px_rgba(37,99,235,0.15)] hover:-translate-y-1 hover:border-blue-200 transition-all duration-300
        ${featured ? 'flex-col sm:flex-row' : 'flex-col h-full'}`}
    >
      {/* Thumbnail */}
      <div className={`relative overflow-hidden bg-gradient-to-br from-indigo-900/50 to-purple-900/50 shrink-0
        ${featured ? 'sm:w-[45%] h-60 sm:h-auto' : 'aspect-[16/10]'}`}>
        {item.thumbnail ? (
          <img src={item.thumbnail} alt={title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
        ) : (
          <div className="w-full h-full flex items-center justify-center">
            <i className="fas fa-newspaper text-4xl text-white/20" />
          </div>
        )}
        
        {/* Gradient overlay on image */}
        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent" />
        
        {/* Text on image */}
        <div className="absolute bottom-5 left-5 right-5">
          <h3 className={`font-bold text-white leading-tight mb-1.5 drop-shadow-md
            ${featured ? 'text-xl sm:text-2xl line-clamp-2' : 'text-lg line-clamp-2'}`}>
            {title}
          </h3>
          <p className="text-sm text-slate-300 line-clamp-1 drop-shadow font-medium">
            {excerpt || item.published_at}
          </p>
        </div>
        
        {/* Urgent Badge */}
        {item.is_urgent && (
          <div className="absolute top-4 left-4">
             <span className="inline-flex items-center gap-1 bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-md">
               <i className="fas fa-bolt text-[9px]" /> {ui.urgent}
             </span>
          </div>
        )}
      </div>

      {/* Details Section */}
      <div className="flex flex-col justify-between p-5 flex-1 min-w-0 bg-white/50">
        <div>
          <div className="flex justify-between items-center mb-5">
            <div className="flex items-center gap-2 text-slate-500 font-medium text-sm">
              <i className="fas fa-newspaper text-amber-500" />
              <span>{item.category || pick('ຂ່າວສານ', 'News', '新聞')}</span>
            </div>
            <div className="flex items-center gap-1 text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition-colors group-hover:translate-x-0.5">
              {ui.readMore}
            </div>
          </div>
        </div>

        {/* Tags Row */}
        <div className="flex flex-wrap gap-2 mt-auto">
          <span className="px-3 py-1.5 bg-slate-100/80 text-slate-600 rounded-full text-xs font-medium border border-slate-200/50">
             {item.published_at}
          </span>
          {item.category && (
            <span className="px-3 py-1.5 bg-slate-100/80 text-slate-600 rounded-full text-xs font-medium border border-slate-200/50">
              {item.category}
            </span>
          )}
        </div>
      </div>
    </Link>
  )
}

// ─── Featured Card (image-dominant) ──────────────────────────────────────────
function FeaturedCard({ item, large = false }) {
  const { pick, ui } = useLang()
  const title = pick(item.title_lo, item.title_en, item.title_zh)
  const excerpt = pick(item.excerpt_lo, item.excerpt_en, item.excerpt_zh)

  return (
    <Link
      to={`/news/${item.slug}`}
      className="group flex flex-col bg-white/95 backdrop-blur-xl border border-white/20 rounded-[24px] overflow-hidden cursor-pointer shadow-lg hover:shadow-[0_8px_30px_rgba(37,99,235,0.15)] hover:-translate-y-1 hover:border-blue-200 transition-all duration-300 h-full"
    >
      {/* Thumbnail */}
      <div className={`relative overflow-hidden bg-gradient-to-br from-indigo-900/50 to-purple-900/50 shrink-0
        ${large ? 'aspect-[16/10] sm:aspect-[21/9]' : 'aspect-[16/10]'}`}>
        {item.thumbnail ? (
          <img src={item.thumbnail} alt={title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
        ) : (
          <div className="w-full h-full flex items-center justify-center">
            <i className="fas fa-star text-4xl text-white/20" />
          </div>
        )}
        
        {/* Gradient overlay on image */}
        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent" />
        
        {/* Text on image */}
        <div className="absolute bottom-5 left-5 right-5">
          <h3 className={`font-bold text-white leading-tight mb-2 drop-shadow-md
            ${large ? 'text-2xl sm:text-3xl line-clamp-2' : 'text-xl sm:text-2xl line-clamp-2'}`}>
            {title}
          </h3>
          <p className="text-sm text-slate-300 line-clamp-1 drop-shadow font-medium">
            {excerpt || item.published_at}
          </p>
        </div>
      </div>

      {/* Details Section */}
      <div className="flex flex-col justify-between p-5 flex-1 bg-white/50">
        <div className="flex justify-between items-center mb-5">
          <div className="flex items-center gap-2 text-slate-500 font-medium text-sm">
            <i className="fas fa-star text-amber-500" />
            <span>{item.category || pick('ພິເສດ', 'Featured', '特色')}</span>
          </div>
          <div className="flex items-center gap-1 text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition-colors group-hover:translate-x-0.5">
            {ui.readMore}
          </div>
        </div>

        {/* Tags Row */}
        <div className="flex flex-wrap gap-2 mt-auto">
          <span className="px-3 py-1.5 bg-slate-100/80 text-slate-600 rounded-full text-xs font-medium border border-slate-200/50">
             {item.published_at}
          </span>
          {item.category && (
            <span className="px-3 py-1.5 bg-slate-100/80 text-slate-600 rounded-full text-xs font-medium border border-slate-200/50">
              {item.category}
            </span>
          )}
        </div>
      </div>
    </Link>
  )
}

// ─── Event Card ───────────────────────────────────────────────────────────────
function EventCard({ item }) {
  const { pick, ui } = useLang()
  const title    = pick(item.title_lo, item.title_en, item.title_zh)
  const location = pick(item.location_lo, item.location_en, item.location_zh)

  const dateStr = item.start_date || item.published_at || ''
  const dateObj = dateStr ? new Date(dateStr) : null
  const day     = dateObj && !isNaN(dateObj) ? dateObj.getDate() : null
  const mon     = dateObj && !isNaN(dateObj)
    ? dateObj.toLocaleString('en', { month: 'short' }).toUpperCase() : null

  return (
    <Link to={`/news/${item.slug}`} className="group flex flex-col bg-white/95 backdrop-blur-xl border border-white/20 rounded-[24px] overflow-hidden cursor-pointer shadow-lg hover:shadow-[0_8px_30px_rgba(37,99,235,0.15)] hover:-translate-y-1 hover:border-blue-200 transition-all duration-300 h-full">
      {/* Image */}
      <div className="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-indigo-900/50 to-purple-900/50 shrink-0">
        {item.thumbnail ? (
          <img src={item.thumbnail} alt={title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
        ) : (
          <div className="w-full h-full flex items-center justify-center">
            <i className="fas fa-calendar-alt text-4xl text-white/20" />
          </div>
        )}
        
        {/* Gradient overlay on image */}
        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent" />
        
        {/* Text on image */}
        <div className="absolute bottom-4 left-5 right-5">
          <h3 className="font-bold text-white text-lg sm:text-xl leading-tight mb-1.5 drop-shadow-md line-clamp-2">
            {title}
          </h3>
          <p className="text-sm text-slate-300 line-clamp-1 drop-shadow flex items-center gap-1.5 font-medium">
            <i className="fas fa-map-marker-alt text-amber-400" /> {location || pick('ຍັງບໍ່ລະບຸ', 'TBA', '待定')}
          </p>
        </div>

        {/* Date badge */}
        {day && (
          <div className="absolute top-4 right-4 bg-white/90 backdrop-blur-md rounded-[16px]
            min-w-[54px] text-center px-2 py-2 shadow-lg border border-white/50">
            <div className="text-xl font-extrabold text-blue-900 leading-none">{day}</div>
            <div className="text-[10px] font-bold text-amber-500 uppercase tracking-wider mt-1">{mon}</div>
          </div>
        )}
      </div>

      {/* Body */}
      <div className="flex flex-col justify-between p-5 flex-1 bg-white/50">
        <div className="flex justify-between items-center mb-5">
          <div className="flex items-center gap-2 text-slate-500 font-medium text-sm">
            <i className="fas fa-clock text-amber-500" />
            <span>{dateStr || pick('ກຳນົດການໃໝ່', 'Upcoming', '即將舉行')}</span>
          </div>
          <div className="flex items-center gap-1 text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition-colors group-hover:translate-x-0.5">
            {pick('ລາຍລະອຽດ →', 'Details →', '詳情 →')}
          </div>
        </div>

        {/* Tags Row */}
        <div className="flex flex-wrap gap-2 mt-auto">
          {location && (
            <span className="px-3 py-1.5 bg-slate-100/80 text-slate-600 rounded-full text-xs font-medium border border-slate-200/50 flex items-center gap-1.5">
              <i className="fas fa-map-pin text-amber-500" /> {location}
            </span>
          )}
          <span className="px-3 py-1.5 bg-amber-50/80 text-amber-700 rounded-full text-xs font-medium border border-amber-200/50">
            {pick('ກິດຈະກຳ', 'Event', '活動')}
          </span>
        </div>
      </div>
    </Link>
  )
}

// ─── Skeleton ─────────────────────────────────────────────────────────────────
function CardSkeleton({ wide = false }) {
  return (
    <div className={`flex bg-white/10 backdrop-blur-md rounded-[24px] overflow-hidden animate-pulse border border-white/10
      ${wide ? 'flex-col sm:flex-row' : 'flex-col'} h-full`}>
      <div className={`bg-slate-200/20 shrink-0 ${wide ? 'sm:w-[45%] h-60 sm:h-auto' : 'aspect-[16/10]'}`} />
      <div className="p-5 flex-1 flex flex-col justify-between">
        <div className="flex justify-between items-center mb-5">
          <div className="h-4 bg-slate-200/20 rounded-full w-24" />
          <div className="h-4 bg-slate-200/20 rounded-full w-20" />
        </div>
        <div className="flex gap-2 mt-auto">
          <div className="h-7 bg-slate-200/20 rounded-full w-20" />
          <div className="h-7 bg-slate-200/20 rounded-full w-16" />
        </div>
      </div>
    </div>
  )
}

// ─── Empty State ──────────────────────────────────────────────────────────────
function EmptyState({ message }) {
  return (
    <div className="col-span-full flex flex-col items-center justify-center py-16">
      <div className="w-16 h-16 rounded-2xl glass flex items-center justify-center mb-4">
        <i className="fas fa-inbox text-2xl text-slate-600" />
      </div>
      <p className="text-sm text-slate-500">{message}</p>
    </div>
  )
}

// ─── Main Component ───────────────────────────────────────────────────────────
export default function NewsSection() {
  const { pick, ui } = useLang()

  const TABS = [
    { key: 'latest',   label: ui.latest,     icon: 'fas fa-newspaper' },
    { key: 'featured', label: ui.featuredTab, icon: 'fas fa-star' },
    { key: 'events',   label: ui.events,      icon: 'fas fa-calendar-alt' },
  ]

  const [tab, setTab]         = useState('latest')
  const [data, setData]       = useState({ latest: [], featured: [], events: [] })
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    Promise.all([
      api.get('/public/news',   { params: { per_page: 6 } }),
      api.get('/public/news',   { params: { per_page: 4, featured: 1 } }),
      api.get('/public/events', { params: { per_page: 6 } }),
    ]).then(([latest, featured, events]) => {
      setData({
        latest:   latest.data?.data   || [],
        featured: featured.data?.data || [],
        events:   events.data?.data   || [],
      })
    }).catch(() => {}).finally(() => setLoading(false))
  }, [])

  const items = data[tab] || []

  return (
    <section className="py-16 relative">
      <div className="max-w-[1400px] mx-auto px-4">

        {/* ── Section header ─────────────────────────────── */}
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
          <div>
            <span className="inline-block text-xs font-bold text-amber-400 uppercase tracking-[0.2em]
              mb-2 glass px-3 py-1 rounded-full">
              {pick('ໜ້າຫຼັກ · ຂ່າວ', 'Latest Updates', '最新動態')}
            </span>
            <h2 className="text-2xl md:text-3xl font-bold text-white">
              {ui.newsAndEvents}
            </h2>
            <div className="h-1 w-16 bg-gradient-to-r from-amber-400 to-amber-600 mt-3 rounded-full" />
          </div>

          <Link to="/news"
            className="inline-flex items-center gap-2 text-sm font-semibold text-amber-400
              hover:text-amber-300 group shrink-0 cursor-pointer">
            {ui.viewAll}
            <span className="w-7 h-7 rounded-xl glass flex items-center justify-center
              group-hover:bg-amber-500/15 transition-colors">
              <i className="fas fa-arrow-right text-[10px] text-amber-400
                group-hover:translate-x-0.5 transition-transform" />
            </span>
          </Link>
        </div>

        {/* ── Tab switcher ───────────────────────────────── */}
        <div className="flex flex-wrap gap-1.5 glass rounded-2xl p-1.5 mb-8 w-fit">
          {TABS.map(t => (
            <button key={t.key} onClick={() => setTab(t.key)}
              className={`flex items-center gap-2 px-4 py-2 text-sm rounded-xl font-medium
                transition-all duration-200 whitespace-nowrap cursor-pointer ${
                tab === t.key
                  ? 'bg-amber-500 text-indigo-950 shadow-sm shadow-amber-500/25'
                  : 'text-slate-400 hover:text-white hover:bg-white/5'
              }`}>
              <i className={`${t.icon} text-[11px] ${
                tab === t.key ? 'text-indigo-800' : 'text-slate-500'
              }`} />
              {t.label}
              {!loading && (
                <span className={`text-[10px] px-1.5 py-0.5 rounded-full font-bold leading-none ${
                  tab === t.key ? 'bg-indigo-900/30 text-indigo-900' : 'bg-white/5 text-slate-500'
                }`}>
                  {data[t.key]?.length ?? 0}
                </span>
              )}
            </button>
          ))}
        </div>

        {/* ── Content ────────────────────────────────────── */}
        {loading ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            {/* First skeleton mimics the wide featured card */}
            <div className="sm:col-span-2 md:col-span-3">
              <CardSkeleton wide />
            </div>
            {Array.from({ length: 5 }).map((_, i) => <CardSkeleton key={i} />)}
          </div>

        ) : tab === 'featured' ? (
          items.length ? (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
              {items[0] && (
                <div className="md:col-span-2">
                  <FeaturedCard item={items[0]} large />
                </div>
              )}
              {items.slice(1).map(item => (
                <FeaturedCard key={item.id} item={item} />
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-1">
              <EmptyState message={ui.noData} />
            </div>
          )

        ) : tab === 'events' ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            {items.length
              ? items.map(item => <EventCard key={item.id} item={item} />)
              : <EmptyState message={ui.noData} />}
          </div>

        ) : (
          /* Latest — first item as horizontal hero card */
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            {items.length ? (
              <>
                {items[0] && (
                  <div className="sm:col-span-2 md:col-span-3">
                    <NewsCard item={items[0]} featured />
                  </div>
                )}
                {items.slice(1).map(item => (
                  <NewsCard key={item.id} item={item} />
                ))}
              </>
            ) : (
              <EmptyState message={ui.noData} />
            )}
          </div>
        )}

      </div>
    </section>
  )
}
