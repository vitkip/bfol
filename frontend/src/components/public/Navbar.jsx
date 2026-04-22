import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { useSiteSettings } from '../../context/SiteSettingsContext'
import { useLang } from '../../context/LanguageContext'
import api from '../../api/client'

const FALLBACK = [
  { id: 'home', label_lo: 'ໜ້າຫຼັກ', label_en: 'Home', label_zh: '首頁', url: '/', items: [] },
  { id: 'about', label_lo: 'ກ່ຽວກັບ ອງສ', label_en: 'About BFOL', label_zh: '關於我們', url: null, items: [
    { id: 'a1', label_lo: 'ປະຫວັດຄວາມເປັນມາ',    label_en: 'History',    label_zh: '歷史',   url: '/about/history' },
    { id: 'a2', label_lo: 'ວິສາຫະກິດ & ຄາລະກິດ', label_en: 'Mission',    label_zh: '使命',   url: '/about/mission' },
    { id: 'a3', label_lo: 'ໂຄງສ້າງອົງການ',        label_en: 'Structure',  label_zh: '組織結構', url: '/about/structure' },
    { id: 'a4', label_lo: 'ຄະນະກຳມະການ',          label_en: 'Committee',  label_zh: '委員會', url: '/about/committee' },
  ]},
  { id: 'edu', label_lo: 'ດ້ານການສຶກສາ', label_en: 'Education', label_zh: '教育', url: null, items: [
    { id: 'e1', label_lo: 'ຮຽນ ສີລ ສະມາທິ ທັມ', label_en: 'Sila & Dhamma', label_zh: '戒定慧', url: '/dhamma/sila' },
    { id: 'e2', label_lo: 'ດ້ານການສອນ',           label_en: 'Teaching',      label_zh: '教學',   url: '/dhamma/teach' },
    { id: 'e3', label_lo: 'ທັດທະ & ວິໄຊ',         label_en: 'Research',      label_zh: '研究',   url: '/dhamma/research' },
    { id: 'e4', label_lo: 'ສາສາສາ & ສັງຄົມ',      label_en: 'Society',       label_zh: '社會',   url: '/dhamma/social' },
  ]},
  { id: 'foreign', label_lo: 'ການຕ່າງປະເທດ', label_en: 'International', label_zh: '國際關係', url: null, items: [
    { id: 'f1', label_lo: 'ການທູດສາສາສາ',      label_en: 'Diplomacy',   label_zh: '宗教外交', url: '/foreign/diplomacy' },
    { id: 'f2', label_lo: 'ແລກປ່ຽນ ສາກົນ',    label_en: 'Exchange',    label_zh: '國際交流', url: '/foreign/exchange' },
    { id: 'f3', label_lo: 'MOU ກັບຕ່າງປະເທດ', label_en: 'MOU',         label_zh: 'MOU協議', url: '/foreign/mou' },
    { id: 'f4', label_lo: 'ໂຄງການ ຊ່ວຍເຫຼືອ', label_en: 'Aid Projects', label_zh: '援助項目', url: '/foreign/aid' },
    { id: 'f5', label_lo: 'ສຶກສາ ສາກົນ',       label_en: 'Int\'l Study', label_zh: '國際教育', url: '/foreign/education' },
  ]},
  { id: 'media', label_lo: 'ສື່ສາ', label_en: 'Media', label_zh: '媒體', url: null, items: [
    { id: 'm1', label_lo: 'DhammaOnLen',     label_en: 'DhammaOnLen',    label_zh: 'DhammaOnLen', url: '/media/dhamma-on-len' },
    { id: 'm2', label_lo: 'ການສອນ Online',   label_en: 'Online Teaching', label_zh: '線上教學',   url: '/media/online' },
    { id: 'm3', label_lo: 'ຮູບພາບ ກິດຈະກຳ', label_en: 'Gallery',         label_zh: '活動相冊',   url: '/media/gallery' },
    { id: 'm4', label_lo: 'ວິດີໂອ',          label_en: 'Video',           label_zh: '視頻',       url: '/media/video' },
    { id: 'm5', label_lo: 'ເອກະສານ',         label_en: 'Documents',       label_zh: '文件',       url: '/media/documents' },
  ]},
  { id: 'news',    label_lo: 'ຂ່າວສານ', label_en: 'News',    label_zh: '新聞',   url: '/news',    items: [] },
  { id: 'contact', label_lo: 'ຕິດຕໍ່',  label_en: 'Contact', label_zh: '聯繫我們', url: '/contact', items: [] },
]

