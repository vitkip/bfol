import { Link } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'

const galleryPlaceholders = [
  { id: 1 }, { id: 2 }, { id: 3 },
  { id: 4 }, { id: 5 }, { id: 6 },
]

const gradients = [
  'from-indigo-700 to-purple-800',
  'from-teal-700 to-cyan-800',
  'from-blue-700 to-indigo-800',
  'from-purple-700 to-violet-800',
  'from-sky-700 to-blue-800',
  'from-indigo-800 to-purple-900',
]

export default function MediaSection() {
  const { pick } = useLang()

  const mediaLinks = [
    {
      icon: 'fab fa-youtube',
      label: 'YouTube',
      sublabel: pick('ຊ່ອງວິດີໂອ', 'Video Channel', '視頻頻道'),
      color: 'from-red-600 to-red-700',
      href: 'https://www.youtube.com/@DhammaOnLen',
    },
    {
      icon: 'fab fa-facebook-f',
      label: 'Facebook',
      sublabel: 'DhammaOnLen',
      color: 'from-blue-600 to-blue-700',
      href: 'https://www.facebook.com/DhammaOnLen',
    },
    {
      icon: 'fas fa-images',
      label: pick('ຮູບພາບ', 'Gallery', '相冊'),
      sublabel: pick('ກິດຈະກຳ', 'Activities', '活動'),
      color: 'from-teal-600 to-teal-700',
      to: '/media/gallery',
    },
    {
      icon: 'fas fa-file-pdf',
      label: pick('ເອກະສານ', 'Documents', '文件'),
      sublabel: pick('PDF & ໄຟລ໌', 'PDF & Files', 'PDF及文件'),
      color: 'from-amber-600 to-orange-600',
      to: '/media/documents',
    },
  ]

  return (
    <section className="py-16 relative overflow-hidden">
      {/* Top accent line */}
      <div className="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-500/20 to-transparent" />

      <div className="relative max-w-[1400px] mx-auto px-4">

        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
          <div>
            <span className="inline-block text-xs font-bold text-amber-400 uppercase tracking-[0.2em] mb-2">
              {pick('ສື່ ດິຈິຕອລ', 'Digital Media', '數字媒體')}
            </span>
            <h2 className="text-2xl md:text-3xl font-bold text-white">
              {pick('ສື່ ແລະ ກິດຈະກຳ', 'Media & Events', '媒體與活動')}
            </h2>
            <div className="h-1 w-16 bg-gradient-to-r from-amber-400 to-amber-600 mt-3 rounded-full" />
          </div>
          <Link to="/media/gallery"
            className="inline-flex items-center gap-2 text-sm text-amber-400 hover:text-amber-300 font-medium transition-colors group shrink-0 cursor-pointer">
            {pick('ເບິ່ງທັງໝົດ', 'View all', '查看全部')}
            <i className="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform" />
          </Link>
        </div>

        {/* Main grid */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

          {/* Featured YouTube */}
          <a href="https://www.youtube.com/@DhammaOnLen" target="_blank" rel="noreferrer"
            className="group relative rounded-2xl overflow-hidden glass aspect-video cursor-pointer block">
            <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-950/50 to-purple-950/50">
              <div className="text-center">
                <i className="fab fa-youtube text-7xl text-red-500/50 mb-4 block group-hover:text-red-500 transition-colors" />
                <p className="text-slate-500 text-sm">DhammaOnLen</p>
              </div>
            </div>
            {/* Play button overlay */}
            <div className="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
              <div className="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center shadow-2xl shadow-red-900/50 scale-90 group-hover:scale-100 transition-transform duration-300">
                <i className="fas fa-play text-white text-2xl ml-1.5" />
              </div>
            </div>
            {/* Bottom label */}
            <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-indigo-950/90 via-indigo-950/40 to-transparent p-5">
              <div className="flex items-center gap-2 mb-1">
                <span className="text-xs bg-red-500 text-white px-2 py-0.5 rounded font-bold">YouTube</span>
                <span className="text-xs text-amber-400 font-medium animate-pulse">● LIVE</span>
              </div>
              <p className="text-white font-semibold text-sm">DhammaOnLen — {pick('ຊ່ອງ YouTube ທຳ', 'Official YouTube Channel', '官方YouTube頻道')}</p>
            </div>
          </a>

          {/* Gallery masonry */}
          <div className="grid grid-cols-3 gap-2">
            {galleryPlaceholders.map((g, i) => (
              <Link
                key={g.id}
                to="/media/gallery"
                className={`group relative rounded-xl overflow-hidden cursor-pointer ${
                  i === 0 ? 'col-span-2 row-span-2' : ''
                }`}
                style={{ aspectRatio: i === 0 ? '16/9' : '1/1' }}
              >
                <div className={`w-full h-full bg-gradient-to-br ${gradients[i]}`} />
                <div className="absolute inset-0 flex items-center justify-center">
                  <i className="fas fa-image text-white/10 text-3xl" />
                </div>
                <div className="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
                  <div className="opacity-0 group-hover:opacity-100 transition-opacity text-center">
                    <i className="fas fa-expand-alt text-white text-lg mb-1 block" />
                    {i === 0 && (
                      <span className="text-white text-xs">{pick('ເບິ່ງຮູບ', 'View Gallery', '查看相冊')}</span>
                    )}
                  </div>
                </div>
                {i === 5 && (
                  <div className="absolute inset-0 bg-indigo-950/60 flex items-center justify-center">
                    <div className="text-center">
                      <span className="text-white font-bold text-lg block">+</span>
                      <span className="text-white/80 text-xs">{pick('ເພີ່ມ', 'More', '更多')}</span>
                    </div>
                  </div>
                )}
              </Link>
            ))}
          </div>
        </div>

        {/* Channel links */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
          {mediaLinks.map(m => {
            const cls = `group flex items-center gap-3.5 bg-gradient-to-r ${m.color} rounded-xl px-4 py-3.5
              hover:opacity-90 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg cursor-pointer`
            const inner = (
              <>
                <div className="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                  <i className={`${m.icon} text-white`} />
                </div>
                <div className="min-w-0">
                  <div className="text-white text-sm font-bold leading-tight">{m.label}</div>
                  <div className="text-white/70 text-xs truncate">{m.sublabel}</div>
                </div>
                <i className="fas fa-arrow-right text-white/40 text-xs ml-auto group-hover:text-white/80 group-hover:translate-x-0.5 transition-all" />
              </>
            )
            return m.to ? (
              <Link key={m.label} to={m.to} className={cls}>{inner}</Link>
            ) : (
              <a key={m.label} href={m.href} target="_blank" rel="noreferrer" className={cls}>{inner}</a>
            )
          })}
        </div>
      </div>
    </section>
  )
}
