import { Link } from 'react-router-dom'
import { useLang } from '../../context/LanguageContext'

// ─── Data: two natural category groups ───────────────────────────────────────
const GROUPS = [
  {
    label_lo: 'ດ້ານ ສາສະໜາ ແລະ ການສຶກສາ',
    label_en: 'Dhamma & Education',
    label_zh: '佛法與教育',
    groupIcon: 'fas fa-dharmachakra',
    areas: [
      {
        num: '01', icon: 'fas fa-book-open',
        title_lo: 'ການສຶກສາ ສາສະໜາ',  title_en: 'Religious Education',        title_zh: '宗教教育',
        desc_lo:  'ຮຽນ ສີລ ສະມາທິ ທັມ ທັງໃນ ແລະ ຕ່າງປະເທດ',
        desc_en:  'Study Sila, Samadhi & Dhamma',                              desc_zh: '學習戒定慧',
        to: '/dhamma/sila',
        color: 'from-amber-500 to-orange-500',
      },
      {
        num: '02', icon: 'fas fa-chalkboard-teacher',
        title_lo: 'ດ້ານການສອນ',           title_en: 'Teaching',                  title_zh: '教學工作',
        desc_lo:  'ຝຶກອົບຮົມ ແລະ ພັດທະນາຄູສອນ ທຄດ',
        desc_en:  'Teacher training & development',                            desc_zh: '培訓教師',
        to: '/dhamma/teach',
        color: 'from-blue-500 to-blue-600',
      },
      {
        num: '03', icon: 'fas fa-microscope',
        title_lo: 'ການຄົ້ນຄ້ວາ ວິໄຈ',   title_en: 'Research',                   title_zh: '學術研究',
        desc_lo:  'ທັດທາ ແລະ ວິໄຊ ດ້ານ ສາສະໜາ ລາວ',
        desc_en:  'Buddhist research in Laos',                                desc_zh: '佛教學術研究',
        to: '/dhamma/research',
        color: 'from-teal-500 to-cyan-600',
      },
      {
        num: '04', icon: 'fas fa-hands-helping',
        title_lo: 'ສາສາສາ ແລະ ສັງຄົມ',  title_en: 'Society & Religion',         title_zh: '宗教與社會',
        desc_lo:  'ກິດຈະກຳ ສຶກສາ ສາດ ໃນ ຊຸມຊົນ',
        desc_en:  'Community religious activities',                            desc_zh: '社區宗教活動',
        to: '/dhamma/social',
        color: 'from-purple-500 to-violet-600',
      },
    ],
  },
  {
    label_lo: 'ດ້ານ ການຕ່າງປະເທດ ແລະ ສາກົນ',
    label_en: 'International Affairs',
    label_zh: '國際事務',
    groupIcon: 'fas fa-globe-asia',
    areas: [
      {
        num: '05', icon: 'fas fa-globe-asia',
        title_lo: 'ການທູດສາສາສາ',         title_en: 'Religious Diplomacy',        title_zh: '宗教外交',
        desc_lo:  'ຄວາມສໍາພັນ ກັບ ອົງການ ສາກົນ ທົ່ວໂລກ',
        desc_en:  'International Buddhist relations',                          desc_zh: '國際佛教關係',
        to: '/foreign/diplomacy',
        color: 'from-red-500 to-rose-600',
      },
      {
        num: '06', icon: 'fas fa-exchange-alt',
        title_lo: 'ແລກປ່ຽນ ສາກົນ',       title_en: "Int'l Exchange",             title_zh: '國際交流',
        desc_lo:  'ໂຄງການ ແລກປ່ຽນ ພ້ອຍ ແລະ ສາມະເນນ',
        desc_en:  'Monk exchange programs',                                    desc_zh: '僧侶交流項目',
        to: '/foreign/exchange',
        color: 'from-indigo-500 to-indigo-600',
      },
      {
        num: '07', icon: 'fas fa-file-signature',
        title_lo: 'MOU ຕ່າງປະເທດ',        title_en: "Int'l MOU",                 title_zh: '國際協議',
        desc_lo:  'ບົດບັນທຶກ ຄວາມເຂົ້າໃຈ ກັບ ປະເທດ ຕ່າງໆ',
        desc_en:  'MOU with foreign countries',                               desc_zh: '與外國簽署協議',
        to: '/foreign/mou',
        color: 'from-green-500 to-emerald-600',
      },
      {
        num: '08', icon: 'fas fa-hand-holding-heart',
        title_lo: 'ໂຄງການ ຊ່ວຍເຫຼືອ',    title_en: 'Aid Projects',               title_zh: '援助項目',
        desc_lo:  'ໂຄງການ ສາດ ຊ່ວຍ ສັງຄົມ ແລະ ຊຸດຊົນ',
        desc_en:  'Community aid programs',                                   desc_zh: '社會援助計劃',
        to: '/foreign/aid',
        color: 'from-orange-500 to-amber-600',
      },
    ],
  },
]

