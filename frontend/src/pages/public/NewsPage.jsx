import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

function NewsCard({ item }) {
  const { pick, ui } = useLang()
  const title   = pick(item.title_lo, item.title_en, item.title_zh)
  const excerpt = pick(item.excerpt_lo, item.excerpt_en, item.excerpt_zh)

  return (
    <div className="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col">
      <div className="relative h-48 overflow-hidden bg-gray-100">
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
      <div className="p-4 flex flex-col flex-1">
        <h3 className="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 group-hover:text-blue-900 transition-colors mb-2">
          {title}
        </h3>
        {excerpt && <p className="text-xs text-gray-500 line-clamp-2 mb-3 flex-1">{excerpt}</p>}
        <div className="flex items-center justify-between mt-auto pt-3 border-t border-gray-100">
          <span className="text-xs text-gray-400"><i className="fas fa-calendar-alt mr-1" />{item.published_at}</span>
          <Link to={`/news/${item.slug}`} className="text-xs text-blue-700 hover:text-blue-900 font-medium">
            {ui.readMore}
          </Link>
        </div>
      </div>
    </div>
  )
}

export default function NewsPage() {
  const { ui } = useLang()
  const [items, setItems]   = useState([])
  const [meta, setMeta]     = useState(null)
  const [page, setPage]     = useState(1)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    setLoading(true)
    api.get('/public/news', { params: { page, per_page: 9 } })
      .then(r => { setItems(r.data?.data || []); setMeta(r.data?.meta) })
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [page])

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="bg-blue-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-3xl font-bold mb-2">{ui.news}</h1>
          <nav className="text-sm mt-4 text-blue-300">
            <Link to="/" className="hover:text-white">{ui.home}</Link>
            <span className="mx-2">/</span>
            <span className="text-white">{ui.news}</span>
          </nav>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 py-12">
        {loading ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            {Array.from({ length: 9 }).map((_, i) => (
              <div key={i} className="bg-white rounded-xl animate-pulse">
                <div className="h-48 bg-gray-200 rounded-t-xl" />
                <div className="p-4 space-y-2">
                  <div className="h-3 bg-gray-200 rounded w-3/4" />
                  <div className="h-3 bg-gray-200 rounded w-1/2" />
                </div>
              </div>
            ))}
          </div>
        ) : (
          <>
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
              {items.map(item => <NewsCard key={item.id} item={item} />)}
            </div>

            {meta && meta.last_page > 1 && (
              <div className="flex justify-center gap-2 mt-10">
                <button onClick={() => setPage(p => Math.max(1, p - 1))}
                  disabled={page === 1}
                  className="px-4 py-2 rounded-lg border text-sm disabled:opacity-40 hover:bg-blue-50 transition-colors">
                  {ui.prev}
                </button>
                {Array.from({ length: meta.last_page }, (_, i) => i + 1).map(p => (
                  <button key={p} onClick={() => setPage(p)}
                    className={`w-9 h-9 rounded-lg text-sm ${page === p ? 'bg-blue-900 text-white' : 'border hover:bg-blue-50'} transition-colors`}>
                    {p}
                  </button>
                ))}
                <button onClick={() => setPage(p => Math.min(meta.last_page, p + 1))}
                  disabled={page === meta.last_page}
                  className="px-4 py-2 rounded-lg border text-sm disabled:opacity-40 hover:bg-blue-50 transition-colors">
                  {ui.next}
                </button>
              </div>
            )}
          </>
        )}
      </div>
    </div>
  )
}
