import { Link } from 'react-router-dom'
import { useSiteSettings } from '../../context/SiteSettingsContext'

export default function Footer() {
  const s = useSiteSettings()
  const year = new Date().getFullYear()

  const socials = [
    { icon: 'fab fa-facebook-f', href: s.site_facebook, show: !!s.site_facebook },
    { icon: 'fab fa-youtube',    href: s.site_youtube,  show: !!s.site_youtube },
    { icon: 'fab fa-line',       href: s.site_line,     show: !!s.site_line },
  ].filter(x => x.show)

  return (
    <footer className="bg-blue-950 text-blue-200">
      <div className="max-w-7xl mx-auto px-4 pt-14 pb-8">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">

          {/* Brand */}
          <div>
            <div className="flex items-center gap-3 mb-4">
              {s.logo_url ? (
                <img src={s.logo_url} alt={s.site_name_lo}
                  className="w-11 h-11 rounded-full object-contain bg-white p-0.5" />
              ) : (
                <div className="w-11 h-11 rounded-full bg-yellow-400 flex items-center justify-center">
                  <i className="fas fa-dharmachakra text-blue-900 text-xl" />
                </div>
              )}
              <div>
                <div className="font-bold text-white text-base">{s.site_name_lo || 'ອົງສ · BFOL'}</div>
                <div className="text-xs text-blue-400">{s.site_name_en || 'BFOL'}</div>
              </div>
            </div>
            <p className="text-sm text-blue-300 leading-relaxed mb-5">
              ອົງການສາສະໜາ ທີ່ ຮ່ວມກັນ ສົ່ງເສີມ ແລະ ພັດທະນາ ດ້ານ ສາສະໜາ ທາງ ລາວ ໃນ ລະດັບ ຊາດ ແລະ ສາກົນ.
            </p>
            {socials.length > 0 && (
              <div className="flex gap-3">
                {socials.map(sc => (
                  <a key={sc.icon} href={sc.href} target="_blank" rel="noreferrer"
                    className="w-9 h-9 rounded-full bg-blue-800 hover:bg-yellow-400 hover:text-blue-900 flex items-center justify-center text-sm transition-colors">
                    <i className={sc.icon} />
                  </a>
                ))}
              </div>
            )}
          </div>

          {/* About */}
          <div>
            <h4 className="text-white font-bold mb-4 text-sm uppercase tracking-wide">ກ່ຽວກັບ ອົງສ</h4>
            <ul className="space-y-2">
              {[
                { label: 'ປະຫວັດຄວາມເປັນມາ',    to: '/about/history' },
                { label: 'ວິສາຫະກິດ & ຄາລະກິດ', to: '/about/mission' },
                { label: 'ໂຄງສ້າງອົງການ',        to: '/about/structure' },
                { label: 'ຄະນະກຳມະການ',          to: '/about/committee' },
              ].map(l => (
                <li key={l.to}>
                  <Link to={l.to} className="text-sm hover:text-yellow-400 transition-colors flex items-center gap-2">
                    <i className="fas fa-angle-right text-yellow-400/60 text-xs" />
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Activities */}
          <div>
            <h4 className="text-white font-bold mb-4 text-sm uppercase tracking-wide">ກິດຈະກຳ</h4>
            <ul className="space-y-2">
              {[
                { label: 'ຂ່າວສານ',          to: '/news' },
                { label: 'MOU ຕ່າງປະເທດ',    to: '/foreign/mou' },
                { label: 'ໂຄງການ ຊ່ວຍເຫຼືອ', to: '/foreign/aid' },
                { label: 'ແລກປ່ຽນ ສາກົນ',    to: '/foreign/exchange' },
                { label: 'ສື່ & ກິດຈະກຳ',    to: '/media/gallery' },
              ].map(l => (
                <li key={l.to}>
                  <Link to={l.to} className="text-sm hover:text-yellow-400 transition-colors flex items-center gap-2">
                    <i className="fas fa-angle-right text-yellow-400/60 text-xs" />
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-white font-bold mb-4 text-sm uppercase tracking-wide">ຕິດຕໍ່</h4>
            <ul className="space-y-3 text-sm">
              {s.site_address_lo && (
                <li className="flex gap-3">
                  <i className="fas fa-map-marker-alt text-yellow-400 mt-0.5 w-4 flex-shrink-0" />
                  <span>{s.site_address_lo}</span>
                </li>
              )}
              {s.site_phone && (
                <li className="flex gap-3">
                  <i className="fas fa-phone text-yellow-400 mt-0.5 w-4 flex-shrink-0" />
                  <a href={`tel:${s.site_phone}`} className="hover:text-yellow-400 transition-colors">
                    {s.site_phone}
                  </a>
                </li>
              )}
              {s.site_email && (
                <li className="flex gap-3">
                  <i className="fas fa-envelope text-yellow-400 mt-0.5 w-4 flex-shrink-0" />
                  <a href={`mailto:${s.site_email}`} className="hover:text-yellow-400 transition-colors">
                    {s.site_email}
                  </a>
                </li>
              )}
              {s.office_hours_lo && (
                <li className="flex gap-3">
                  <i className="fas fa-clock text-yellow-400 mt-0.5 w-4 flex-shrink-0" />
                  <span>{s.office_hours_lo}</span>
                </li>
              )}
            </ul>
          </div>
        </div>

        {/* Bottom */}
        <div className="border-t border-blue-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-blue-400">
          <span>© {year} {s.site_name_lo || 'ອົງສ · BFOL'}. ສະຫງວນລິຂະສິດ.</span>
          <div className="flex gap-4">
            <Link to="/contact" className="hover:text-yellow-400 transition-colors">ຕິດຕໍ່</Link>
            <span className="text-blue-700">|</span>
            <Link to="/about/history" className="hover:text-yellow-400 transition-colors">ກ່ຽວກັບ</Link>
          </div>
        </div>
      </div>
    </footer>
  )
}
