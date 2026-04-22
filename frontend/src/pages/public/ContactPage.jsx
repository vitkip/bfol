import { useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/client'
import { useSiteSettings } from '../../context/SiteSettingsContext'

export default function ContactPage() {
  const s = useSiteSettings()
  const [form, setForm] = useState({ name: '', email: '', phone: '', subject: '', message: '' })
  const [status, setStatus] = useState(null) // 'success' | 'error' | null
  const [sending, setSending] = useState(false)

  const handleSubmit = async (e) => {
    e.preventDefault()
    setSending(true)
    setStatus(null)
    try {
      await api.post('/public/contact', form)
      setStatus('success')
      setForm({ name: '', email: '', phone: '', subject: '', message: '' })
    } catch {
      setStatus('error')
    } finally {
      setSending(false)
    }
  }

  const field = (name, label, type = 'text', required = true) => (
    <div>
      <label className="block text-sm font-medium text-gray-700 mb-1">{label}{required && <span className="text-red-500 ml-0.5">*</span>}</label>
      <input
        type={type}
        value={form[name]}
        onChange={e => setForm(f => ({ ...f, [name]: e.target.value }))}
        required={required}
        className="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      />
    </div>
  )

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-blue-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-3xl font-bold mb-2">ຕິດຕໍ່ເຮົາ</h1>
          <p className="text-blue-300">ສົ່ງຂໍ້ຄວາມຫາ ອົງສ</p>
          <nav className="text-sm mt-4 text-blue-300">
            <Link to="/" className="hover:text-white">ໜ້າຫຼັກ</Link>
            <span className="mx-2">/</span>
            <span className="text-white">ຕິດຕໍ່</span>
          </nav>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 py-12">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
          {/* Info */}
          <div className="lg:col-span-1 space-y-6">
            <div>
              <h2 className="text-lg font-bold text-blue-900 mb-4">ຂໍ້ມູນ ຕິດຕໍ່</h2>
              <div className="space-y-4">
                {[
                  { icon: 'fas fa-map-marker-alt', label: 'ທີ່ຢູ່',       value: s.site_address_lo },
                  { icon: 'fas fa-phone',          label: 'ໂທລະສັບ',      value: s.site_phone },
                  { icon: 'fas fa-envelope',       label: 'ອີເມວ',         value: s.site_email },
                  { icon: 'fas fa-clock',          label: 'ເວລາ ເຮັດວຽກ', value: s.office_hours_lo },
                ].filter(c => c.value).map(c => (
                  <div key={c.label} className="flex gap-4">
                    <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                      <i className={`${c.icon} text-blue-700`} />
                    </div>
                    <div>
                      <div className="text-xs text-gray-500 mb-0.5">{c.label}</div>
                      <div className="text-sm font-medium text-gray-800">{c.value}</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            <div>
              <h3 className="text-sm font-bold text-gray-700 mb-3">ຊ່ອງ ສັງຄົມ</h3>
              <div className="flex gap-3">
                <a href={s.site_facebook || '#'} target="_blank" rel="noreferrer"
                  className="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:opacity-80 transition-opacity">
                  <i className="fab fa-facebook-f" />
                </a>
                {s.site_youtube && (
                  <a href={s.site_youtube} target="_blank" rel="noreferrer"
                    className="w-10 h-10 bg-red-600 text-white rounded-lg flex items-center justify-center hover:opacity-80 transition-opacity">
                    <i className="fab fa-youtube" />
                  </a>
                )}
                {s.site_line && (
                  <a href={s.site_line} target="_blank" rel="noreferrer"
                    className="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center hover:opacity-80 transition-opacity">
                    <i className="fab fa-line" />
                  </a>
                )}
              </div>
            </div>
          </div>

          {/* Form */}
          <div className="lg:col-span-2 bg-white rounded-2xl shadow-sm p-8">
            <h2 className="text-lg font-bold text-blue-900 mb-6">ສົ່ງ ຂໍ້ຄວາມ</h2>

            {status === 'success' && (
              <div className="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                <i className="fas fa-check-circle" /> ສົ່ງ ຂໍ້ຄວາມ ສຳເລັດ! ພວກເຮົາຈະ ຕິດຕໍ່ ກັບ ຄຶນ.
              </div>
            )}
            {status === 'error' && (
              <div className="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                <i className="fas fa-exclamation-circle" /> ເກີດ ຂໍ້ຜິດພາດ ກະລຸນາ ລອງ ໃໝ່.
              </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {field('name', 'ຊື່ ແລະ ນາມສະກຸນ')}
                {field('email', 'ອີເມວ', 'email')}
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {field('phone', 'ໂທລະສັບ', 'tel', false)}
                {field('subject', 'ຫົວຂໍ້')}
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  ຂໍ້ຄວາມ <span className="text-red-500">*</span>
                </label>
                <textarea
                  rows={5}
                  value={form.message}
                  onChange={e => setForm(f => ({ ...f, message: e.target.value }))}
                  required
                  className="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                />
              </div>
              <button type="submit" disabled={sending}
                className="w-full bg-blue-900 text-white py-3 rounded-lg font-medium hover:bg-blue-800 transition-colors disabled:opacity-60 flex items-center justify-center gap-2">
                {sending ? <><i className="fas fa-spinner fa-spin" /> ກຳລັງສົ່ງ...</> : <><i className="fas fa-paper-plane" /> ສົ່ງ ຂໍ້ຄວາມ</>}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  )
}
