import { Link } from 'react-router-dom'
import { useSiteSettings } from '../../context/SiteSettingsContext'
import { useLang } from '../../context/LanguageContext'

export default function Footer() {
  const s    = useSiteSettings()
  const { pick } = useLang()
  const year = new Date().getFullYear()

  const socials = [
    { icon: 'fab fa-facebook-f', href: s.site_facebook, label: 'Facebook', show: !!s.site_facebook, hoverColor: 'hover:text-blue-400' },
    { icon: 'fab fa-youtube',    href: s.site_youtube,  label: 'YouTube',  show: !!s.site_youtube,  hoverColor: 'hover:text-red-400' },
    { icon: 'fab fa-line',       href: s.site_line,     label: 'Line',     show: !!s.site_line,     hoverColor: 'hover:text-green-400' },
    { icon: 'fab fa-weixin',     href: s.site_wechat,   label: 'WeChat',   show: !!s.site_wechat,   hoverColor: 'hover:text-green-400' },
  ].filter(x => x.show)

  const aboutLinks = [
    { label_lo: 'ປະຫວັດຄວາມເປັນມາ',    label_en: 'History',   label_zh: '歷史',   to: '/about/history' },
    { label_lo: 'ວິສາຫະກິດ & ຄາລະກິດ', label_en: 'Mission',   label_zh: '使命',   to: '/about/mission' },
    { label_lo: 'ໂຄງສ້າງອົງການ',        label_en: 'Structure', label_zh: '組織結構', to: '/about/structure' },
    { label_lo: 'ຄະນະກຳມະການ',          label_en: 'Committee', label_zh: '委員會', to: '/about/committee' },
  ]

  const activityLinks = [
    { label_lo: 'ຂ່າວສານ',          label_en: 'News',            label_zh: '新聞',   to: '/news' },
    { label_lo: 'MOU ຕ່າງປະເທດ',    label_en: 'Int\'l MOU',      label_zh: 'MOU協議', to: '/foreign/mou' },
    { label_lo: 'ໂຄງການ ຊ່ວຍເຫຼືອ', label_en: 'Aid Projects',    label_zh: '援助項目', to: '/foreign/aid' },
    { label_lo: 'ແລກປ່ຽນ ສາກົນ',    label_en: 'Int\'l Exchange', label_zh: '國際交流', to: '/foreign/exchange' },
    { label_lo: 'ສື່ & ກິດຈະກຳ',    label_en: 'Media & Events',  label_zh: '媒體活動', to: '/media/gallery' },
  ]

  const NavLink = ({ l }) => (
    <li>
      <Link to={l.to}
        className="group flex items-center gap-2 text-sm text-slate-400 hover:text-amber-400 transition-colors duration-200 cursor-pointer">
        <i className="fas fa-angle-right text-amber-500/30 text-xs group-hover:text-amber-400 group-hover:translate-x-0.5 transition-all" />
        {pick(l.label_lo, l.label_en, l.label_zh)}
      </Link>
    </li>
  )

  return (
    <footer className="relative border-t border-white/5">

      {/* Top accent line */}
      <div className="h-px bg-gradient-to-r from-transparent via-amber-500/30 to-transparent" />

      <div className="max-w-[1400px] mx-auto px-4 pt-14 pb-8">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

          {/* Brand column */}
          <div className="lg:col-span-1">
            <Link to="/" className="flex items-center gap-3 mb-5 group cursor-pointer">
              {s.logo_url ? (
                <img src={s.logo_url} alt={s.site_name_lo}
                  className="w-12 h-12 rounded-xl object-contain bg-white/5 p-0.5 border border-white/10
                    group-hover:border-amber-400/30 transition-all" />
              ) : (
                <div className="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                  <i className="fas fa-dharmachakra text-indigo-950 text-xl" />
                </div>
              )}
              <div>
                <div className="font-bold text-white text-base leading-tight">
                  {pick(s.site_name_lo, s.site_name_en, s.site_name_zh) || 'ອົງສ · BFOL'}
                </div>
                <div className="text-xs text-slate-500 mt-0.5">{s.site_name_en || 'BFOL'}</div>
              </div>
            </Link>

            <p className="text-sm text-slate-500 leading-relaxed mb-6">
              {pick(
                'ອົງການສາສະໜາ ທີ່ ຮ່ວມກັນ ສົ່ງເສີມ ແລະ ພັດທະນາ ດ້ານ ສາສະໜາ ທາງ ລາວ ໃນ ລະດັບ ຊາດ ແລະ ສາກົນ.',
                'A Buddhist organization promoting and developing Lao Buddhism at national and international levels.',
                '致力於在國家和國際層面推廣和發展老撾佛教的宗教組織。'
              )}
            </p>

            {socials.length > 0 && (
              <div className="flex gap-2 flex-wrap">
                {socials.map(sc => (
                  <a key={sc.icon} href={sc.href} target="_blank" rel="noreferrer"
                    aria-label={sc.label}
                    className={`w-9 h-9 rounded-xl glass ${sc.hoverColor} text-slate-500
                      flex items-center justify-center text-sm transition-all duration-200
                      hover:scale-110 hover:bg-white/10 cursor-pointer`}>
                    <i className={sc.icon} />
                  </a>
                ))}
              </div>
            )}
          </div>

          {/* About links */}
          <div>
            <h4 className="text-white font-bold mb-5 text-xs uppercase tracking-[0.15em] flex items-center gap-2">
              <span className="w-4 h-px bg-amber-400" />
              {pick('ກ່ຽວກັບ ອົງສ', 'About BFOL', '關於BFOL')}
            </h4>
            <ul className="space-y-2.5">
              {aboutLinks.map(l => <NavLink key={l.to} l={l} />)}
            </ul>
          </div>

          {/* Activity links */}
          <div>
            <h4 className="text-white font-bold mb-5 text-xs uppercase tracking-[0.15em] flex items-center gap-2">
              <span className="w-4 h-px bg-amber-400" />
              {pick('ກິດຈະກຳ', 'Activities', '活動')}
            </h4>
            <ul className="space-y-2.5">
              {activityLinks.map(l => <NavLink key={l.to} l={l} />)}
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-white font-bold mb-5 text-xs uppercase tracking-[0.15em] flex items-center gap-2">
              <span className="w-4 h-px bg-amber-400" />
              {pick('ຕິດຕໍ່', 'Contact', '聯繫')}
            </h4>
            <ul className="space-y-3.5 text-sm">
              {s.site_address_lo && (
                <li className="flex gap-3 text-slate-400">
                  <div className="w-8 h-8 rounded-xl glass flex items-center justify-center shrink-0 mt-0.5">
                    <i className="fas fa-map-marker-alt text-amber-500 text-xs" />
                  </div>
                  <span className="leading-relaxed">{s.site_address_lo}</span>
                </li>
              )}
              {s.site_phone && (
                <li>
                  <a href={`tel:${s.site_phone}`}
                    className="flex gap-3 text-slate-400 hover:text-amber-400 transition-colors group cursor-pointer">
                    <div className="w-8 h-8 rounded-xl glass flex items-center justify-center shrink-0">
                      <i className="fas fa-phone text-amber-500 text-xs" />
                    </div>
                    <span className="self-center">{s.site_phone}</span>
                  </a>
                </li>
              )}
              {s.site_email && (
                <li>
                  <a href={`mailto:${s.site_email}`}
                    className="flex gap-3 text-slate-400 hover:text-amber-400 transition-colors group cursor-pointer">
                    <div className="w-8 h-8 rounded-xl glass flex items-center justify-center shrink-0">
                      <i className="fas fa-envelope text-amber-500 text-xs" />
                    </div>
                    <span className="self-center break-all">{s.site_email}</span>
                  </a>
                </li>
              )}
              {s.office_hours_lo && (
                <li className="flex gap-3 text-slate-400">
                  <div className="w-8 h-8 rounded-xl glass flex items-center justify-center shrink-0">
                    <i className="fas fa-clock text-amber-500 text-xs" />
                  </div>
                  <span className="self-center">{s.office_hours_lo}</span>
                </li>
              )}
            </ul>
          </div>
        </div>

        {/* Bottom bar */}
        <div className="border-t border-white/5 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-600">
          <span>
            © {year} {pick(s.site_name_lo, s.site_name_en, s.site_name_zh) || 'ອົງສ · BFOL'}
            {' '}— {pick('ສະຫງວນລິຂະສິດ', 'All rights reserved', '版權所有')}
          </span>
          <div className="flex items-center gap-4">
            <Link to="/contact" className="hover:text-amber-400 transition-colors cursor-pointer">
              {pick('ຕິດຕໍ່', 'Contact', '聯繫')}
            </Link>
            <span className="text-white/10">•</span>
            <Link to="/about/history" className="hover:text-amber-400 transition-colors cursor-pointer">
              {pick('ກ່ຽວກັບ', 'About', '關於')}
            </Link>
            <span className="text-white/10">•</span>
            <Link to="/news" className="hover:text-amber-400 transition-colors cursor-pointer">
              {pick('ຂ່າວ', 'News', '新聞')}
            </Link>
          </div>
        </div>
      </div>
    </footer>
  )
}
