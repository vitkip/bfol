import { createContext, useContext, useState, useCallback } from 'react'

export const LANGS = {
  lo: { label: 'ລາວ',    flag: '🇱🇦', short: 'LA' },
  en: { label: 'English', flag: '🇬🇧', short: 'EN' },
  zh: { label: '中文',    flag: '🇨🇳', short: 'ZH' },
}

// Static UI labels per language
export const UI = {
  lo: {
    readMore:      'ອ່ານຕໍ່ →',
    home:          'ໜ້າຫຼັກ',
    news:          'ຂ່າວສານ',
    allNews:       'ຂ່າວທັງໝົດ',
    backToNews:    '← ກັບໄປຫາຂ່າວທັງໝົດ',
    contact:       'ຕິດຕໍ່',
    noContent:     'ບໍ່ມີເນື້ອຫາ',
    notFound:      'ບໍ່ພົບໜ້ານີ້',
    notPublished:  'ຍັງບໍ່ໄດ້ເຜີຍແຜ່',
    backHome:      '← ກັບໄປໜ້າຫຼັກ',
    urgent:        'ດ່ວນ',
    featured:      'ຂ່າວເດັ່ນ',
    views:         'ຄັ້ງ',
    pageNotFound:  'ບໍ່ພົບຂ່າວນີ້',
    viewAll:       'ເບິ່ງທັງໝົດ',
    latest:        'ຂ່າວລ່າສຸດ',
    featuredTab:   'ຂ່າວເດັ່ນ',
    events:        'ກິດຈະກຳ',
    newsAndEvents: 'ຂ່າວສານ ແລະ ກິດຈະກຳ',
    noData:        'ບໍ່ມີຂໍ້ມູນ',
    send:          'ສົ່ງຂໍ້ຄວາມ',
    name:          'ຊື່-ນາມສະກຸນ',
    email:         'ອີເມລ',
    phone:         'ໂທລະສັບ',
    subject:       'ຫົວຂໍ້',
    message:       'ຂໍ້ຄວາມ',
    sending:       'ກຳລັງສົ່ງ...',
    sentOk:        'ສົ່ງຂໍ້ຄວາມສຳເລັດ! ພວກເຮົາຈະຕິດຕໍ່ກັບທ່ານໄວໆນີ້.',
    prev:          'ກ່ອນ',
    next:          'ຕໍ່ໄປ',
  },
  en: {
    readMore:      'Read more →',
    home:          'Home',
    news:          'News',
    allNews:       'All News',
    backToNews:    '← Back to News',
    contact:       'Contact',
    noContent:     'No content available',
    notFound:      'Page not found',
    notPublished:  'Not yet published',
    backHome:      '← Back to Home',
    urgent:        'Urgent',
    featured:      'Featured',
    views:         'views',
    pageNotFound:  'News article not found',
    viewAll:       'View all',
    latest:        'Latest News',
    featuredTab:   'Featured',
    events:        'Events',
    newsAndEvents: 'News & Events',
    noData:        'No data available',
    send:          'Send Message',
    name:          'Full Name',
    email:         'Email',
    phone:         'Phone',
    subject:       'Subject',
    message:       'Message',
    sending:       'Sending...',
    sentOk:        'Message sent! We will contact you soon.',
    prev:          'Prev',
    next:          'Next',
  },
  zh: {
    readMore:      '閱讀更多 →',
    home:          '首頁',
    news:          '新聞',
    allNews:       '所有新聞',
    backToNews:    '← 返回新聞列表',
    contact:       '聯繫我們',
    noContent:     '暫無內容',
    notFound:      '頁面未找到',
    notPublished:  '尚未發佈',
    backHome:      '← 返回首頁',
    urgent:        '緊急',
    featured:      '精選新聞',
    views:         '次瀏覽',
    pageNotFound:  '找不到該新聞',
    viewAll:       '查看全部',
    latest:        '最新新聞',
    featuredTab:   '精選',
    events:        '活動',
    newsAndEvents: '新聞與活動',
    noData:        '暫無資料',
    send:          '發送消息',
    name:          '姓名',
    email:         '電子郵件',
    phone:         '電話',
    subject:       '主題',
    message:       '消息',
    sending:       '發送中...',
    sentOk:        '消息已發送！我們將盡快與您聯繫。',
    prev:          '上一頁',
    next:          '下一頁',
  },
}

const LanguageContext = createContext({
  lang: 'lo',
  setLang: () => {},
  pick: (lo) => lo || '',
  ui: UI.lo,
})

export function LanguageProvider({ children }) {
  const [lang, setLangState] = useState(() => {
    const saved = localStorage.getItem('bfol_lang')
    return ['lo', 'en', 'zh'].includes(saved) ? saved : 'lo'
  })

  const setLang = useCallback((l) => {
    localStorage.setItem('bfol_lang', l)
    setLangState(l)
  }, [])

  // pick the right language value, falling back to lo then en
  const pick = useCallback((lo, en, zh) => {
    if (lang === 'en') return en || lo || ''
    if (lang === 'zh') return zh || en || lo || ''
    return lo || en || ''
  }, [lang])

  return (
    <LanguageContext.Provider value={{ lang, setLang, pick, ui: UI[lang] }}>
      {children}
    </LanguageContext.Provider>
  )
}

export const useLang = () => useContext(LanguageContext)
