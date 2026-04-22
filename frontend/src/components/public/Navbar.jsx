import { useState, useEffect } from 'react'
import { Link, useLocation } from 'react-router-dom'
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
  const location = useLocation()

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 20)
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  useEffect(() => { setOpen(false) }, [location.pathname])

  useEffect(() => {
    api.get('/public/menu')
      .then(r => { if (r.data?.length) setNavItems(r.data) })
      .catch(() => {})
  }, [])

  const label = (item) => pick(item.label_lo, item.label_en, item.label_zh)

  const renderLink = (item) => {
    if (!item.url) return null
    const isExternal = item.url.startsWith('http')
    const isActive = location.pathname === item.url
    const cls = `relative px-3 py-2 text-[13px] font-semibold rounded-xl transition-all duration-300 cursor-pointer group
      ${isActive ? 'text-amber-400' : 'text-slate-300 hover:text-white'}`

    const content = (
      <>
        {label(item)}
        <span className={`absolute left-1 right-1 bottom-0 h-[2px] bg-amber-400 rounded-full transition-all duration-300
          ${isActive ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100'}`} />
      </>
    )

    if (isExternal) {
      return <a href={item.url} target={item.target || '_blank'} rel="noreferrer" className={cls}>{content}</a>
    }
    return <Link to={item.url} className={cls}>{content}</Link>
  }

  const renderMobileLink = (item) => {
    if (!item.url) return null
    const isExternal = item.url.startsWith('http')
    const isActive = location.pathname === item.url
    const cls = `block px-4 py-3 text-sm font-medium rounded-xl transition-all cursor-pointer
      ${isActive ? 'bg-amber-500/10 text-amber-400' : 'text-slate-300 hover:bg-white/5 hover:text-white'}`
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
    <header className={`transition-all duration-500 ease-out rounded-2xl ${
      scrolled
        ? 'bg-indigo-950/70 backdrop-blur-2xl shadow-[0_8px_40px_rgba(0,0,0,0.3)] border border-white/10'
        : 'bg-indigo-950/40 backdrop-blur-xl shadow-lg shadow-indigo-950/50 border border-white/5'
    }`}>
      <div className="px-4 lg:px-6">
        <div className="flex items-center justify-between h-14 lg:h-16">

          {/* Logo */}
          <Link to="/" className="flex items-center gap-3 min-w-0 group cursor-pointer">
            <div className="relative">
              {s.logo_url ? (
                <img src={s.logo_url} alt={s.site_name_lo}
                  className="w-10 h-10 rounded-xl object-contain bg-white/10 p-0.5 border border-white/10 shrink-0
                    group-hover:border-amber-400/30 transition-all" />
              ) : (
                <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-400 flex items-center justify-center text-indigo-950 text-lg shrink-0
                  shadow-md shadow-amber-500/30 group-hover:shadow-amber-500/50 transition-shadow">
                  <i className="fas fa-dharmachakra" />
                </div>
              )}
            </div>
            <div className="leading-tight min-w-0 max-w-[200px] xl:max-w-[280px]">
              <div className="font-bold text-white text-[14px] truncate tracking-tight group-hover:text-amber-300 transition-colors">
                {pick(s.site_name_lo, s.site_name_en, s.site_name_zh) || 'ອົງສ · BFOL'}
              </div>
              <div className="text-[11px] font-medium text-slate-400 truncate">{s.site_name_en || 'BFOL'}</div>
            </div>
          </Link>

          {/* Desktop nav */}
          <nav className="hidden lg:flex items-center gap-1 xl:gap-2">
            {navItems.map((item) =>
              item.items?.length > 0 ? (
                <div key={item.id} className="relative group">
                  <button className="flex items-center gap-1.5 px-3 py-2 text-[13px] font-semibold text-slate-300
                    hover:text-white rounded-xl transition-all duration-300 cursor-pointer">
                    {item.icon && <i className={`${item.icon} text-xs opacity-70`} />}
                    {label(item)}
                    <i className="fas fa-chevron-down text-[10px] opacity-50 group-hover:-rotate-180 transition-transform duration-300" />
                  </button>

                  {/* Floating Dropdown — Glassmorphism */}
                  <div className="absolute top-[calc(100%+0.5rem)] left-1/2 -translate-x-1/2 min-w-[230px]
                    glass rounded-2xl p-2
                    opacity-0 invisible scale-95 origin-top
                    group-hover:opacity-100 group-hover:visible group-hover:scale-100
                    transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] z-50">
                    {item.items.map((sub) => {
                      const isExt = sub.url?.startsWith('http')
                      const subCls = `flex items-center px-4 py-2.5 text-[13px] font-medium text-slate-300
                        hover:text-amber-400 hover:bg-white/5 rounded-xl transition-all duration-200 cursor-pointer`
                      return isExt ? (
                        <a key={sub.id} href={sub.url} target={sub.target || '_blank'} rel="noreferrer" className={subCls}>
                          {sub.icon && <i className={`${sub.icon} text-[11px] mr-2 text-slate-500`} />}
                          {label(sub)}
                        </a>
                      ) : (
                        <Link key={sub.id} to={sub.url || '#'} className={subCls}>
                          {sub.icon && <i className={`${sub.icon} text-[11px] mr-2 text-slate-500`} />}
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
                className="ml-4 flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-amber-500 to-amber-400
                  text-indigo-950 text-[13px] font-bold rounded-xl cursor-pointer
                  shadow-md shadow-amber-500/25 hover:shadow-lg hover:shadow-amber-500/40
                  hover:-translate-y-0.5 transition-all duration-300">
                <i className="fab fa-facebook-f text-[11px]" /> DhammaOnLen
              </a>
            )}
          </nav>

          {/* Mobile hamburger */}
          <button onClick={() => setOpen(!open)}
            className="lg:hidden flex flex-col gap-[5px] p-2 bg-white/5 hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
            <span className={`block h-[2px] w-5 bg-slate-300 rounded-full transition-all duration-300 ${open ? 'rotate-45 translate-y-[7px]' : ''}`} />
            <span className={`block h-[2px] w-5 bg-slate-300 rounded-full transition-all duration-300 ${open ? 'opacity-0' : ''}`} />
            <span className={`block h-[2px] w-5 bg-slate-300 rounded-full transition-all duration-300 ${open ? '-rotate-45 -translate-y-[7px]' : ''}`} />
          </button>
        </div>

        {/* Mobile menu dropdown */}
        <div className={`lg:hidden overflow-hidden transition-all duration-500 ease-out
          ${open ? 'max-h-[80vh] opacity-100 mt-2 pb-4' : 'max-h-0 opacity-0 mt-0'}`}>
          <div className="glass rounded-2xl p-2 flex flex-col gap-1 max-h-[75vh] overflow-y-auto">
            {navItems.map((item) =>
              item.items?.length > 0 ? (
                <div key={item.id} className="bg-white/3 rounded-xl overflow-hidden">
                  <button onClick={() => setOpenDrop(openDrop === item.id ? null : item.id)}
                    className="w-full flex justify-between items-center px-4 py-3 text-sm font-semibold
                      text-slate-300 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                    <span className="flex items-center gap-2">
                      {item.icon && <i className={`${item.icon} text-xs text-amber-500`} />}
                      {label(item)}
                    </span>
                    <i className={`fas fa-chevron-down text-[10px] transition-transform duration-300
                      ${openDrop === item.id ? 'rotate-180 text-amber-400' : 'text-slate-500'}`} />
                  </button>
                  <div className={`transition-all duration-300 overflow-hidden
                    ${openDrop === item.id ? 'max-h-[500px] opacity-100 pb-2' : 'max-h-0 opacity-0'}`}>
                    <div className="px-2 flex flex-col gap-1">
                      {item.items.map((sub) => {
                        const isExt = sub.url?.startsWith('http')
                        const subCls = "block px-4 py-2.5 text-[13px] font-medium text-slate-400 hover:text-amber-400 hover:bg-white/5 rounded-lg transition-colors cursor-pointer"
                        return isExt ? (
                          <a key={sub.id} href={sub.url} target={sub.target || '_blank'} rel="noreferrer"
                            onClick={() => setOpen(false)} className={subCls}>
                            {label(sub)}
                          </a>
                        ) : (
                          <Link key={sub.id} to={sub.url || '#'} onClick={() => setOpen(false)}
                            className={subCls}>
                            {label(sub)}
                          </Link>
                        )
                      })}
                    </div>
                  </div>
                </div>
              ) : item.url ? renderMobileLink(item) : null
            )}
          </div>
        </div>
      </div>
    </header>
  )
}
