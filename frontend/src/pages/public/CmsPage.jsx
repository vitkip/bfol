import { useState, useEffect } from 'react'
import { Link, useParams } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

export default function CmsPage() {
  const { slug } = useParams()
  const { pick, ui } = useLang()
  const [page, setPage]         = useState(null)
  const [loading, setLoading]   = useState(true)
  const [notFound, setNotFound] = useState(false)

  useEffect(() => {
    setLoading(true)
    setNotFound(false)
    setPage(null)
    api.get(`/public/pages/${slug}`)
      .then(r => setPage(r.data))
      .catch(err => { if (err.response?.status === 404) setNotFound(true) })
      .finally(() => setLoading(false))
  }, [slug])

  if (loading) return (
    <div className="min-h-[60vh] flex items-center justify-center">
      <div className="space-y-4 w-full max-w-4xl mx-auto px-4">
        <div className="h-8 bg-gray-200 rounded animate-pulse w-1/3" />
        <div className="h-4 bg-gray-200 rounded animate-pulse w-full" />
        <div className="h-4 bg-gray-200 rounded animate-pulse w-5/6" />
        <div className="h-4 bg-gray-200 rounded animate-pulse w-4/6" />
      </div>
    </div>
  )

  if (notFound) return (
    <div className="min-h-[60vh] flex flex-col items-center justify-center text-center px-4">
      <i className="fas fa-file-circle-xmark text-6xl text-gray-200 mb-4" />
      <h1 className="text-xl font-bold text-gray-600 mb-2">{ui.notFound}</h1>
      <p className="text-gray-400 text-sm mb-4">"{slug}" — {ui.notPublished}</p>
      <Link to="/" className="text-blue-700 hover:text-blue-900 text-sm font-medium">
        {ui.backHome}
      </Link>
    </div>
  )

  const title   = pick(page.title_lo,   page.title_en,   page.title_zh)
  const content = pick(page.content_lo, page.content_en, page.content_zh)

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="bg-blue-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-3xl font-bold mb-2">{title}</h1>
          <nav className="text-sm mt-4 text-blue-300">
            <Link to="/" className="hover:text-white transition-colors">{ui.home}</Link>
            <span className="mx-2">/</span>
            <span className="text-white">{title}</span>
          </nav>
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 py-12">
        <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
          {page.thumbnail && (
            <img src={page.thumbnail} alt={title} className="w-full max-h-72 object-cover" />
          )}
          <div className="p-8 md:p-12">
            <div className="flex items-center gap-3 mb-8 pb-6 border-b border-gray-100">
              <div className="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i className="fas fa-file-alt text-blue-700" />
              </div>
              <h2 className="text-xl font-bold text-blue-900">{title}</h2>
            </div>
            <div className="rich-content text-gray-700 text-base whitespace-pre-line">
              {content}
            </div>
          </div>
        </div>

        <div className="mt-6 text-center">
          <Link to="/" className="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900 transition-colors">
            <i className="fas fa-arrow-left text-xs" /> {ui.home}
          </Link>
        </div>
      </div>
    </div>
  )
}