// ─── Area Card ────────────────────────────────────────────────────────────────
function AreaCard({ a, pick }) {
  return (
    <Link
      to={a.to}
      className="group relative flex flex-col items-center text-center cursor-pointer
        p-5 md:p-6 rounded-2xl glass overflow-hidden
        hover:bg-white/10 hover:-translate-y-1.5 transition-all duration-300"
    >
      {/* Gradient wash on hover */}
      <div className={`absolute inset-0 bg-gradient-to-br ${a.color}
        opacity-0 group-hover:opacity-[0.08] transition-opacity duration-300`} />

      {/* Bottom accent line — slides right on hover */}
      <div className={`absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r ${a.color}
        scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left`} />

      {/* Card number */}
      <span className="absolute top-3 right-3 text-[10px] font-bold font-mono
        text-white/10 group-hover:text-white/20 transition-colors select-none">
        {a.num}
      </span>

      {/* Icon box */}
      <div className={`relative w-14 h-14 rounded-2xl bg-gradient-to-br ${a.color}
        flex items-center justify-center mb-4 shadow-lg
        group-hover:scale-110 group-hover:shadow-xl transition-all duration-300`}>
        <i className={`${a.icon} text-white text-xl`} />
      </div>

      {/* Title */}
      <h3 className="relative font-bold text-slate-100 text-sm mb-1.5
        group-hover:text-white transition-colors leading-[1.5]">
        {pick(a.title_lo, a.title_en, a.title_zh)}
      </h3>

      {/* Description */}
      <p className="relative text-xs text-slate-400 leading-relaxed">
        {pick(a.desc_lo, a.desc_en, a.desc_zh)}
      </p>

      {/* Learn more — fades up on hover */}
      <div className="relative mt-4 flex items-center gap-1 text-xs font-semibold text-amber-400
        opacity-0 group-hover:opacity-100 translate-y-1.5 group-hover:translate-y-0
        transition-all duration-200">
        {pick('ເບິ່ງເພີ່ມ', 'Learn more', '了解更多')}
        <i className="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform" />
      </div>
    </Link>
  )
}

// ─── Main Component ───────────────────────────────────────────────────────────
export default function WorkAreas() {
  const { pick } = useLang()

  return (
    <section className="py-16 relative">
      <div className="max-w-[1400px] mx-auto px-4">

        {/* ── Section header ─────────────────────────── */}
        <div className="text-center mb-14">
          <span className="inline-block text-xs font-bold text-amber-400 uppercase tracking-[0.2em]
            mb-3 glass px-4 py-1.5 rounded-full">
            {pick('ພາລະກິດ', 'Our Mission', '使命')}
          </span>
          <h2 className="text-2xl md:text-3xl font-bold text-white mb-3">
            {pick('ວຽກງານ ແລະ ພາລະກິດ', 'Work Areas & Mission', '工作範圍與使命')}
          </h2>
          <p className="text-slate-400 text-sm max-w-lg mx-auto leading-relaxed">
            {pick(
              'ຂົງເຂດ ການ ເຮັດວຽກ ຂອງ ອົງສ ທັງ ດ້ານ ສາສະໜາ ແລະ ສາກົນ',
              'Key areas of work of BFOL in religious and international fields',
              'BFOL的工作範疇，涵蓋宗教與國際領域'
            )}
          </p>
          <div className="h-1 w-16 bg-gradient-to-r from-amber-400 to-amber-600 mt-4 mx-auto rounded-full" />
        </div>

        {/* ── Grouped card sections ───────────────────── */}
        <div className="space-y-12">
          {GROUPS.map((group) => (
            <div key={group.label_en}>

              {/* Group header */}
              <div className="flex items-center gap-3 mb-6">
                <div className="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shrink-0 shadow-md">
                  <i className={`${group.groupIcon} text-indigo-950 text-sm`} />
                </div>
                <h3 className="font-bold text-white text-base tracking-tight">
                  {pick(group.label_lo, group.label_en, group.label_zh)}
                </h3>
                <div className="flex-1 h-px bg-white/5" />
                <span className="text-[11px] text-slate-500 font-medium shrink-0">
                  {group.areas.length} {pick('ລາຍການ', 'areas', '項目')}
                </span>
              </div>

              {/* Card grid */}
              <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
                {group.areas.map((a) => (
                  <AreaCard key={a.to} a={a} pick={pick} />
                ))}
              </div>

            </div>
          ))}
        </div>

      </div>
    </section>
  )
}
