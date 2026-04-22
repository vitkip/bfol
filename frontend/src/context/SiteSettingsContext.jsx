import { createContext, useContext, useEffect, useState } from 'react'
import api from '../api/client'

const defaults = {
  site_name_lo:    'ອົງສ · BFOL',
  site_name_en:    'BFOL',
  site_name_zh:    '',
  logo_url:        null,
  favicon_url:     null,
  site_phone:      '021-000-000',
  site_email:      'bfol.foreign@gmail.com',
  site_address_lo: 'ນະຄອນຫຼວງວຽງຈັນ, ສ.ປ.ປ. ລາວ',
  office_hours_lo: 'ຈ-ສ: 8:00 - 17:00',
  site_facebook:   'https://www.facebook.com/DhammaOnLen',
  site_youtube:    '',
  site_line:       '',
  site_wechat:     '',
}

const SiteSettingsContext = createContext(defaults)

export function SiteSettingsProvider({ children }) {
  const [settings, setSettings] = useState(defaults)

  useEffect(() => {
    api.get('/public/settings')
      .then(r => setSettings({ ...defaults, ...r.data }))
      .catch(() => {})
  }, [])

  // Update favicon and document title when settings load
  useEffect(() => {
    if (settings.site_name_lo) {
      document.title = settings.site_name_lo
    }
    if (settings.favicon_url) {
      let link = document.querySelector("link[rel~='icon']")
      if (!link) {
        link = document.createElement('link')
        link.rel = 'icon'
        document.head.appendChild(link)
      }
      link.href = settings.favicon_url
    }
  }, [settings.site_name_lo, settings.favicon_url])

  return (
    <SiteSettingsContext.Provider value={settings}>
      {children}
    </SiteSettingsContext.Provider>
  )
}

export const useSiteSettings = () => useContext(SiteSettingsContext)
