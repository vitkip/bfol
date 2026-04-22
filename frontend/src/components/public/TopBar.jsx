import { useSiteSettings } from '../../context/SiteSettingsContext'
import { useLang, LANGS } from '../../context/LanguageContext'

export default function TopBar() {
  const s = useSiteSettings()
  const { lang, setLang } = useLang()

  return (
    <div className="bg-indigo-950/60 backdrop-blur-xl text-slate-400 text-[11px] font-medium py-2.5
      border-b border-white/5 hidden sm:block">
      <div className="max-w-[1400px] mx-auto px-4 flex justify-between items-center">
        <div className="flex gap-6 items-center">
          {s.site_phone && (
            <span className="flex items-center gap-2 tracking-wide hover:text-amber-400 transition-colors cursor-default">
              <i className="fas fa-phone text-amber-500 text-[10px]" />
              {s.site_phone}
            </span>
          )}
          {s.site_email && (
            <a href={`mailto:${s.site_email}`} className="flex items-center gap-2 tracking-wide hover:text-amber-400 transition-colors">
              <i className="fas fa-envelope text-amber-500 text-[10px]" />
              {s.site_email}
            </a>
          )}
        </div>

        <div className="flex items-center gap-3">
          {/* Social links */}
          <div className="flex items-center gap-3 mr-3">
            {s.site_facebook && (
              <a href={s.site_facebook} target="_blank" rel="noreferrer"
                className="text-slate-500 hover:text-blue-400 hover:-translate-y-0.5 transition-all duration-300 cursor-pointer">
                <i className="fab fa-facebook-f text-[13px]" />
              </a>
            )}
            {s.site_youtube && (
              <a href={s.site_youtube} target="_blank" rel="noreferrer"
                className="text-slate-500 hover:text-red-400 hover:-translate-y-0.5 transition-all duration-300 cursor-pointer">
                <i className="fab fa-youtube text-[13px]" />
              </a>
            )}
            {s.site_line && (
              <a href={s.site_line} target="_blank" rel="noreferrer"
                className="text-slate-500 hover:text-green-400 hover:-translate-y-0.5 transition-all duration-300 cursor-pointer">
                <i className="fab fa-line text-[13px]" />
              </a>
            )}
          </div>

          {/* Divider */}
          <span className="w-px h-3 bg-white/10 mx-1" />

          {/* Language switcher */}
          <div className="flex items-center gap-0.5 glass rounded-lg p-0.5">
            {Object.entries(LANGS).map(([code, info]) => (
              <button
                key={code}
                onClick={() => setLang(code)}
                title={info.label}
                className={`px-2.5 py-1 rounded-md text-[10px] uppercase tracking-wider font-bold
                  transition-all duration-300 cursor-pointer ${
                  lang === code
                    ? 'bg-amber-500 text-indigo-950 shadow-sm shadow-amber-500/30'
                    : 'text-slate-400 hover:text-white hover:bg-white/10'
                }`}
              >
                {info.short}
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
