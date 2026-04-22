import { useEffect, useState, useCallback } from 'react'
import { Outlet, useLocation } from 'react-router-dom'
import TopBar from './TopBar'
import Navbar from './Navbar'
import Footer from './Footer'

// Scrolls window to top on every client-side route change (SPA default doesn't do this)
function ScrollRestoration() {
  const { pathname } = useLocation()
  useEffect(() => { window.scrollTo({ top: 0, behavior: 'instant' }) }, [pathname])
  return null
}

export default function PublicLayout() {
  const [showBackTop, setShowBackTop] = useState(false)

  const onScroll = useCallback(() => {
    setShowBackTop(window.scrollY > 450)
  }, [])

  useEffect(() => {
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [onScroll])

  return (
    <div className="min-h-screen flex flex-col aurora-bg text-slate-100 font-sans">

      {/* ── Accessibility: skip to main content ────────────────────────────── */}
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[9999]
          focus:bg-amber-500 focus:text-indigo-950 focus:font-bold focus:text-sm
          focus:px-6 focus:py-3 focus:rounded-xl focus:shadow-2xl focus:outline-none"
      >
        Skip to content
      </a>

      <ScrollRestoration />

      {/* ── Top Bar ───────────────────────────────────────────────────────── */}
      <TopBar />

      {/* ── Floating Sticky Navbar ────────────────────────────────────────── */}
      <div className="sticky top-2 sm:top-3 z-[100] w-full flex justify-center pointer-events-none">
        <div className="w-full max-w-[1400px] px-4 pointer-events-auto">
          <Navbar />
        </div>
      </div>

      {/* ── Main Content ──────────────────────────────────────────────────── */}
      {/* Each section manages its own container width */}
      <main id="main-content" className="flex-1 min-w-0 w-full flex flex-col">
        <Outlet />
      </main>

      <Footer />

      {/* ── Back to top button ──────────────────────────────────────────────── */}
      <button
        onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
        aria-label="Back to top"
        className={`fixed bottom-6 right-6 z-50 cursor-pointer
          w-12 h-12 rounded-2xl glass text-amber-400
          flex items-center justify-center
          hover:bg-amber-500/20 hover:scale-110 hover:text-amber-300
          active:scale-95
          transition-all duration-300 ease-out
          ${showBackTop
            ? 'opacity-100 translate-y-0 pointer-events-auto'
            : 'opacity-0 translate-y-8 pointer-events-none'
          }`}
      >
        <i className="fas fa-arrow-up text-sm" />
      </button>

    </div>
  )
}
