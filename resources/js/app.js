import Alpine from 'alpinejs'

document.addEventListener('alpine:init', () => {

  // ── Hero Slider ──────────────────────────────────────────────────────────
  Alpine.data('heroSlider', (count) => ({
    current: 0,
    count,
    paused: false,
    timer: null,

    init() { this.play() },
    play()  { this.timer = setInterval(() => this.next(), 6000) },
    stop()  { clearInterval(this.timer) },
    next()  { this.current = (this.current + 1) % this.count; this.restart() },
    prev()  { this.current = (this.current - 1 + this.count) % this.count; this.restart() },
    goto(i) { this.current = i; this.restart() },
    restart() { this.stop(); if (!this.paused) this.play() },
    pause()   { this.paused = true;  this.stop() },
    resume()  { this.paused = false; this.play() },
  }))

  // ── Stats Count-Up (easeOut, handles small targets correctly) ───────────
  Alpine.data('statsCounter', (target) => ({
    count: 0,
    _obs: null,

    init() {
      this._obs = new IntersectionObserver(([entry]) => {
        if (!entry.isIntersecting) return
        this._obs.disconnect()
        const duration = 1800
        let elapsed    = 0
        const interval = setInterval(() => {
          elapsed += 16
          const p    = Math.min(elapsed / duration, 1)
          const ease = 1 - Math.pow(1 - p, 3)   // easeOutCubic
          this.count = Math.round(target * ease)
          if (p >= 1) clearInterval(interval)
        }, 16)
      }, { threshold: 0.2 })
      this._obs.observe(this.$el)
    },
    destroy() { this._obs?.disconnect() },
  }))

})

window.Alpine = Alpine
Alpine.start()
