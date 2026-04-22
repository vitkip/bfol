import { Link, useLocation } from 'react-router-dom'

const labels = {
  '/dhamma/sila': 'ຮຽນ ສີລ ສະມາທິ ທັມ',
  '/dhamma/teach': 'ດ້ານສອນ',
  '/dhamma/research': 'ທັດທະ & ວິໄຊ',
  '/dhamma/social': 'ສາສາສາ & ສັງຄົມ',
  '/foreign/diplomacy': 'ການທູດສາສາສາ',
  '/foreign/exchange': 'ແລກປ່ຽນ ສາກົນ',
  '/foreign/mou': 'MOU ກັບ ຕ່າງປະເທດ',
  '/foreign/aid': 'ໂຄງການ ຊ່ວຍເຫຼືອ',
  '/foreign/education': 'ສຶກສາ ສາກົນ',
  '/media/dhamma-on-len': 'DhammaOnLen',
  '/media/online': 'ການສອນ Online',
  '/media/gallery': 'ຮູບພາບ ກິດຈະກຳ',
  '/media/video': 'ວິດີໂອ',
  '/media/documents': 'ເອກະສານ',
}

export default function PlaceholderPage() {
  const { pathname } = useLocation()
  const label = labels[pathname] || pathname

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="bg-blue-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-3xl font-bold mb-2">{label}</h1>
          <nav className="text-sm mt-4 text-blue-300">
            <Link to="/" className="hover:text-white">ໜ້າຫຼັກ</Link>
            <span className="mx-2">/</span>
            <span className="text-white">{label}</span>
          </nav>
        </div>
      </div>
      <div className="max-w-7xl mx-auto px-4 py-24 text-center">
        <div className="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
          <i className="fas fa-tools text-3xl text-blue-400" />
        </div>
        <h2 className="text-xl font-bold text-gray-700 mb-2">ກຳລັງ ພັດທະນາ</h2>
        <p className="text-gray-500 mb-6">ໜ້ານີ້ ຢູ່ ໃນ ຂັ້ນ ຕອນ ການ ພັດທະນາ ກະລຸນາ ລໍຖ້າ.</p>
        <Link to="/" className="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-900 text-white rounded-lg text-sm hover:bg-blue-800 transition-colors">
          <i className="fas fa-home" /> ກັບ ໄປ ໜ້າຫຼັກ
        </Link>
      </div>
    </div>
  )
}
