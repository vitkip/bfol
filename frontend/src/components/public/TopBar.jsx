import { useSiteSettings } from '../../context/SiteSettingsContext'
import { useLang, LANGS } from '../../context/LanguageContext'

export default function TopBar() {
  const s = useSiteSettings()
  const { lang, setLang } = useLang()

  return (
    <div className="bg-blue-950 text-blue-200 text-xs py-1.5">
      <div className="max-w-7xl mx-auto px-4 flex justify-between items-center">
        <div className="flex gap-4">
          {s.site_phone && (
            <span className="flex items-center gap-1.5">
              <i className="fas fa-phone text-yellow-400" />
              {s.site_phone}
            </span>
          )}
          {s.site_email && (
            <a href={`mailto:${s.site_email}`} className="flex items-center gap-1.5 hover:text-white">
              <i className="fas fa-envelope text-yellow-400" />
              {s.site_email}
            </a>
          )}
        </div>

        <div className="flex items-center gap-1">
          {/* Language switcher */}
          {Object.entries(LANGS).map(([code, info]) => (
            <button
              key={code}
              onClick={() => setLang(code)}
              title={info.label}
              className={`px-2 py-0.5 rounded text-xs font-bold transition-colors ${
                lang === code
                  ? 'bg-yellow-500 text-blue-950'
                  : 'text-blue-300 hover:text-white hover:bg-blue-800'
              }`}
            >
              {info.short}
            </button>
          ))}

          {/* Divider */}
          <span className="w-px h-3 bg-blue-700 mx-1" />

          {/* Social links */}
          {s.site_facebook && (
            <a href={s.site_facebook} target="_blank" rel="noreferrer"
              className="hover:text-white transition-colors px-1">
              <i className="fab fa-facebook-f" />
            </a>
          )}
          {s.site_youtube && (
            <a href={s.site_youtube} target="_blank" rel="noreferrer"
              className="hover:text-white transition-colors px-1">
              <i className="fab fa-youtube" />
            </a>
          )}
          {s.site_line && (
            <a href={s.site_line} target="_blank" rel="noreferrer"
              className="hover:text-white transition-colors px-1">
              <i className="fab fa-line" />
            </a>
          )}
        </div>
      </div>
    </div>
  )
}
