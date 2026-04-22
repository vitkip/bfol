import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

const TYPE_ICONS = {
  PDF:   { icon: 'fa-file-pdf',        color: 'text-red-500',    bg: 'bg-red-50' },
  Word:  { icon: 'fa-file-word',       color: 'text-blue-500',   bg: 'bg-blue-50' },
  Excel: { icon: 'fa-file-excel',      color: 'text-green-500',  bg: 'bg-green-50' },
  PPT:   { icon: 'fa-file-powerpoint', color: 'text-orange-500', bg: 'bg-orange-50' },
  ZIP:   { icon: 'fa-file-archive',    color: 'text-yellow-600', bg: 'bg-yellow-50' },
  RAR:   { icon: 'fa-file-archive',    color: 'text-yellow-600', bg: 'bg-yellow-50' },
  Text:  { icon: 'fa-file-alt',        color: 'text-gray-500',   bg: 'bg-gray-50' },
}

function fileIcon(type) {
  return TYPE_ICONS[type] || { icon: 'fa-file', color: 'text-gray-400', bg: 'bg-gray-50' }
}

function formatSize(kb) {
  if (!kb) return ''
  if (kb < 1024) return `${kb} KB`
  return `${(kb / 1024).toFixed(1)} MB`
}

function DocRow({ item }) {
  const { pick, ui } = useLang()
  const title = pick(item.title_lo, item.title_en, item.title_zh)
  const desc  = pick(item.description_lo, item.description_en, item.description_zh)
  const { icon, color, bg } = fileIcon(item.file_type)

  return (
    <div className="flex items-center gap-4 bg-white rounded-xl px-5 py-4 shadow-sm hover:shadow-md transition-shadow group">
      <div className={`flex-shrink-0 w-12 h-12 rounded-lg ${bg} flex items-center justify-center`}>
        <i className={`fas ${icon} text-xl ${color}`} />
      </div>

      <div className="flex-1 min-w-0">
        <p className="font-semibold text-gray-800 text-sm leading-snug truncate group-hover:text-blue-900 transition-colors">
          {title}
        </p>
        {desc && <p className="text-xs text-gray-500 mt-0.5 line-clamp-1">{desc}</p>}
        <div className="flex items-center gap-3 mt-1 text-[11px] text-gray-400">
          {item.file_type && (
            <span className={`font-medium ${color}`}>{item.file_type}</span>
          )}
          {item.file_size_kb > 0 && <span>{formatSize(item.file_size_kb)}</span>}
          {item.category && <span>{pick(item.category.name_lo, item.category.name_en)}</span>}
          <span><i className="fas fa-calendar-alt mr-1" />{item.published_at}</span>
          <span><i className="fas fa-download mr-1" />{item.download_count} {ui.downloads}</span>
        </div>
      </div>

      <a
        href={item.file_url}
        target="_blank"
        rel="noopener noreferrer"
        className="flex-shrink-0 flex items-center gap-1.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-medium px-4 py-2 rounded-lg transition-colors"
      >
        <i className="fas fa-download text-[11px]" />
        {ui.download}
      </a>
    </div>
  )
}

const FILE_TYPES = ['PDF', 'Word', 'Excel', 'PPT', 'ZIP', 'RAR', 'Text']

export default function DocumentsPage() {
  const { ui } = useLang()

  const [items, setItems]     = useState([])
  const [meta, setMeta]       = useState(null)
  const [page, setPage]       = useState(1)
  const [loading, setLoading] = useState(true)
  const [search, setSearch]   = useState('')
  const [fileType, setFileType] = useState('')
  const [searchInput, setSearchInput] = useState('')

  useEffect(() => {
    setLoading(true)
    const params = { page, per_page: 15 }
    if (fileType) params.file_type = fileType
    if (search)   params.search    = search

    api.get('/public/documents', { params })
      .then(r => { setItems(r.data?.data || []); setMeta(r.data?.meta) })
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [page, fileType, search])

  function handleSearch(e) {
    e.preventDefault()
    setPage(1)
    setSearch(searchInput)
  }

  function handleTypeFilter(t) {
    setFileType(t)
    setPage(1)
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero */}
      <div className="bg-blue-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-3xl font-bold mb-2">{ui.documents}</h1>
          <nav className="text-sm mt-4 text-blue-300">
            <Link to="/" className="hover:text-white">{ui.home}</Link>
            <span className="mx-2">/</span>
            <span className="text-white">{ui.documents}</span>
          </nav>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 py-10">
        {/* Filters */}
        <div className="flex flex-col sm:flex-row gap-3 mb-8">
          <form onSubmit={handleSearch} className="flex gap-2 flex-1">
            <input
              type="text"
              value={searchInput}
              onChange={e => setSearchInput(e.target.value)}
              placeholder={ui.searchDocs}
              className="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900/30"
            />
            <button type="submit"
              className="bg-blue-900 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition-colors">
              <i className="fas fa-search" />
            </button>
          </form>

          <div className="flex gap-2 flex-wrap">
            <button
              onClick={() => handleTypeFilter('')}
              className={`px-3 py-2 rounded-lg text-xs font-medium border transition-colors ${!fileType ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'}`}>
              {ui.allTypes}
            </button>
            {FILE_TYPES.map(t => (
              <button key={t}
                onClick={() => handleTypeFilter(t)}
                className={`px-3 py-2 rounded-lg text-xs font-medium border transition-colors ${fileType === t ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'}`}>
                {t}
              </button>
            ))}
          </div>
        </div>

        {/* List */}
        {loading ? (
          <div className="space-y-3">
            {Array.from({ length: 8 }).map((_, i) => (
              <div key={i} className="flex items-center gap-4 bg-white rounded-xl px-5 py-4 animate-pulse">
                <div className="w-12 h-12 rounded-lg bg-gray-200" />
                <div className="flex-1 space-y-2">
                  <div className="h-3 bg-gray-200 rounded w-2/3" />
                  <div className="h-2 bg-gray-200 rounded w-1/3" />
                </div>
                <div className="w-24 h-8 bg-gray-200 rounded-lg" />
              </div>
            ))}
          </div>
        ) : items.length === 0 ? (
          <div className="text-center py-20 text-gray-400">
            <i className="fas fa-folder-open text-5xl mb-4 block" />
            <p>{ui.noData}</p>
          </div>
        ) : (
          <div className="space-y-3">
            {items.map(item => <DocRow key={item.id} item={item} />)}
          </div>
        )}

        {/* Pagination */}
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
      </div>
    </div>
  )
}
