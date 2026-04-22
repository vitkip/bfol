import { Link } from 'react-router-dom'

const galleryPlaceholders = [
  { id: 1, label: 'ກິດຈະກຳ 1' },
  { id: 2, label: 'ກິດຈະກຳ 2' },
  { id: 3, label: 'ກິດຈະກຳ 3' },
  { id: 4, label: 'ກິດຈະກຳ 4' },
  { id: 5, label: 'ກິດຈະກຳ 5' },
  { id: 6, label: 'ກິດຈະກຳ 6' },
]

export default function MediaSection() {
  return (
    <section className="py-14 bg-gray-900 text-white">
      <div className="max-w-7xl mx-auto px-4">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h2 className="text-2xl font-bold">ສື່ ແລະ ກິດຈະກຳ</h2>
            <div className="h-1 w-16 bg-yellow-400 mt-2 rounded" />
          </div>
          <Link to="/media/gallery" className="text-sm text-yellow-400 hover:text-yellow-300 flex items-center gap-1">
            ເບິ່ງທັງໝົດ <i className="fas fa-arrow-right text-xs" />
          </Link>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          {/* Featured Video */}
          <div className="relative rounded-2xl overflow-hidden bg-gray-800 aspect-video group cursor-pointer">
            <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-900 to-blue-950">
              <i className="fab fa-youtube text-6xl text-red-500 opacity-80" />
            </div>
            <div className="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
              <a href="https://www.youtube.com/@DhammaOnLen" target="_blank" rel="noreferrer"
                className="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center hover:scale-110 transition-transform">
                <i className="fas fa-play text-white text-xl ml-1" />
              </a>
            </div>
            <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
              <span className="text-xs bg-red-500 text-white px-2 py-0.5 rounded mr-2">LIVE</span>
              <span className="text-sm font-medium">DhammaOnLen - ຊ່ອງ YouTube ທຳ</span>
            </div>
          </div>

          {/* Gallery Grid */}
          <div className="grid grid-cols-3 gap-2">
            {galleryPlaceholders.map((g, i) => (
              <div key={g.id}
                className={`relative rounded-lg overflow-hidden bg-gray-800 group cursor-pointer ${
                  i === 0 ? 'col-span-2 row-span-2' : ''
                }`}
                style={{ aspectRatio: i === 0 ? '2/1' : '1/1' }}>
                <div className={`w-full h-full flex items-center justify-center bg-gradient-to-br ${
                  i % 3 === 0 ? 'from-blue-800 to-blue-950' :
                  i % 3 === 1 ? 'from-teal-800 to-teal-950' :
                  'from-indigo-800 to-indigo-950'
                }`}>
                  <i className="fas fa-image text-white/30 text-2xl" />
                </div>
                <div className="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                  <i className="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity" />
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Links row */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
          {[
            { icon: 'fab fa-youtube', label: 'YouTube', color: 'bg-red-600', href: 'https://youtube.com' },
            { icon: 'fab fa-facebook-f', label: 'Facebook', color: 'bg-blue-600', href: 'https://www.facebook.com/DhammaOnLen' },
            { icon: 'fas fa-images', label: 'ຮູບພາບ', color: 'bg-teal-600', to: '/media/gallery' },
            { icon: 'fas fa-file-pdf', label: 'ເອກະສານ', color: 'bg-amber-600', to: '/media/documents' },
          ].map(m => (
            m.to ? (
              <Link key={m.label} to={m.to}
                className={`flex items-center gap-3 ${m.color} rounded-xl px-4 py-3 hover:opacity-90 transition-opacity`}>
                <i className={`${m.icon} text-white`} />
                <span className="text-white text-sm font-medium">{m.label}</span>
              </Link>
            ) : (
              <a key={m.label} href={m.href} target="_blank" rel="noreferrer"
                className={`flex items-center gap-3 ${m.color} rounded-xl px-4 py-3 hover:opacity-90 transition-opacity`}>
                <i className={`${m.icon} text-white`} />
                <span className="text-white text-sm font-medium">{m.label}</span>
              </a>
            )
          ))}
        </div>
      </div>
    </section>
  )
}