export default function Navbar() {
  const [open, setOpen]         = useState(false)
  const [scrolled, setScrolled] = useState(false)
  const [openDrop, setOpenDrop] = useState(null)
  const [navItems, setNavItems] = useState(FALLBACK)
  const s = useSiteSettings()
  const { pick } = useLang()

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 50)
    window.addEventListener('scroll', onScroll)
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  useEffect(() => {
    api.get('/public/menu')
      .then(r => { if (r.data?.length) setNavItems(r.data) })
      .catch(() => {})
  }, [])

  const label = (item) => pick(item.label_lo, item.label_en, item.label_zh)

  const renderLink = (item) => {
    if (!item.url) return null
    const isExternal = item.url.startsWith('http')
    const cls = 'px-3 py-2 text-sm text-gray-700 hover:text-blue-900 font-medium rounded transition-colors'
    if (isExternal) {
      return <a href={item.url} target={item.target || '_blank'} rel="noreferrer" className={cls}>{label(item)}</a>
    }
    return <Link to={item.url} className={cls}>{label(item)}</Link>
  }

  const renderMobileLink = (item) => {
    if (!item.url) return null
    const isExternal = item.url.startsWith('http')
    const cls = 'block px-4 py-3 text-sm font-medium text-gray-700 hover:text-blue-900'
    if (isExternal) {
      return (
        <a href={item.url} target={item.target || '_blank'} rel="noreferrer"
          onClick={() => setOpen(false)} className={cls}>{label(item)}</a>
      )
    }
    return (
      <Link to={item.url} onClick={() => setOpen(false)} className={cls}>{label(item)}</Link>
    )
  }

  return (
    <header className={`sticky top-0 z-50 transition-all duration-300 ${
      scrolled ? 'bg-white shadow-md' : 'bg-white/95 shadow-sm'
    }`}>
      <div className="max-w-7xl mx-auto px-4">
        <div className="flex items-center justify-between h-16">

          {/* Logo */}
          <Link to="/" className="flex items-center gap-3">
            {s.logo_url ? (
              <img src={s.logo_url} alt={s.site_name_lo}
                className="w-10 h-10 rounded-full object-contain bg-white p-0.5 border border-gray-200" />
            ) : (
              <div className="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center text-yellow-400 text-lg">
                <i className="fas fa-dharmachakra" />
              </div>
            )}
            <div className="leading-tight">
              <div className="font-bold text-blue-900 text-sm">
                {pick(s.site_name_lo, s.site_name_en, s.site_name_zh) || 'ອົງສ · BFOL'}
              </div>
              <div className="text-xs text-gray-500">{s.site_name_en || 'BFOL'}</div>
            </div>
          </Link>

          {/* Desktop nav */}
          <nav className="hidden lg:flex items-center gap-1">
            {navItems.map((item) =>
              item.items?.length > 0 ? (
                <div key={item.id} className="relative group">
                  <button className="flex items-center gap-1 px-3 py-2 text-sm text-gray-700 hover:text-blue-900 font-medium rounded transition-colors">
                    {item.icon && <i className={`${item.icon} text-xs opacity-70 mr-0.5`} />}
                    {label(item)}
                    <i className="fas fa-chevron-down text-[10px] opacity-60" />
                  </button>
                  <div className="absolute top-full left-0 min-w-[200px] bg-white shadow-xl rounded-b-xl border-t-2 border-blue-900 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    {item.items.map((sub) => {
                      const isExt = sub.url?.startsWith('http')
                      const subCls = 'block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-900 first:rounded-none last:rounded-b-xl transition-colors'
                      return isExt ? (
                        <a key={sub.id} href={sub.url} target={sub.target || '_blank'} rel="noreferrer" className={subCls}>
                          {sub.icon && <i className={`${sub.icon} text-xs mr-1.5 opacity-60`} />}
                          {label(sub)}
                        </a>
                      ) : (
                        <Link key={sub.id} to={sub.url || '#'} className={subCls}>
                          {sub.icon && <i className={`${sub.icon} text-xs mr-1.5 opacity-60`} />}
                          {label(sub)}
                        </Link>
                      )
                    })}
                  </div>
                </div>
              ) : item.url ? renderLink(item) : null
            )}

            {s.site_facebook && (
              <a href={s.site_facebook} target="_blank" rel="noreferrer"
                className="ml-2 flex items-center gap-1.5 px-3 py-2 bg-blue-800 text-white text-sm rounded-lg hover:bg-blue-900 transition-colors">
                <i className="fab fa-facebook-f text-xs" /> DhammaOnLen
              </a>
            )}
          </nav>

          {/* Mobile hamburger */}
          <button onClick={() => setOpen(!open)} className="lg:hidden flex flex-col gap-1.5 p-2">
            <span className={`block h-0.5 w-6 bg-gray-700 transition-all ${open ? 'rotate-45 translate-y-2' : ''}`} />
            <span className={`block h-0.5 w-6 bg-gray-700 transition-all ${open ? 'opacity-0' : ''}`} />
            <span className={`block h-0.5 w-6 bg-gray-700 transition-all ${open ? '-rotate-45 -translate-y-2' : ''}`} />
          </button>
        </div>

        {/* Mobile menu */}
        {open && (
          <div className="lg:hidden border-t pb-4">
            {navItems.map((item) =>
              item.items?.length > 0 ? (
                <div key={item.id}>
                  <button onClick={() => setOpenDrop(openDrop === item.id ? null : item.id)}
                    className="w-full flex justify-between items-center px-4 py-3 text-sm font-medium text-gray-700">
                    <span className="flex items-center gap-2">
                      {item.icon && <i className={`${item.icon} text-xs text-blue-600`} />}
                      {label(item)}
                    </span>
                    <i className={`fas fa-chevron-${openDrop === item.id ? 'up' : 'down'} text-xs`} />
                  </button>
                  {openDrop === item.id && (
                    <div className="bg-gray-50 pl-4">
                      {item.items.map((sub) => {
                        const isExt = sub.url?.startsWith('http')
                        return isExt ? (
                          <a key={sub.id} href={sub.url} target={sub.target || '_blank'} rel="noreferrer"
                            onClick={() => setOpen(false)}
                            className="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900">
                            {label(sub)}
                          </a>
                        ) : (
                          <Link key={sub.id} to={sub.url || '#'} onClick={() => setOpen(false)}
                            className="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900">
                            {label(sub)}
                          </Link>
                        )
                      })}
                    </div>
                  )}
                </div>
              ) : item.url ? renderMobileLink(item) : null
            )}
          </div>
        )}
      </div>
    </header>
  )
}
