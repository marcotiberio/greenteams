import 'vite/modulepreload-polyfill'
import './scripts/loadCustomElements'
import './scripts/custom'
import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'
import FlyntComponent from './scripts/FlyntComponent'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import 'lazysizes'

if (import.meta.env.DEV) {
  import('@vite/client')
}

window.customElements.define(
  'flynt-component',
  FlyntComponent
)

window.Alpine = Alpine
Alpine.start()
Alpine.plugin(intersect)

gsap.registerPlugin(ScrollTrigger)

import.meta.glob([
  '../Components/**',
  '../assets/**',
  '!**/*.js',
  '!**/*.scss',
  '!**/*.php',
  '!**/*.twig',
  '!**/screenshot.png',
  '!**/*.md'
])
