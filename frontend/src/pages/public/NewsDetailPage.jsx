import { useState, useEffect } from 'react'
import { Link, useParams } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

export default function NewsDetailPage() {
  const { slug } = useParams()
  const { pick, ui } = useLang()
  const [news, setNews]         = useState(null)
  const [loading, setLoading]   = useState(true)
  const [notFound, setNotFound] = useState(false)

  useEffect(() => {
    setLoading(true)
    setNotFound(false)
    setNews(null)
    api.get(`/public/news/${slug}`)
      .then(r => setNews(r.data))
      .catch(err => { if (err.response?.status === 404) setNotFound(true) })
      .finally(() => setLoading(false))
  }, [slug])

  if (loading) return (
    <div className="min-h-screen bg-gray-50">
      <div className="bg-blue-900 h-48 animate-pulse" />
      <div className="max-w-4xl mx-auto px-4 py-10 space-y-4">
        <div className="h-6 bg-gray-200 rounded animate-pulse w-2/3" />
        <div className="h-4 bg-gray-200 rounded animate-pulse w-full" />
        <div className="h-4 bg-gray-200 rounded animate-pulse w-5/6" />
        <div className="h-4 bg-gray-200 rounded animate-pulse w-4/6" />
      </div>
    </div>
  )

  if (notFound) return (
    <div className="min-h-[60vh] flex flex-col items-center justify-center text-center px-4">
      <i className="fas fa-newspaper text-6xl text-gray-200 mb-4" />
      <h1 className="text-xl font-bold text-gray-600 mb-2">{ui.pageNotFound}</h1>
      <p className="text-gray-400 text-sm mb-4">"{slug}" — {ui.notPublished}</p>
      <Link to="/news" className="text-blue-700 hover:text-blue-900 text-sm font-medium">
        {ui.backToNews}
      </Link>
    </div>
  )

  const title   = pick(news.title_lo,   news.title_en,   news.title_zh)
  const content = pick(news.content_lo, news.content_en, news.content_zh)
  const excerpt = pick(news.excerpt_lo, news.excerpt_en, news.excerpt_zh)

  return (
    <div className="min-h-screen bg-gray-50">

      {/* Header */}
      <div className="bg-blue-900 text-white py-12">
        <div className="max-w-4xl mx-auto px-4">
          <div className="flex flex-wrap gap-2 mb-4">
            {news.is_urgent && (
              <span className="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                <i className="fas fa-bolt mr-1" /> {ui.urgent}
              </span>
            )}
            {news.is_featured && (
              <span className="bg-yellow-400 text-blue-900 text-xs font-bold px-3 py-1 rounded-full">
                <i className="fas fa-star mr-1" /> {ui.featured}
              </span>
            )}
            {news.category && (
              <span className="bg-blue-700 text-white text-xs px-3 py-1 rounded-full">
                {news.category}
              </span>
            )}
          </div>

          <h1 className="text-2xl md:text-3xl font-bold leading-snug mb-4">{title}</h1>

          <div className="flex flex-wrap items-center gap-4 text-blue-300 text-sm">
            <span className="flex items-center gap-1.5">
              <i className="fas fa-calendar-alt text-xs" /> {news.published_at}
            </span>
            {news.view_count > 0 && (
              <span className="flex items-center gap-1.5">
                <i className="fas fa-eye text-xs" /> {news.view_count.toLocaleString()} {ui.views}
              </span>
            )}
          </div>

          <nav className="text-sm mt-5 text-blue-300">
            <Link to="/" className="hover:text-white transition-colors">{ui.home}</Link>
            <span className="mx-2">/</span>
            <Link to="/news" className="hover:text-white transition-colors">{ui.news}</Link>
            <span className="mx-2">/</span>
            <span className="text-white line-clamp-1">{title}</span>
          </nav>
        </div>
      </div>

      {/* Body */}
      <div className="max-w-4xl mx-auto px-4 py-10">
        <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
          {news.thumbnail && (
            <img src={news.thumbnail} alt={title} className="w-full max-h-80 object-cover" />
          )}
          <div className="p-6 md:p-10">
            {excerpt && (
              <p className="text-gray-500 text-base leading-relaxed border-l-4 border-blue-200 pl-4 mb-8 italic">
                {excerpt}
              </p>
            )}
            {content ? (
              <div className="rich-content text-gray-700 text-base"
                dangerouslySetInnerHTML={{ __html: content }} />
            ) : (
              <p className="text-gray-400 italic text-center py-8">{ui.noContent}</p>
            )}
          </div>
        </div>

        <div className="mt-8 flex justify-between items-center">
          <Link to="/news"
            className="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900 font-medium transition-colors">
            <i className="fas fa-arrow-left text-xs" /> {ui.backToNews}
          </Link>
          <Link to="/"
            className="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <i className="fas fa-home text-xs" /> {ui.home}
          </Link>
        </div>
      </div>
    </div>
  )
}
