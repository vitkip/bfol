import { Link } from 'react-router-dom'

const areas = [
  {
    icon: 'fas fa-book-open',
    title: 'ການສຶກສາ ສາສະໜາ',
    desc: 'ຮຽນ ສີລ ສະມາທິ ທັມ ທັງໃນ ແລະ ຕ່າງປະເທດ',
    to: '/dhamma/sila',
    color: 'bg-amber-500',
  },
  {
    icon: 'fas fa-chalkboard-teacher',
    title: 'ດ້ານການສອນ',
    desc: 'ຝຶກອົບຮົມ ແລະ ພັດທະນາຄູສອນ ທ+ຄ+ດ',
    to: '/dhamma/teach',
    color: 'bg-blue-600',
  },
  {
    icon: 'fas fa-microscope',
    title: 'ການຄົ້ນຄ້ວາ ວິໄຈ',
    desc: 'ທັດທາ ແລະ ວິໄຈ ດ້ານ ສາສະໜາ ລາວ',
    to: '/dhamma/research',
    color: 'bg-teal-600',
  },
  {
    icon: 'fas fa-hands-helping',
    title: 'ສາສາສາ ແລະ ສັງຄົມ',
    desc: 'ກິດຈະກຳ ສຶກສາ ສາດ ໃນ ຊຸມຊົນ ແລະ ສັງຄົມ',
    to: '/dhamma/social',
    color: 'bg-purple-600',
  },
  {
    icon: 'fas fa-globe-asia',
    title: 'ການທູດສາສາສາ',
    desc: 'ຄວາມສໍາພັນ ກັບ ອົງການ ສາກົນ ທົ່ວໂລກ',
    to: '/foreign/diplomacy',
    color: 'bg-red-600',
  },
  {
    icon: 'fas fa-exchange-alt',
    title: 'ແລກປ່ຽນ ສາກົນ',
    desc: 'ໂຄງການ ແລກປ່ຽນ ພ້ອຍ ແລະ ສາມະເນນ ສາກົນ',
    to: '/foreign/exchange',
    color: 'bg-indigo-600',
  },
  {
    icon: 'fas fa-file-signature',
    title: 'MOU ຕ່າງປະເທດ',
    desc: 'ບົດບັນທຶກ ຄວາມເຂົ້າໃຈ ກັບ ປະເທດ ຕ່າງໆ',
    to: '/foreign/mou',
    color: 'bg-green-600',
  },
  {
    icon: 'fas fa-hand-holding-heart',
    title: 'ໂຄງການ ຊ່ວຍເຫຼືອ',
    desc: 'ໂຄງການ ສາດ ຊ່ວຍ ສັງຄົມ ແລະ ຊຸດຊົນ',
    to: '/foreign/aid',
    color: 'bg-orange-500',
  },
]

export default function WorkAreas() {
  return (
    <section className="py-14 bg-white">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-10">
          <h2 className="text-2xl font-bold text-blue-900">ວຽກງານ ແລະ ພາລະກິດ</h2>
          <p className="text-gray-500 mt-2 text-sm">ຂົງເຂດ ການ ເຮັດວຽກ ຂອງ ອົງສ</p>
          <div className="h-1 w-16 bg-yellow-400 mt-3 mx-auto rounded" />
        </div>

        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
          {areas.map(a => (
            <Link key={a.to} to={a.to}
              className="group flex flex-col items-center text-center p-6 rounded-2xl border border-gray-100 hover:border-transparent hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
              <div className={`w-14 h-14 rounded-2xl ${a.color} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
                <i className={`${a.icon} text-white text-xl`} />
              </div>
              <h3 className="font-semibold text-gray-800 text-sm mb-1 group-hover:text-blue-900 transition-colors">
                {a.title}
              </h3>
              <p className="text-xs text-gray-500 leading-relaxed">{a.desc}</p>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}
