import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

function NewsCard({ item }) {
  const { pick, ui } = useLang()
  const title   = pick(item.title_lo, item.title_en, item.title_zh)
  const excerpt = pick(item.excerpt_lo, item.excerpt_en, item.excerpt_zh)

  return (
    <div className="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
      <div className="relative h-44 overflow-hidden bg-gray-100">
        {item.thumbnail ? (
          <img src={item.thumbnail} alt={title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
        ) : (
          <div className="w-full h-full flex items-center justify-center bg-blue-50">
            <i className="fas fa-newspaper text-4xl text-blue-200" />
          </div>
        )}
        {item.is_urgent && (
          <span className="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded">
            {ui.urgent}
          </span>
        )}
        {item.category && (
          <span className="absolute top-2 right-2 bg-blue-900/80 text-white text-[10px] px-2 py-0.5 rounded">
            {item.category}
          </span>
        )}
      </div>
      <div className="p-4">
        <h3 className="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 group-hover:text-blue-900 transition-colors mb-2">
          {title}
        </h3>
        {excerpt && <p className="text-xs text-gray-500 line-clamp-2 mb-3">{excerpt}</p>}
        <div className="flex items-center justify-between">
          <span className="text-xs text-gray-400 flex items-center gap-1">
            <i className="fas fa-calendar-alt" /> {item.published_at}
          </span>
          <Link to={`/news/${item.slug}`} className="text-xs text-blue-700 hover:text-blue-900 font-medium">
            {ui.readMore}
          </Link>
        </div>
      </div>
    </div>
  )
}

function FeaturedCard({ item }) {
  const { pick, ui } = useLang()
  const title = pick(item.title_lo, item.title_en, item.title_zh)

  return (
    <div className="relative rounded-xl overflow-hidden h-64 group">
      {item.thumbnail ? (
        <img src={item.thumbnail} alt={title}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
      ) : (
        <div className="w-full h-full bg-gradient-to-br from-blue-800 to-blue-950" />
      )}
      <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent" />
      <div className="absolute bottom-0 left-0 right-0 p-4">
        {item.category && (
          <span className="text-xs bg-yellow-400 text-blue-900 font-bold px-2 py-0.5 rounded mb-2 inline-block">
            {item.category}
          </span>
        )}
        <h3 className="text-white font-bold text-sm leading-snug line-clamp-2 mb-1">{title}</h3>
        <div className="flex items-center justify-between">
          <span className="text-blue-300 text-xs">{item.published_at}</span>
          <Link to={`/news/${item.slug}`} className="text-yellow-400 text-xs hover:text-yellow-300">
            {ui.readMore}
          </Link>
        </div>
      </div>
    </div>
  )
}

function EventCard({ item }) {
  const { pick } = useLang()
  const title    = pick(item.title_lo, item.title_en, item.title_zh)
  const location = pick(item.location_lo, item.location_en, item.location_lo)

  return (
    <div className="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
      <div className="relative h-44 overflow-hidden bg-gray-100">
        {item.thumbnail ? (
          <img src={item.thumbnail} alt={title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
        ) : (
          <div className="w-full h-full flex items-center justify-center bg-blue-50">
            <i className="fas fa-calendar-alt text-4xl text-blue-200" />
          </div>
        )}
      </div>
      <div className="p-4">
        <h3 className="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 group-hover:text-blue-900 mb-2">
          {title}
        </h3>
        {location && <p className="text-xs text-gray-500 mb-1 flex items-center gap-1"><i className="fas fa-map-marker-alt" />{location}</p>}
        <p className="text-xs text-gray-400 flex items-center gap-1">
          <i className="fas fa-calendar-alt" /> {item.start_date || item.published_at}
        </p>
      </div>
    </div>
  )
}

export default function NewsSection() {
  const { ui } = useLang()
  const TABS = [
    { key: 'latest',   label: ui.latest },
    { key: 'featured', label: ui.featuredTab },
    { key: 'events',   label: ui.events },
  ]

  const [tab, setTab] = useState('latest')
  const [data, setData] = useState({ latest: [], featured: [], events: [] })
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    Promise.all([
      api.get('/public/news', { params: { per_page: 6 } }),
      api.get('/public/news', { params: { per_page: 4, featured: 1 } }),
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
    <section className="py-14 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
          <div>
            <h2 className="text-2xl font-bold text-blue-900">{ui.newsAndEvents}</h2>
            <div className="h-1 w-16 bg-yellow-400 mt-2 rounded" />
          </div>
          <Link to="/news" className="text-sm text-blue-700 hover:text-blue-900 font-medium flex items-center gap-1">
            {ui.viewAll} <i className="fas fa-arrow-right text-xs" />
          </Link>
        </div>

        <div className="flex gap-1 bg-white rounded-lg p-1 shadow-sm mb-8 w-fit">
          {TABS.map(t => (
            <button key={t.key} onClick={() => setTab(t.key)}
              className={`px-4 py-2 text-sm rounded-md font-medium transition-colors ${
                tab === t.key ? 'bg-blue-900 text-white' : 'text-gray-600 hover:text-blue-900'
              }`}>
              {t.label}
            </button>
          ))}
        </div>

        {loading ? (
          <div className="grid grid-cols-2 md:grid-cols-3 gap-5">
            {Array.from({ length: 6 }).map((_, i) => (
              <div key={i} className="bg-white rounded-xl h-64 animate-pulse">
                <div className="h-44 bg-gray-200 rounded-t-xl" />
                <div className="p-4 space-y-2">
                  <div className="h-3 bg-gray-200 rounded w-3/4" />
                  <div className="h-3 bg-gray-200 rounded w-1/2" />
                </div>
              </div>
            ))}
          </div>
        ) : tab === 'featured' ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {items.length ? items.map(item => <FeaturedCard key={item.id} item={item} />) : (
              <p className="col-span-2 text-center py-12 text-gray-400">{ui.noData}</p>
            )}
          </div>
        ) : tab === 'events' ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            {items.length ? items.map(item => <EventCard key={item.id} item={item} />) : (
              <p className="col-span-3 text-center py-12 text-gray-400">{ui.noData}</p>
            )}
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            {items.length ? items.map(item => <NewsCard key={item.id} item={item} />) : (
              <div className="col-span-3 text-center py-12 text-gray-400">
                <i className="fas fa-inbox text-4xl mb-3 block" />
                {ui.noData}
              </div>
            )}
          </div>
        )}
      </div>
    </section>
  )
}
