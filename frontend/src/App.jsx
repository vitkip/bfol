import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { SiteSettingsProvider } from './context/SiteSettingsContext'
import { LanguageProvider } from './context/LanguageContext'
import PublicLayout from './components/public/PublicLayout'

import Home            from './pages/public/Home'
import NewsPage        from './pages/public/NewsPage'
import ContactPage     from './pages/public/ContactPage'
import AboutPage       from './pages/public/AboutPage'
import PlaceholderPage  from './pages/public/PlaceholderPage'
import CmsPage          from './pages/public/CmsPage'
import NewsDetailPage   from './pages/public/NewsDetailPage'

function App() {
  return (
    <LanguageProvider>
    <SiteSettingsProvider>
      <BrowserRouter>
        <Routes>
          <Route element={<PublicLayout />}>
            <Route path="/"             element={<Home />} />
            <Route path="/news"         element={<NewsPage />} />
            <Route path="/news/:slug"   element={<NewsDetailPage />} />
            <Route path="/contact"      element={<ContactPage />} />
            <Route path="/about/:sub"   element={<AboutPage />} />
            <Route path="/dhamma/:sub"  element={<PlaceholderPage />} />
            <Route path="/foreign/:sub" element={<PlaceholderPage />} />
            <Route path="/media/:sub"   element={<PlaceholderPage />} />
            <Route path="/lo/page/:slug" element={<CmsPage />} />
            <Route path="*"            element={<Navigate to="/" replace />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </SiteSettingsProvider>
    </LanguageProvider>
  )
}

export default App
