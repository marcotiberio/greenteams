import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import delegate from 'delegate-event-listener'
import { buildRefs } from '@/assets/scripts/helpers.js'

export default function (el) {
  let isMenuOpen
  const refs = buildRefs(el)
  const navigationHeight = parseInt(window.getComputedStyle(el).getPropertyValue('--navigation-height')) || 0

  const isDesktopMediaQuery = window.matchMedia('(min-width: 1024px)')
  isDesktopMediaQuery.addEventListener('change', onBreakpointChange)

  el.addEventListener('click', delegate('[data-ref="menuButton"]', onMenuButtonClick))

  // Get menu items for animation (including submenu items)
  const getMenuItems = () => {
    if (!refs.menu) return []
    const topLevelItems = refs.menu.querySelectorAll('.item:not(.submenu .item)')
    return Array.from(topLevelItems)
  }

  const menuItems = getMenuItems()

  onBreakpointChange()

  function onMenuButtonClick (e) {
    isMenuOpen = !isMenuOpen
    refs.menuButton.setAttribute('aria-expanded', isMenuOpen)

    if (isMenuOpen) {
      openMenu()
    } else {
      closeMenu()
    }
  }

  function openMenu () {
    el.setAttribute('data-status', 'menuIsOpen')
    disableBodyScroll(refs.menu)
    
    // Animate menu items with stagger
    requestAnimationFrame(() => {
      menuItems.forEach((item, index) => {
        // Set initial state
        item.style.opacity = '0'
        item.style.transform = 'translateY(-20px)'
        item.style.transition = `opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.06}s, transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.06}s`
        
        // Animate submenu items if they exist
        const submenuItems = item.querySelectorAll('.submenu .item')
        submenuItems.forEach((subItem, subIndex) => {
          subItem.style.opacity = '0'
          subItem.style.transform = 'translateX(-15px)'
          subItem.style.transition = `opacity 0.3s ease ${(index * 0.06) + 0.2 + (subIndex * 0.04)}s, transform 0.3s ease ${(index * 0.06) + 0.2 + (subIndex * 0.04)}s`
        })
        
        // Trigger animation
        requestAnimationFrame(() => {
          item.style.opacity = '1'
          item.style.transform = 'translateY(0)'
          
          // Animate submenu items
          submenuItems.forEach((subItem) => {
            subItem.style.opacity = '1'
            subItem.style.transform = 'translateX(0)'
          })
        })
      })
    })
  }

  function closeMenu () {
    // Animate menu items out (reverse order for better UX)
    const reversedItems = [...menuItems].reverse()
    
    reversedItems.forEach((item, index) => {
      item.style.opacity = '0'
      item.style.transform = 'translateY(-10px)'
      item.style.transition = `opacity 0.25s ease ${index * 0.03}s, transform 0.25s ease ${index * 0.03}s`
      
      // Animate submenu items out
      const submenuItems = item.querySelectorAll('.submenu .item')
      submenuItems.forEach((subItem) => {
        subItem.style.opacity = '0'
        subItem.style.transform = 'translateX(-10px)'
        subItem.style.transition = 'opacity 0.2s ease, transform 0.2s ease'
      })
    })

    // Wait for animation to complete before removing status
    const animationDuration = Math.max(250, reversedItems.length * 30)
    setTimeout(() => {
      el.removeAttribute('data-status')
      enableBodyScroll(refs.menu)
      
      // Reset menu items styles
      menuItems.forEach((item) => {
        item.style.opacity = ''
        item.style.transform = ''
        item.style.transition = ''
        
        const submenuItems = item.querySelectorAll('.submenu .item')
        submenuItems.forEach((subItem) => {
          subItem.style.opacity = ''
          subItem.style.transform = ''
          subItem.style.transition = ''
        })
      })
    }, animationDuration)
  }

  function onBreakpointChange () {
    if (!isDesktopMediaQuery.matches) {
      setScrollPaddingTop()
    }
  }

  function setScrollPaddingTop () {
    const scrollPaddingTop = document.getElementById('wpadminbar')
      ? navigationHeight + document.getElementById('wpadminbar').offsetHeight
      : navigationHeight
    document.documentElement.style.scrollPaddingTop = `${scrollPaddingTop}px`
  }
}
