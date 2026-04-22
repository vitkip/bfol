import { Link, useParams } from 'react-router-dom'
import { useState, useEffect } from 'react'
import api from '../../api/client'

const sections = {
  history: {
    title: 'ປະຫວັດຄວາມເປັນມາ',
    icon: 'fas fa-history',
    content: `ສະຫະພັນພຸດທະສາສະໜາລາວ (ອົງສ · BFOL) ໄດ້ກໍ່ຕັ້ງຂຶ້ນ ເພື່ອ ສົ່ງເສີມ ແລະ ພັດທະນາ
    ດ້ານສາສະໜາ ຂອງ ລາວ ທັງ ໃນ ລະດັບ ຊາດ ແລະ ສາກົນ.`,
  },
  mission: {
    title: 'ວິສາຫະກິດ & ຄາລະກິດ',
    icon: 'fas fa-bullseye',
    content: `ວິສາຫະກິດ: ສ້າງສັງຄົມ ທີ່ ສົດຊື່ນ ດ້ວຍ ຄຸນທັມ ແລະ ສາດ ສາສະໜາ.
    ຄາລະກິດ: ສ້າງ ເງື່ອນໄຂ ທີ່ ດີ ສຳລັບ ການ ພັດທະນາ ດ້ານ ສາສະໜາ.`,
  },
  structure: {
    title: 'ໂຄງສ້າງອົງການ',
    icon: 'fas fa-sitemap',
    content: null, // org chart placeholder
  },
  committee: {
    title: 'ຄະນະກຳມະການ',
    icon: 'fas fa-users',
    content: null, // loads from API
  },
}

function CommitteeList() {
  const [members, setMembers] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/committee')
      .then(r => setMembers(r.data?.data || []))
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [])

  if (loading) return (
    <div className="grid grid-cols-2 md:grid-cols-4 gap-5">
      {Array.from({ length: 8 }).map((_, i) => (
        <div key={i} className="animate-pulse">
          <div className="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-3" />
          <div className="h-3 bg-gray-200 rounded w-3/4 mx-auto mb-1" />
          <div className="h-2 bg-gray-200 rounded w-1/2 mx-auto" />
        </div>
      ))}
    </div>
  )

  return (
    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      {members.map(m => (
        <div key={m.id} className="text-center group">
          <div className="w-24 h-24 rounded-full bg-blue-100 mx-auto mb-3 overflow-hidden">
            {m.photo_url ? (
              <img src={m.photo_url} alt={m.name_lo} className="w-full h-full object-cover" />
            ) : (
              <div className="w-full h-full flex items-center justify-center">
                <i className="fas fa-user text-3xl text-blue-300" />
              </div>
            )}
          </div>
          <h3 className="font-semibold text-gray-800 text-sm">{m.name_lo || m.name_en}</h3>
          <p className="text-xs text-blue-600 mt-0.5">{m.position_lo}</p>
        </div>
      ))}
    </div>
  )
}

export default function AboutPage() {
  const { sub } = useParams()
  const section = sections[sub] || sections.history

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="bg-blue-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-3xl font-bold mb-2">{section.title}</h1>
          <nav className="text-sm mt-4 text-blue-300">
            <Link to="/" className="hover:text-white">ໜ້າຫຼັກ</Link>
            <span className="mx-2">/</span>
            <span className="text-white">{section.title}</span>
          </nav>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 py-12">
        {/* Sub-nav */}
        <div className="flex flex-wrap gap-2 mb-10">
          {Object.entries(sections).map(([key, s]) => (
            <Link key={key} to={`/about/${key}`}
              className={`flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                sub === key ? 'bg-blue-900 text-white' : 'bg-white text-gray-600 hover:text-blue-900 shadow-sm'
              }`}>
              <i className={`${s.icon} text-xs`} /> {s.title}
            </Link>
          ))}
        </div>

        <div className="bg-white rounded-2xl shadow-sm p-8">
          {sub === 'committee' ? (
            <CommitteeList />
          ) : sub === 'structure' ? (
            <div className="text-center py-16 text-gray-400">
              <i className="fas fa-sitemap text-6xl mb-4 block text-blue-200" />
              <p className="text-gray-500">ໂຄງສ້າງ ອົງການ</p>
              <p className="text-sm mt-2">(ຮູບ org chart ຈະ ຖືກ ອັບໂຫລດ ໃນ ໄວໆ ນີ້)</p>
            </div>
          ) : (
            <div className="prose max-w-none">
              <div className="flex items-center gap-3 mb-6">
                <div className="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                  <i className={`${section.icon} text-blue-700 text-xl`} />
                </div>
                <h2 className="text-xl font-bold text-blue-900 m-0">{section.title}</h2>
              </div>
              <p className="text-gray-600 leading-relaxed whitespace-pre-line">{section.content}</p>
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
